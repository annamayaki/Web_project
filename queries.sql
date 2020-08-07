select count(*) from events where activity_type like 'IN_VEHICLE';
select count(*) from events where activity_type like 'ON_BICYCLE';
select count(*) from events where activity_type like 'ON_FOOT';
select count(*) from events where activity_type like 'RUNNING';
select count(*) from events where activity_type like 'STILL';
select count(*) from events where activity_type like 'TILTING';
select count(*) from events where activity_type like 'UNKNOWN';
select count(*) from events where activity_type = null;