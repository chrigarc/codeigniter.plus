drop table if exists backend_error;
drop table if exists log;

create table if not exists log(
    id bigint unsigned auto_increment not null,
    type varchar(255) not null,
    content longtext null,
    ip varchar(15) null,
    user_id bigint null,
    created_at timestamp not null default current_timestamp,
    primary key(id),
    index(type),
    index(ip),
    index(user_id)
);

create table if not exists backend_error(
    id bigint unsigned auto_increment not null,
    model_id bigint unsigned null,
    model_type varchar(255) not null,
    exception_trace longtext null,
    created_at timestamp not null default current_timestamp,
    primary key(id),
    index(model_type)
);

describe log;
