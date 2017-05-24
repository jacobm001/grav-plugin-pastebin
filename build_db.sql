create table pastes (
  paste_id integer primary key autoincrement
  , uuid text not null
  , title text
  , author text default 'anonymous'
  , lang text default 'txt'
  , created timestamp default current_timestamp
  , raw text
);

create table views (
  view_id integer primary key autoincrement
  , ip text
  , viewed timestamp default current_timestamp
  , uuid text
  , foreign key(uuid) references pastes(uuid)
);