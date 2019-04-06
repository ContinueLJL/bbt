--建库
CREATE DATABASE `bbt`;

--建表
CREATE TABLE `messages`(
`id` int NOT NULL,
`name` varchar(30) NOT NULL,
`sex` varchar(5) NOT NULL,
`grade` varchar(50) NOT NULL,
`college` varchar(50) NOT NULL,
`dorm` varchar(30) NOT NULL,
`phone_number` varchar(11) NOT NULL,
`branch` varchar(50) NOT NULL,
`second_branch` varchar(50) NOT NULL,
`adjust` varchar(5) NOT NULL,
`time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--查询男女生人数
select count(*) AS 'total number' FROM `messages` WHERE sex='男';

--查询某部门人数
select count(*) AS '***branch\'s num' FROM  `messages` WHERE branch='某部门';

--查询报名技术部的女生人数
select count(*) AS 'emmm' FROM `messages` WHERE branch='技术部' AND sex='女';

--查询报名总人数
select count(*) AS 'total' FROM `messages`;

--联表查询
--table: a b;
--field： id,q,w,e;
--SELECT q,w,e FROM `a` WHERE id<100
--UNION ALL
--SELECT q,w,e FROM `b` WHERE id<100
--ORDER BY q;

--增加：链表查询
select branch,count(case when sex='男' then sex else null end) as 男生人数, 
count(case when sex='女' then sex else null end) as 女生人数,count(sex) from 
(select branch,sex from a union all select branch,sex from b) as c group by branch;
