drop table p01users cascade constraints;
drop table p01myclientsession cascade constraints;
drop table p01student cascade constraints;

drop table p01section cascade constraints;
drop table p01gensection cascade constraints;	

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
  foreign key (clientid) references p01users
);

create table p01student (
stid varchar2(32) primary key,
fname varchar2(32),
lname varchar2(32),
stage number(3), 
staddress varchar2(50),
sttype varchar2(32),
ststatus varchar2(5),
clientid varchar2(8) not null,
foreign key (clientid) references p01users
);

create table p01section(
sectid varchar2(32),
crn varchar2(32),
ctitle varchar2(32),
sem varchar2(30),
credit number(3),
grade number(3),
clientid varchar2(8) not null,
foreign key (clientid) references p01users
);


create table p01gensection(
sectid varchar2(32),
crn varchar2(32),
ctitle varchar2(32),
description varchar2(200),
credit number(3),
sem varchar2(30),
max_size number(3), 
cur_size number(3)
);

insert into p01users values ('a', 'a', '1', '0');
insert into p01users values ('b', 'b', '0', '1');
insert into p01users values ('c', 'c', '1', '1');

insert into p01student values ('stu001', 'John', 'Doe' , 20, '100 N University Dr, Edmond, OK 73034', 'Undergraduate', 'No' , 'b');
insert into p01student values ('stu002', 'Joe', 'Dan' , 22, '100 N University Dr, Edmond, OK 73034', 'Undergraduate', 'Yes' , 'c');

insert into p01section values ('CMSC', '10001' , 'Beginning Programming' ,'Spring 2019', 4, 90, 'b');
insert into p01section values ('CMSC', '10002' , 'Programming 1' ,'Fall 2019', 2, 79, 'b');
insert into p01section values ('CMSC', '10003' , 'Programming 2' , 'Spring 2020' ,3, 88, 'b');
insert into p01section values ('CMSC', '10001' , 'Beginning Programming' , 'Fall 2020' , 3, 89, 'c');

insert into p01gensection values('CMSC', '10001', 'Beginning Programming' , 'This course includes an introduction to programming concepts,  a specific computer 
    language will be used for the implementation of the problem solving
    process.' , 3 , 'Fall 2019',30,0);
	
insert into p01gensection values('CMSC', '10002', 'Programming 1' , 'This class is a survey of (roughly) the first half of the textbook on C++. 
It covers basic C++ statements and some elementary algorithms.', 3 , 'Fall 2019', 30,0);

insert into p01gensection values('CMSC', '10003', 'Programming 2' , 'This is a C++ programming course.  
In this course you are to get a LOT of practice writing C++ programs, 
and at the same time, learning some of the algorithms and techniques available to programmers. 
', 3 , 'Fall 2019', 30,0);

commit;
