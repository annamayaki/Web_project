select count(*) from events where activity_type like 'IN_VEHICLE';
select count(*) from events where activity_type like 'ON_BICYCLE';
select count(*) from events where activity_type like 'ON_FOOT';
select count(*) from events where activity_type like 'RUNNING';
select count(*) from events where activity_type like 'STILL';
select count(*) from events where activity_type like 'TILTING';
select count(*) from events where activity_type like 'UNKNOWN';
select count(*) from events where activity_type is null;

-- SELECT DISTINCT EXTRACT(MONTH FROM timestampms) FROM events;
SELECT longitude, latitude FROM events WHERE EXTRACT(MONTH FROM timestampms) = 12;
-- SELECT DISTINCT EXTRACT(DOW FROM timestampms) FROM events;
SELECT longitude, latitude FROM events WHERE EXTRACT(DOW FROM timestampms) = 0;
-- SELECT DISTINCT EXTRACT(HOUR FROM timestampms) FROM events;
SELECT longitude, latitude FROM events WHERE EXTRACT(HOUR FROM timestampms) = 0;
-- SELECT DISTINCT EXTRACT(YEAR FROM timestampms) FROM events;
SELECT longitude, latitude FROM events WHERE EXTRACT(YEAR FROM timestampms) = 2019;

SELECT count(*) FROM events WHERE EXTRACT(YEAR FROM timestampms) = 2019
AND EXTRACT(MONTH FROM timestampms) = 11 AND activity_type LIKE 'IN_VEHICLE';

SELECT count(*) FROM events WHERE EXTRACT(YEAR FROM timestampms) = 2019
AND EXTRACT(MONTH FROM timestampms) BETWEEN 10 AND 12
AND activity_type LIKE 'IN_VEHICLE';

SELECT count(*) FROM events WHERE activity_type LIKE 'IN_VEHICLE' OR activity_type LIKE 'ON_FOOT';

-- select points where distance from patras > 10km -> modify as trigger on insert
SELECT timestampms, (6371 * acos(cos(radians(38.230462)) * cos(radians(latitude) ) *   
    cos(radians(longitude) - radians(21.753150)) + sin(radians(38.230462)) * sin(radians(latitude)))  
) AS distance  
FROM events  
WHERE (6371 * acos(cos(radians(38.230462)) * cos(radians(latitude) ) *   
    cos(radians(longitude) - radians(21.753150)) + sin(radians(38.230462)) * sin(radians(latitude)))  
) < 10  
ORDER BY distance DESC;