-- ****************************** Module Header ****************************** 
-- Module Name:  xm website SQL file
-- Project:      xm website to track everyday expenses
-- Copyright (c) Sundar Krishnamurthy sundar@passion8cakes.com
-- All rights reserved.
--
-- xm.sql file to create the database, load metadata tables and stored procedures - please employ MySQL 5.5 or greater
--
-- 1.    T1:    expenses table - all expenses go here
--
-- Revisions:
--      1. Sundar Krishnamurthy         sundar@passion8cakes.com               3/25/2017       Initial file created.


-- Very, very, very bad things happen if you uncomment this line below. Do at your peril, you have been warned!
-- drop database if exists $$DATABASE_NAME$$;                                                        -- $$ DATABASE_NAME $$

-- Create database $$DATABASE_NAME$$, with utf8 and utf8_general_ci
create database if not exists $$DATABASE_NAME$$ character set utf8 collate utf8_general_ci;       -- $$ DATABASE_NAME $$

-- Employ $$DATABASE_NAME$$
use $$DATABASE_NAME$$;                                                                            -- $$ DATABASE_NAME $$

-- drop table if exists expenses;

-- 1. T1. expenses table to store all individual expenses
create table if not exists expenses (
    expenseId                                 int ( 10 ) unsigned              not null auto_increment,
    userId                                    int ( 10 ) unsigned              not null,
    sequenceId                                int ( 10 ) unsigned              not null,
    sourceCurrencyId                          int ( 10 ) unsigned              default null,
    date                                      datetime                         default null,
    head                                      varchar( 64 )                    default null,
    amount                                    float (12, 2)                    default null,
    floatingAmount                            float (12, 2)                    default null,
    sourceId                                  int ( 10 ) unsigned              default null,
    targetId                                  int ( 10 ) unsigned              default null,
    categoryId                                int ( 10 ) unsigned              default null,
    detail                                    varchar( 256 )                   default null,
    income                                    float ( 12, 2)                   default null,
    floatingIncome                            float ( 12, 2)                   default null,
    targetCurrencyId                          int ( 10 ) unsigned              default null,
    created                                   datetime                         not null,
    lastUpdate                                datetime                         not null,
    timezone                                  float ( 5, 2 )                   default null,
    key ( expenseId ),
    index ix_userId ( userId ),
    index ix_head ( head ),
    index ix_date ( date )
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

insert expenses (
    userId,
    sequenceId,
    sourceCurrencyId,
    date,
    head,
    amount,
    sourceId,
    categoryId,
    detail,
    created,
    lastUpdate,
    timezone )
values (
2,
1,
1,
'2017-01-01',
'Seattle B-Sides',
16.37,
3,
4,
null,
utc_timestamp(),
utc_timestamp(),
-8);

insert expenses (
    userId,
    sequenceId,
    sourceCurrencyId,
    date,
    head,
    amount,
    sourceId,
    categoryId,
    detail,
    created,
    lastUpdate,
    timezone )
values (
2,
2,
1,
'2017-01-01',
'SideCar Enterprises',
100,
3,
4,
null,
utc_timestamp(),
utc_timestamp(),
-8);

drop procedure if exists getHeads;

delimiter //

-- 2. P1. getHeads stored procedure to search for heads, employ provided string
create procedure getHeads(
    in p_userId                               int ( 10 ) unsigned,
    in p_searchTerm                           varchar( 64 ),
    in p_count                                int ( 10 ) unsigned
)
begin

    declare l_query                           varchar( 192 );
    declare l_searchTerm                      varchar( 128 );

    set l_searchTerm = replace(p_searchTerm, '\'', '\'\'');

    set l_query = 'select distinct head from expenses ';
    set l_query = concat(l_query, 'where head like \'%', l_searchTerm, '%\' and '); 
    set l_query = concat(l_query, ' userId=', p_userId);
    set l_query = concat(l_query, ' order by head limit ', p_count, ';');

    set @statement = l_query;
    prepare stmt from @statement;
    execute stmt;
    deallocate prepare stmt;

end //

delimiter ;

call getHeadNames(1,'sid',10);