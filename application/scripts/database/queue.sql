drop table if exists jobs;


create table if not exists jobs(
    id bigint unsigned auto_increment not null,
    queue varchar(255) not null,
    params longtext not null,
    attempts tinyint(3) unsigned not null default 0,
    active boolean not null default false,
    available_at timestamp not null default current_timestamp,
    finished_at timestamp null,
    status boolean not null default false,
    created_at timestamp not null default current_timestamp,
    primary key(id),
    index(queue)
);

describe jobs;



