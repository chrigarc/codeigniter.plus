drop table if exists csv_load;

create table if not exists csv_load(
 id bigint unsigned auto_increment not null,
 filename varchar(255) not null,
 batch_size int not null default 100,
 current_index int not null default 0,
 created_at timestamp not null default current_timestamp,
 updated_at timestamp not null default current_timestamp,
 finished_at timestamp null,
 primary key(id),
 index(id)
);
