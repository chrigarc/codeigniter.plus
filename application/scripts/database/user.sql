drop table if exists role_modules;
drop table if exists user_modules;
drop table if exists user_roles;

drop table if exists roles;
drop table if exists users;
drop table if exists modules;

drop table if exists module_groups;


CREATE TABLE IF NOT EXISTS `ci_sessions`
(
    `id`         varchar(128)               NOT NULL,
    `ip_address` varchar(45)                NOT NULL,
    `timestamp`  int(10) unsigned DEFAULT 0 NOT NULL,
    `data`       blob                       NOT NULL,
    KEY `ci_sessions_timestamp` (`timestamp`)
);

create table users
(
    id         bigint unsigned auto_increment not null,
    uuid       varchar(36)                    not null unique,
    email      varchar(255)                   not null unique,
    name       varchar(255)                   not null,
    last_name  varchar(255)                   not null,
    token      varchar(32)                    not null,
    other_name varchar(255)                   null,
    password   varchar(255)                   not null,
    active     boolean                        not null default true,
    created_at timestamp                      not null default current_timestamp,
    updated_at timestamp                      not null default current_timestamp,
    primary key (id),
    index (email)
);

create table if not exists roles
(
    id          bigint unsigned auto_increment not null,
    uuid        varchar(36)                    not null unique,
    name        varchar(255)                   not null,
    description text                           null,
    active      boolean                        not null default true,
    created_at  timestamp                      not null default current_timestamp,
    updated_at  timestamp                      not null default current_timestamp,
    primary key (id),
    index (name)
);

create table if not exists module_groups
(
    id          bigint unsigned auto_increment not null,
    name        varchar(255)                   not null,
    description text                           null,
    created_at  timestamp                      not null default current_timestamp,
    updated_at  timestamp                      not null default current_timestamp,
    primary key (id),
    index (name)
);

create table if not exists modules
(
    id          bigint unsigned auto_increment not null,
    uuid        varchar(36)                    not null unique,
    name        varchar(255)                   not null,
    pattern     varchar(255)                   not null,
    method      varchar(10)                    not null default 'GET',
    description text                           null,
    auth        boolean                        not null default true,
    module_group_id bigint unsigned            null,
    active      boolean                        not null default true,
    created_at  timestamp                      not null default current_timestamp,
    updated_at  timestamp                      not null default current_timestamp,
    primary key (id),
    foreign key(module_group_id) references module_groups(id),
    index (name)
);

create table if not exists role_modules
(
    id         bigint unsigned auto_increment not null,
    role_id    bigint unsigned                not null,
    module_id  bigint unsigned                not null,
    active     boolean                        not null default true,
    created_at timestamp                      not null default current_timestamp,
    updated_at timestamp                      not null default current_timestamp,
    primary key (id),
    foreign key (role_id) references roles (id),
    foreign key (module_id) references modules (id)
);




create table if not exists user_modules
(
    id         bigint unsigned auto_increment not null,
    module_id    bigint unsigned                not null,
    user_id    bigint unsigned                not null,
    active     boolean                        not null default true,
    created_at timestamp                      not null default current_timestamp,
    updated_at timestamp                      not null default current_timestamp,
    primary key (id),
    foreign key (module_id) references modules (id),
    foreign key (user_id) references users (id)
);

create table if not exists user_roles
(
    id         bigint unsigned auto_increment not null,
    user_id    bigint unsigned                not null,
    role_id    bigint unsigned                not null,
    active     boolean                        not null default true,
    created_at timestamp                      not null default current_timestamp,
    updated_at timestamp                      not null default current_timestamp,
    primary key (id),
    foreign key (role_id) references roles (id),
    foreign key (user_id) references users (id)
);

