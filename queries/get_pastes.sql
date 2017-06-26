select 
  pastes.uuid
  , pastes.title
  , pastes.author
  , pastes.lang
  , pastes.created
  , pastes.raw
  , count(*) as views 
from 
  pastes 
  join views 
    on pastes.uuid = views.uuid 
group by 
  pastes.uuid
  , pastes.title
  , pastes.author
  , pastes.lang
  , pastes.created
  , pastes.raw 
order by 
  pastes.created desc