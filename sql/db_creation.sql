DROP DATABASE IF EXISTS supertrouper;
CREATE DATABASE supertrouper
    WITH 
    OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'Greek_Greece.1253'
    LC_CTYPE = 'Greek_Greece.1253'
    TABLESPACE = pg_default
    CONNECTION LIMIT = -1
;

\c supertrouper;

-- CREATE EXTENSION postgis;

CREATE TYPE site_user_type AS ENUM('user', 'admin');

DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users (
    userid VARCHAR UNIQUE NOT NULL PRIMARY KEY,
    firstname VARCHAR NOT NULL,
    lastname VARCHAR NOT NULL,
    username VARCHAR NOT NULL,
    email VARCHAR NOT NULL,
    password VARCHAR NOT NULL,
    user_type site_user_type DEFAULT 'user' NOT NULL,
    last_upload_date DATE DEFAULT NULL
);

-- CREATE TYPE detected_activity_type AS ENUM('IN_VEHICLE', 'ON_BICYCLE',
-- 'ON_FOOT', 'RUNNING', 'STILL', 'TILTING', 'UNKNOWN', 'WALKING');

DROP TABLE IF EXISTS events;
CREATE TABLE IF NOT EXISTS events (
    userid VARCHAR NOT NULL,
    -- username VARCHAR NOT NULL,
    heading INT,
    activity_type VARCHAR,
    activity_confidence INT,
    activity_timestampms TIMESTAMP,
    verticalaccuracy INT,
    velocity INT,
    accuracy INT,
    longitude FLOAT NOT NULL,
    latitude FLOAT NOT NULL,
    altitude INT,
    timestampms TIMESTAMP NOT NULL,
    PRIMARY KEY (userid, timestampms),
    CONSTRAINT ACTIVE_USER FOREIGN KEY (userid) REFERENCES users(userid)
    ON DELETE CASCADE ON UPDATE CASCADE
);

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
$$ LANGUAGE 'plpgsql';

-- SELECT * FROM calc_eco_score_rank();

CREATE OR REPLACE FUNCTION geog_dist()
RETURNS trigger AS $$
BEGIN
   IF (SELECT (6371 * acos(cos(radians(38.230462)) * 
   cos(radians(NEW.latitude) ) * cos(radians(NEW.longitude) - 
   radians(21.753150)) + sin(radians(38.230462)) * 
   sin(radians(NEW.latitude))))) >= 10 THEN
      RETURN NULL;
   END IF;
   RETURN NEW;
END;
$$  LANGUAGE 'plpgsql';

DROP TRIGGER IF EXISTS coords_in_radius ON events;
CREATE TRIGGER coords_in_radius
BEFORE INSERT ON events
FOR EACH ROW EXECUTE PROCEDURE geog_dist();

--------insert users------------
INSERT INTO users 
VALUES ('yHHFxIjbHDoylTLjMym6PA==', 'Anna', 'Mayaki', 'anna', 'anna@me.com', 
    '$2y$10$yFzCY63mov8LZ0LQR70pyeEZOusLl.u/SoDMCcju7p9CuwrvU2v/q','user');
INSERT INTO users 
VALUES ('URwNCGmjCtbyhSedWzA/4Or0Giv00Mzx28aX1b+LHUk=', 'Κλέλια', 'Λ', 'klelia', 
'klelia@icloud.com', 
    '$2y$10$MtJUVehnX9uYN8Tp8VZ94.yUEhCxHhShLgxuJ50et6y2ES2KuRweK','admin');
-- ('klelia','klelia1998','admin');


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