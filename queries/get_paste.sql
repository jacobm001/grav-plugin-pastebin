select 
  uuid
  , title
  , created
  , author
  , lang
  , raw
  , (
    select 
      count(*) 
    from 
      views 
    where 
      views.uuid = pastes.uuid
  ) as views 
from 
  pastes 
where 
  uuid = ?;