create database `forum_db`;

use `forum_db`;

create table if not exists `user` 
(
    `user_id` int auto_increment not null,
    `name` tinytext,
    `surname` tinytext,
    `email` tinytext not null,
    `country` tinytext,
    `state` tinytext,
    `city` tinytext,
    `user_name` tinytext not null,
    `password` text not null,
    `profile_picture` text not null,
    `is_mod` tinyint not null default 0,
    `deleted` tinyint not null default 0,
    primary key(`user_id`),
    unique key(`user_name`(75), `email`(75))
);

create table if not exists `forum`
(
    `forum_id` int auto_increment not null,
    `name` tinytext not null,
    `description` text,
    `category_id` int not null default -1,
    primary key(`forum_id`)
);

create table if not exists `thread`
(
    `thread_id` int auto_increment not null,
    `name` tinytext not null,
    `forum_id` int not null,
    `user_id` int not null,
    primary key(`thread_id`)
);

create table if not exists `post`
(
    `post_id` int auto_increment not null,
    `user_id` int not null, 
    `thread_id` int not null, 
    `post` mediumblob not null, 
    `creation_date` datetime not null, 
    `modification_date` datetime, 
    primary key(`post_id`)
);

create index `index user_name pass email is_mod` on `user` (`user_name`(75), `password`(255), `email`(75), `is_mod`); 
create index `index thread_id on post` on `post` (`thread_id`);
create index `index forum_id on thread` on `thread` (`forum_id`);
