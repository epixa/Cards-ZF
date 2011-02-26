-- Remove foreign key constraints so we can drop all the tables
-- Save the original check value so we can revert it
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- core module

drop table if exists core_acl_rule;
create table core_acl_rule (
    id int not null auto_increment,
    resource_id varchar(255) not null,
    role_id varchar(255) not null,
    privilege varchar(255),
    `assertion` varchar(255),
    primary key(id),
    unique key(resource_id, role_id)
) engine=innodb;


-- user module

drop table if exists `user`;
create table `user` (
    id int not null auto_increment,
    `alias` varchar(255) not null,
    primary key(id)
) engine=innodb;

drop table if exists user_auth;
create table user_auth (
    id int not null auto_increment,
    user_id int not null,
    login_id varchar(255) not null,
    pass_hash varchar(255) not null,
    primary key(id),
    constraint auth_has_user foreign key(user_id) references `user`(id) on delete cascade
) engine=innodb;

drop table if exists user_profile;
create table user_profile (
    id int not null auto_increment,
    user_id int not null,
    email varchar(255) not null,
    primary key(id),
    constraint profile_has_user foreign key(user_id) references `user`(id) on delete cascade
) engine=innodb;

drop table if exists user_session;
create table user_session (
    id int not null auto_increment,
    user_id int not null,
    session_key varchar(255) not null,
    last_activity datetime not null,
    primary key(id),
    unique key(session_key),
    constraint session_has_user foreign key(user_id) references `user`(id) on delete cascade
) engine=innodb;

-- Set the foreign key checks back to the original value
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;