drop table p01enrolledcourses;
drop table my_tmp;

drop table p01gensection cascade constraints;	
drop table p01myclientsession cascade constraints;
drop table p01users cascade constraints;
drop table p01student cascade constraints;
drop table p01section cascade constraints;


create table p01users (
	clientid varchar2(8) primary key,
	password varchar2(12),
	aflag varchar2(1),
	sflag varchar2(1)
);

create table p01myclientsession (
  sessionid varchar2(32) primary key,
  clientid varchar2(8),
  sessiondate date,
  foreign key (clientid) references p01users ON DELETE CASCADE
);

create table p01student (
stid varchar2(32) primary key,
fname varchar2(32) not null,
lname varchar2(32) not null,
clientid varchar2(8) not null,
age number(3), 
street varchar2(30),
city varchar2(30),
state varchar2(30),
zipcode varchar2(5),
sttype varchar2(1),
status varchar2(1),
gpa number(2,1),
foreign key (clientid) references p01users ON DELETE CASCADE
);

create table p01section( /*Classes for each student if they need prereq*/
crn varchar2(32) primary key ,
ctitle varchar2(32),
credit number(1),
prereq1 varchar2(6),
prereq2 varchar2(6),
foreign key (prereq1) references p01section(crn),
foreign key (prereq2) references p01section(crn)
);


create table p01gensection( /*Sections Of Courses/Casses*/
crn varchar2(32) not null,
sectid number(4) not null,
sem number(4) not null,
stime varchar2(10),
max_size number(3), 
cur_size number(3),
deadline date,
primary key ( crn, sectid, sem),
foreign key (crn) references p01section
);

create table p01enrolledcourses (
	stid varchar2(8) not null,
	crn varchar2(6) not null,
	sem number(4) not null,
	sectid number(4) not null,
	enrollflag varchar2(1),
	grade number(1),
	primary key (stid, crn, sectid, sem),
	foreign key (stid) references p01student ON DELETE CASCADE,
	foreign key (crn,sectid, sem) references p01gensection(crn, sectid, sem)
);

create table my_tmp(
	my_grade number,
	my_credits number
);

create or replace procedure create_new_id(
	i_fname in varchar2,
	i_lname in varchar2, 
	i_age in number, 
	i_street in varchar2, 
	i_city in varchar2,
	i_state in varchar2,
	i_zipcode in varchar2,
	i_sttype in varchar2, 
	i_status in varchar2, 
	i_id out varchar2
	)
	is
	i_count number;
	begin
		lock table p01users in row exclusive mode nowait;
		lock table p01student in row exclusive mode nowait;
		select count(*) into i_count from p01student;
		i_count := i_count + 1;
		i_id := substr(i_fname, 1, 1) || substr(i_lname, 1, 1) || to_char(i_count, 'FM000000');
		insert into p01users values (i_id, i_lname, '0', '1');
		insert into p01student (stid, fname, lname, age, street, city, state, zipcode, sttype, status, clientid) 
						values (i_id, i_fname, i_lname, i_age, i_street, i_city, i_state, i_zipcode, i_sttype, i_status, i_id);
		commit;
	end;
/
		

--update_gpa procedure
create or replace procedure update_gpa(student_id in varchar2) as
	CURSOR c1 is select grade, credit from p01section natural join p01enrolledcourses
	where stid = student_id and enrollflag = 0;

	my_grade number;
	my_credits number;

	grade_total number;
	credits_total number;

	my_gpa number(3,1);
	begin

		delete from my_tmp;
		commit;
		
		open c1;
		LOOP
			fetch c1 into my_grade, my_credits;
			EXIT WHEN c1%NOTFOUND;
			my_grade := my_grade * my_credits;
			insert into my_tmp values(my_grade, my_credits);
			commit;
		END LOOP;
		close c1;
		select SUM(my_grade) into grade_total from my_tmp;
		select SUM(my_credits) into  credits_total from my_tmp;
		my_gpa := grade_total/credits_total;
		--dbms_output.put_line(my_gpa);
		update p01student set gpa = my_gpa where stid = student_id;
		commit;
	end;
	/
insert into p01users values ('a', 'a', '1', '0');
insert into p01users values ('JD000001', 'b', '0', '1');
insert into p01users values ('JD000002', 'c', '1', '1');

insert into p01student values ('JD000001', 'John', 'Doe'  , 'JD000001' , 20, '100 N University Dr' , 'Edmond' , 'OK' , '73034', 'U', 'N' , null);
insert into p01student values ('JD000002', 'Joe', 'Dan' , 'JD000002' , 22, '100 N University Dr' , 'Edmond', 'OK' , '73034', 'U' , 'Y', null );

insert into p01section values ( 'cs1111', 'Intro to Computers', 3, null, null);
insert into p01section values ( 'ma1111', 'Math 1', 4, null, null);
insert into p01section values ( 'cs2111', 'Programming 1', 3, 'cs1111', null);
insert into p01section values ( 'cs2211', 'Programming 2', 3, 'cs2111', 'ma1111');
insert into p01section values ( 'ma2111', 'Math 2', 4, 'ma1111', null);
insert into p01section values ( 'ma2211', 'Math 3', 4, 'ma1111', 'ma2111');
--list of course/classes
insert into p01gensection values ('cs1111', 0001, 2020,    0,  3, 3, TO_DATE('20200101', 'yyyymmdd'));
insert into p01gensection values ('cs2111', 0001, 2020,    0,  2, 1, TO_DATE('20200101', 'yyyymmdd'));
insert into p01gensection values ('cs2211', 0001, 2020,    0,  2, 0, TO_DATE('20200101', 'yyyymmdd'));
insert into p01gensection values ('cs1111', 0001, 2021, 1300,  3, 0, TO_DATE('20211225', 'yyyymmdd'));
insert into p01gensection values ('cs2111', 0001, 2021, 1400,  2, 0, TO_DATE('20211101', 'yyyymmdd'));
insert into p01gensection values ('cs2211', 0001, 2021, 1500,  1, 0, TO_DATE('20211225', 'yyyymmdd'));
--ma sections
insert into p01gensection values ('ma1111', 0001, 2020,    0,  3, 3, TO_DATE('20190101', 'yyyymmdd'));
insert into p01gensection values ('ma2111', 0001, 2020,    0,  2, 1, TO_DATE('20190101', 'yyyymmdd'));
insert into p01gensection values ('ma2211', 0001, 2020,    0,  2, 0, TO_DATE('20190101', 'yyyymmdd'));
insert into p01gensection values ('ma1111', 0001, 2021, 1300,  3, 0, TO_DATE('20211225', 'yyyymmdd'));
insert into p01gensection values ('ma2111', 0001, 2021, 1400,  2, 0, TO_DATE('20211101', 'yyyymmdd')); 
insert into p01gensection values ('ma2211', 0001, 2021, 1500,  1, 0, TO_DATE('20211225', 'yyyymmdd'));
--student enrolled into a course
insert into p01enrolledcourses values ( 'JD000001', 'cs1111', 2020, 0001, 0, 1);

create or replace procedure check_deadline
	(my_crn in varchar2, my_sectid in varchar2, my_sem in number, my_error out varchar2)
	is
	my_date date := CURRENT_DATE;
	my_deadline date;

	begin
		select deadline into my_deadline from p01gensection
			where crn = my_crn and sectid = my_sectid and sem = my_sem;
		IF my_deadline < my_date THEN
			dbms_output.put_line('deadline passed error');
			my_error := 'Enroll deadline passed for class ' 
				|| my_crn
				|| ' section '
				|| my_sectid;
		END IF;
	END;
	/


create or replace procedure check_passed_course
	(my_crn in varchar2, my_stid in varchar2, my_error out varchar2)
	is
	my_grade number;
	begin
		select max(grade) into my_grade from p01enrolledcourses 
			where crn = my_crn and stid = my_stid;
		IF my_grade IS NOT NULL THEN
			IF my_grade > 1 THEN
				my_error := 'Class '
					|| my_crn
					|| ' previously passed';
			END IF;
		END IF;
	END;
	/


create or replace procedure check_prereq
	(my_crn in varchar2, my_sectid in varchar2, my_stid in varchar2, my_error out varchar2)
	is
	my_prereq varchar2(30);
	my_tmp number;
	begin
		--check prereq1
		select prereq1 into my_prereq from p01section
			where crn = my_crn;
		IF my_prereq IS NOT NULL THEN
			select count(*) into my_tmp from p01enrolledcourses
				where crn = my_prereq and stid = my_stid and enrollflag = 0;
			IF my_tmp = 0 THEN
				my_error := 'Prereq1 not taken for class '
					|| my_crn
					|| ' section '
					|| my_sectid;
			END IF;
		END IF;

		--check prereq2
		select prereq2 into my_prereq from p01section 
			where crn = my_crn;
		IF my_prereq IS NOT NULL THEN
			select count(*) into my_tmp from p01enrolledcourses
				where crn = my_prereq and stid = my_stid and enrollflag = 0;
			IF my_tmp = 0 THEN
				my_error := my_error 
					|| 'Prereq2 not taken for class '
					|| my_crn
					|| ' section '
					|| my_sectid;
			END IF;
		END IF;
	END;
	/


create or replace procedure check_seat_available
	(my_crn in varchar2, my_sectid in varchar2, my_sem in number, my_stid in varchar2, my_error out varchar2)
	is
	my_students number;
	my_max_students number;
	my_tmp number;
	begin
		delete from my_tmp;
		select count(*) into my_tmp from p01enrolledcourses
			where crn = my_crn and sectid = my_sectid and sem=my_sem and enrollflag = 1;
		IF my_tmp = 0 THEN
			select cur_size, max_size into my_students, my_max_students from p01gensection
				where crn = my_crn and sectid = my_sectid and sem = my_sem FOR UPDATE;
			IF (my_max_students - my_students) > 0 THEN
				insert into p01enrolledcourses values (my_stid, my_crn, my_sem, my_sectid, 1, NULL);
				my_students := my_students + 1; 
				update p01gensection set cur_size = my_students
					where crn = my_crn and sectid = my_sectid and sem = my_sem;
				COMMIT;
			ELSE
				ROLLBACK;
				my_error := 'No seats available for '
					|| my_crn
					|| ' section '
					|| my_sectid;
			END IF;
		ELSE
			my_error := 'Currently enrolled in '
				|| my_crn
				|| ' section '
				|| my_sectid;
		END IF;
	END;
	/




commit;
