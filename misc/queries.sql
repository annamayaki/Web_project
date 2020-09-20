-------- original supertrouper stuff ----------
ALTER TABLE users 
    ADD COLUMN userid VARCHAR UNIQUE,
    ADD COLUMN firstname VARCHAR NOT NULL DEFAULT 'fname',
    ADD COLUMN lastname VARCHAR NOT NULL DEFAULT 'lname',
    ADD COLUMN email VARCHAR NOT NULL  DEFAULT 'mail';

ALTER TABLE events 
    ADD COLUMN userid VARCHAR;

UPDATE users SET 
    userid = 'WuROtp3cuC1XpCGd4/8yv0kNzMy33Mt54SPwmBB036w=',
    email = 'klelia@icloud.com',
    firstname = 'Κλέλια',
    lastname = 'Λ'
WHERE username = 'klelia';

UPDATE events SET 
    userid = 'yHHFxIjbHDoylTLjMym6PA=='
WHERE username = 'anna';

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
SELECT latitude, longitude, timestampms, (6371 * acos(cos(radians(38.230462)) * cos(radians(latitude) ) *   
    cos(radians(longitude) - radians(21.753150)) + sin(radians(38.230462)) * sin(radians(latitude)))  
) AS distance  
FROM events  
WHERE (6371 * acos(cos(radians(38.230462)) * cos(radians(latitude) ) *   
    cos(radians(longitude) - radians(21.753150)) + sin(radians(38.230462)) * sin(radians(latitude)))  
) < 10  
ORDER BY distance ASC;

SELECT EXTRACT(HOUR FROM timestampms) AS hourOfDay, count(*) 
FROM events 
WHERE activity_type LIKE 'IN_VEHICLE' 
GROUP BY hourOfDay
ORDER BY count(*) DESC
LIMIT 1;

SELECT EXTRACT(HOUR FROM timestampms) AS hourOfDay, count(*) 
FROM events 
WHERE activity_type LIKE 'IN_VEHICLE' 
GROUP BY hourOfDay
ORDER BY count(*) DESC
LIMIT 1;

select timestampms, date(date(timestampms) - interval '1 year') 
from events order by timestampms limit 3;

select timestampms, age(now(), timestampms) < interval '1 year'
from events 
-- where date_age < interval '1 year'
order by date_age asc limit 3;

SELECT username, count(timestampms) AS score 
FROM events 
-- WHERE age(now(), timestampms) < interval '1 month'
WHERE age(now(), timestampms) < interval '2 years'
AND (activity_type = 'ON_BICYCLE' OR activity_type = 'ON_FOOT' OR activity_type = 'RUNNING')
GROUP BY username;

SELECT username, count(*) AS score 
-- SELECT username, count(timestampms) AS score 
FROM events 
-- WHERE age(now(), timestampms) < interval '1 month'
WHERE age(now(), timestampms) < interval '1 years'
GROUP BY username;


-- SELECT users.firstname, users.lastname, (num::float/denom::float) as score
SELECT users.email, (num::float/denom::float) as score
FROM 
(SELECT username, count(*) as num
FROM events
WHERE age(now(), timestampms) < interval '2 years'
AND (activity_type = 'ON_BICYCLE' OR activity_type = 'ON_FOOT' 
OR activity_type = 'RUNNING')
GROUP BY username) eco_activs
INNER JOIN 
(SELECT username, count(*) as denom
FROM events
WHERE age(now(), timestampms) < interval '2 years'
GROUP BY username) all_activs
ON all_activs.username = eco_activs.username
INNER JOIN users 
ON all_activs.username = users.username
ORDER BY score DESC
LIMIT 3;

CREATE OR REPLACE FUNCTION get_freq_table()
RETURNS TABLE(email VARCHAR, score FLOAT) AS $$
BEGIN
  RETURN QUERY
    SELECT users.email, (num::float/denom::float) as score
    FROM 
    (SELECT username, count(*) as num
    FROM events
    WHERE age(now(), timestampms) < interval '2 years'
    AND (activity_type = 'ON_BICYCLE' OR activity_type = 'ON_FOOT' 
    OR activity_type = 'RUNNING')
    GROUP BY username) eco_activs
    INNER JOIN 
    (SELECT username, count(*) as denom
    FROM events
    WHERE age(now(), timestampms) < interval '2 years'
    GROUP BY username) all_activs
    ON all_activs.username = eco_activs.username
    INNER JOIN users 
    ON all_activs.username = users.username
    ORDER BY score DESC
    LIMIT 3;
END;
$$ LANGUAGE 'plpgsql';

-- SELECT * FROM get_freq_table();

CREATE OR REPLACE FUNCTION calc_eco_score_rank()
RETURNS TABLE(firstname VARCHAR, lastname VARCHAR, score FLOAT) AS $$
BEGIN
  RETURN QUERY
    SELECT users.firstname, users.lastname, (num::float/denom::float) as score
    FROM 
    (SELECT userid, count(*) as num
    FROM events
    WHERE age(now(), timestampms) < interval '1 month'
    AND (activity_type = 'ON_BICYCLE' OR activity_type = 'ON_FOOT' 
    OR activity_type = 'RUNNING')
    GROUP BY userid) eco_activs
    INNER JOIN 
    (SELECT userid, count(*) as denom
    FROM events
    WHERE age(now(), timestampms) < interval '1 month'
    GROUP BY userid) all_activs
    ON all_activs.userid = eco_activs.userid
    INNER JOIN users 
    ON all_activs.userid = users.userid
    ORDER BY score DESC
    LIMIT 3;
END;
$$ LANGUAGE 'plpgsql'

-- SELECT * FROM calc_eco_score_rank();

insert into events (userid, longitude, latitude, timestampms)
values ('yHHFxIjbHDoylTLjMym6PA==', -74.0071296, 40.7130125, now());

insert into events (userid, longitude, latitude, timestampms)
values ('yHHFxIjbHDoylTLjMym6PA==', 21.7333506, 38.2453894, now());