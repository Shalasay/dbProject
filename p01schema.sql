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
	grade number(3),
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
insert into p01enrolledcourses values ( 'JD000001', 'cs1111', 2020, 0001, 0, 100);
insert into p01enrolledcourses values ( 'JD000001', 'cs2111', 2020, 0001, 0, 70);
insert into p01enrolledcourses values ( 'JD000001', 'cs2211', 2020, 0001, 0, 80);

insert into p01enrolledcourses values ( 'JD000002', 'cs1111', 2020, 0001, 0, 50);
insert into p01enrolledcourses values ( 'JD000002', 'cs2111', 2020, 0001, 0, 60);
insert into p01enrolledcourses values ( 'JD000002', 'cs2211', 2020, 0001, 0, 40);

create or replace procedure check_deadline
	(my_crn in varchar2, my_sectid in varchar2, my_sem in number, my_error out varchar2)
	AS
	my_date date := CURRENT_DATE;
	my_deadline date;
	begin
		select deadline into my_deadline from p01gensection
			where crn = my_crn and sectid = my_sectid and sem = my_sem;
		IF my_deadline < my_date THEN
			my_error := 'Enroll deadline passed for class ' 
				|| my_crn
				|| my_sectid;
		END IF;
	END;
/
commit;
