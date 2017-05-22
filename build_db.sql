create table pastes (
  paste_id integer primary key autoincrement
  , title text
  , author text
  , lang text
  , created datetime
  , raw text
);

create table views (
  view_id integer primary key autoincrement
  , ip4 text
  , ip6 text
  , viewed datetime
  , paste_id
  foreign key(paste_id) references pastes(paste_id)
);