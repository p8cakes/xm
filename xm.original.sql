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

-- drop table if exists sourceTargets;

-- 1. T1. sourceTargets table to store all expense sources and targets
create table if not exists sourceTargets (
    sourceTargetId                            int ( 10 ) unsigned              not null auto_increment,
    sourceTarget                              varchar( 32 )                    not null,
    userId                                    int ( 10 ) unsigned,
    enabled                                   tinyint ( 1 ) unsigned not null default 0,
    created                                   datetime not null,
    lastUpdate                                datetime not null,
    key ( sourceTargetId ),
    unique index ix_userId_sourceTarget ( userId, sourceTarget )
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

drop procedure if exists getSourceTargets;

delimiter //

create procedure getSourceTargets(
    in p_userId                               int ( 10 ) unsigned,
    in p_enabled                              tinyint ( 1 )
)
begin

    if p_enabled is null then

        select sourceTargetId, sourceTarget, enabled from sourceTargets
        where userId = p_userId
        order by sourceTarget;

    else

        select sourceTargetId, sourceTarget, enabled from sourceTargets
        where userId = p_userId
        and enabled = p_enabled
        order by sourceTarget;

    end if;

end //


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

-- drop table if exists worldCurrencies;

-- 1. T1. sourceTargets table to store all expense sources and targets
create table if not exists worldCurrencies (
    currencyId                                int ( 10 ) unsigned              not null auto_increment,
    name                                      varchar( 32 ) default null,
    symbol                                    varchar( 8 ) default null,
    abbr                                      varchar( 8 ) default null,
    image                                     varchar( 128 ) default null,
    enabled                                   tinyint ( 1 ) unsigned not null default 0,
    created                                   datetime not null,
    lastUpdate                                datetime not null,
    key ( currencyId ),
    unique index ix_worldCurrencies_name ( name )
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

drop procedure if exists populateWorldCurrencies;

delimiter //

create procedure populateWorldCurrencies()
begin

    declare l_currencyCount                   int ( 10 ) unsigned;

    select count(*) into l_currencyCount from worldCurrencies;

    if l_currencyCount = 0 then

        insert worldCurrencies ( name, symbol, abbr, image, enabled, created, lastUpdate)
        values ('Australian dollar', '$', 'AUD', 'https://upload.wikimedia.org/wikipedia/en/thumb/b/b9/Flag_of_Australia.svg/23px-Flag_of_Australia.svg.png', 1, utc_timestamp(), utc_timestamp());
        insert worldCurrencies ( name, symbol, abbr, image, enabled, created, lastUpdate)
        values ('Brazilian real', '$', 'BRL', 'https://upload.wikimedia.org/wikipedia/en/thumb/0/05/Flag_of_Brazil.svg/22px-Flag_of_Brazil.svg.png', 1, utc_timestamp(), utc_timestamp());
        insert worldCurrencies ( name, symbol, abbr, image, enabled, created, lastUpdate)
        values ('Canadian dollar', '$', 'CAD', 'https://upload.wikimedia.org/wikipedia/en/thumb/c/cf/Flag_of_Canada.svg/23px-Flag_of_Canada.svg.png', 1, utc_timestamp(), utc_timestamp());
        insert worldCurrencies ( name, symbol, abbr, image, enabled, created, lastUpdate)
        values ('Chinese yuan', '¥', 'CNY', 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Flag_of_the_People%27s_Republic_of_China.svg/23px-Flag_of_the_People%27s_Republic_of_China.svg.png', 1, utc_timestamp(), utc_timestamp());
        insert worldCurrencies ( name, symbol, abbr, image, enabled, created, lastUpdate)
        values ('Euro', '€', 'EUR', 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/Flag_of_Europe.svg/23px-Flag_of_Europe.svg.png', 1, utc_timestamp(), utc_timestamp());
        insert worldCurrencies ( name, symbol, abbr, image, enabled, created, lastUpdate)
        values ('Hong Kong dollar', '$', 'HKD', 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5b/Flag_of_Hong_Kong.svg/23px-Flag_of_Hong_Kong.svg.png', 1, utc_timestamp(), utc_timestamp());
        insert worldCurrencies ( name, symbol, abbr, image, enabled, created, lastUpdate)
        values ('Indian rupee', '₹', 'INR', 'https://upload.wikimedia.org/wikipedia/en/thumb/4/41/Flag_of_India.svg/23px-Flag_of_India.svg.png', 1, utc_timestamp(), utc_timestamp());
        insert worldCurrencies ( name, symbol, abbr, image, enabled, created, lastUpdate)
        values ('Japanese yen', '¥', 'JPY', 'https://upload.wikimedia.org/wikipedia/en/thumb/9/9e/Flag_of_Japan.svg/23px-Flag_of_Japan.svg.png', 1, utc_timestamp(), utc_timestamp());
        insert worldCurrencies ( name, symbol, abbr, image, enabled, created, lastUpdate)
        values ('Mexican peso', '$', 'MXN', 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fc/Flag_of_Mexico.svg/23px-Flag_of_Mexico.svg.png', 1, utc_timestamp(), utc_timestamp());
        insert worldCurrencies ( name, symbol, abbr, image, enabled, created, lastUpdate)
        values ('New Zealand dollar', '$', 'NZD', 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3e/Flag_of_New_Zealand.svg/23px-Flag_of_New_Zealand.svg.png', 1, utc_timestamp(), utc_timestamp());
        insert worldCurrencies ( name, symbol, abbr, image, enabled, created, lastUpdate)
        values ('Norwegian krone', 'kr', 'NOK', 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d9/Flag_of_Norway.svg/21px-Flag_of_Norway.svg.png', 1, utc_timestamp(), utc_timestamp());
        insert worldCurrencies ( name, symbol, abbr, image, enabled, created, lastUpdate)
        values ('Pound Sterling', '£', 'GBP', 'https://upload.wikimedia.org/wikipedia/en/thumb/a/ae/Flag_of_the_United_Kingdom.svg/23px-Flag_of_the_United_Kingdom.svg.png', 1, utc_timestamp(), utc_timestamp());
        insert worldCurrencies ( name, symbol, abbr, image, enabled, created, lastUpdate)
        values ('Russian ruble', '₽', 'RUB', 'https://upload.wikimedia.org/wikipedia/en/thumb/f/f3/Flag_of_Russia.svg/23px-Flag_of_Russia.svg.png', 1, utc_timestamp(), utc_timestamp());
        insert worldCurrencies ( name, symbol, abbr, image, enabled, created, lastUpdate)
        values ('South African rand', 'R', 'ZAR', 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/af/Flag_of_South_Africa.svg/23px-Flag_of_South_Africa.svg.png', 1, utc_timestamp(), utc_timestamp());
        insert worldCurrencies ( name, symbol, abbr, image, enabled, created, lastUpdate)
        values ('South Korean won', '₩', 'KRW', 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/09/Flag_of_South_Korea.svg/23px-Flag_of_South_Korea.svg.png', 1, utc_timestamp(), utc_timestamp());
        insert worldCurrencies ( name, symbol, abbr, image, enabled, created, lastUpdate)
        values ('Swedish krona', 'kr', 'SEK', 'https://upload.wikimedia.org/wikipedia/en/thumb/4/4c/Flag_of_Sweden.svg/23px-Flag_of_Sweden.svg.png', 1, utc_timestamp(), utc_timestamp());
        insert worldCurrencies ( name, symbol, abbr, image, enabled, created, lastUpdate)
        values ('Swiss franc', 'Fr', 'CHF', 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Flag_of_Switzerland.svg/16px-Flag_of_Switzerland.svg.png', 1, utc_timestamp(), utc_timestamp());
        insert worldCurrencies ( name, symbol, abbr, image, enabled, created, lastUpdate)
        values ('Turkish lira', '₺', 'TRY', 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b4/Flag_of_Turkey.svg/23px-Flag_of_Turkey.svg.png', 1, utc_timestamp(), utc_timestamp());
        insert worldCurrencies (name, symbol, abbr, image, enabled, created, lastUpdate)
        values ('United States dollar', '$', 'USD', 'https://upload.wikimedia.org/wikipedia/en/thumb/a/a4/Flag_of_the_United_States.svg/23px-Flag_of_the_United_States.svg.png', 1, utc_timestamp(), utc_timestamp());

    end if;
end //

delimiter ;

call populateWorldCurrencies();

drop procedure populateWorldCurrencies;

drop procedure if exists getWorldCurrencies;

delimiter //

create procedure getWorldCurrencies(
    in p_enabled                              int( 10 ) unsigned
)
begin

    if p_enabled is null then

        select
          currencyId,
          name,
          symbol,
          abbr,
          image,
          enabled,
          created,
          lastUpdate
        from
          worldCurrencies
        order by
          currencyId;

    else

        select
          currencyId,
          name,
          symbol,
          abbr,
          image,
          enabled,
          created,
          lastUpdate
        from
          worldCurrencies
        where
          enabled = p_enabled
        order by
          currencyId;

    end if;

end //

delimiter ;

call getWorldCurrencies();
