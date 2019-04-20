drop table p01users cascade constraints;
drop table p01myclientsession cascade constraints;
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
sem date,
credit number(3),
grade number(3),
clientid varchar2(8) not null,
foreign key (clientid) references p01users
);




insert into p01users values ('a', 'a', '1', '0');
insert into p01users values ('b', 'b', '0', '1');
insert into p01users values ('c', 'c', '1', '1');
commit;

insert into p01student values ('stu001', 'John', 'Doe' , 20, '100 N University Dr, Edmond, OK 73034', 'Undergraduate', 'No' , 'b');
insert into p01student values ('stu002', 'Joe', 'Dan' , 22, '100 N University Dr, Edmond, OK 73034', 'Undergraduate', 'Yes' , 'c');

insert into p01section values ('CMSC', '10001' , 'Beginning Programming' ,TO_DATE('2019/01/14', 'yyyy/mm/dd'), 3, 90, 'b');
insert into p01section values ('CMSC', '10002' , 'Programming 1' ,TO_DATE('2020/01/14', 'yyyy/mm/dd'), 3, 79, 'b');
insert into p01section values ('CMSC', '10002' , 'Programming 2' ,TO_DATE('2021/01/14', 'yyyy/mm/dd'), 3, 88, 'b');
insert into p01section values ('CMSC', '10001' , 'Beginning Programming' ,TO_DATE('2019/01/14', 'yyyy/mm/dd'), 3, 89, 'c');

commit;
