create table Publisher (
	name varchar2(8) primary key,
	phone varchar2(12),
	city varchar2(8)
);

create table Book (
  ISBN varchar2(32) primary key,
  title varchar2(8),
  year number(4),
  published_by varchar2(8),
  previous_edition varchar2(32),
  price number(8),
  foreign key (published_by) references Publisher,
  foreign key (previous_edition) references Book
);

create table Editor(
	SSN number(9) primary key,
	first_name varchar2(8),
	last_name varchar2(8),
	address varchar2(32),
	salary number(8),
	works_for varchar2(8),
	book_count number(4),
	foreign key (works_for) references Publisher
);

create table Edit(
	eSSN number(9),
	bISBN varchar2(32),
	foreign key(eSSN) references Editor,
	foreign key(bISBN) references Book
);


commit;

select p.name as Publisher, b.title as Book_Title 
from Book b, Publisher p, Editor e, Edit et 
where b.published_by = p.name and p.name = e.works_for and e.SSN = et.eSSN and et.bISBN = b.ISBN 
group by p.name, b.title having count(*) = (
select max(temp) 
from (
select p.name as Publisher,b.title as Book_Title, count(*) as temp 
from Book b, Publisher p, Editor e, Edit et 
where b.published_by = p.name and p.name = e.works_for and e.SSN = et.eSSN and et.bISBN = b.ISBN 
group by p.name, b.title));