drop table p01users cascade constraints;
drop table p01myclientsession cascade constraints;

create table p01users (
	clientid varchar2(8) primary key,
	password varchar2(12),
	clienttype varchar2(8)
);

create table p01myclientsession (
  sessionid varchar2(32) primary key,
  clientid varchar2(8),
  sessiondate date,
  foreign key (clientid) references p01users
);

insert into p01users values ('a', 'a', 'admin');
insert into p01users values ('b', 'b', 'stu');
insert into p01users values ('c', 'c', 'stuadmin');
commit;

