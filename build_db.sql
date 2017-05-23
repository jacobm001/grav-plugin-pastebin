create table pastes (
  paste_id integer primary key autoincrement
  , title text
  , author text default 'anonymous'
  , lang text default 'txt'
  , created timestamp default current_timestamp
  , raw text
);

create table views (
  view_id integer primary key autoincrement
  , ip4 text
  , ip6 text
  , viewed timestamp default current_timestamp
  , paste_id
  , foreign key(paste_id) references pastes(paste_id)
);