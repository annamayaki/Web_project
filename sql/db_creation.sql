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

DROP TABLE IF EXISTS events;
CREATE TABLE IF NOT EXISTS events (
    userid VARCHAR NOT NULL,
    heading INT,
    activity_type VARCHAR,
    activity_confidence INT,
    activity_timestampms BIGINT,
    verticalaccuracy INT,
    velocity INT,
    accuracy INT,
    longitude FLOAT NOT NULL,
    latitude FLOAT NOT NULL,
    altitude INT,
    timestampms BIGINT NOT NULL,
    timestampunix TIMESTAMP NOT NULL,
    PRIMARY KEY (userid, timestampunix),
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
    WHERE age(now(), timestampunix) < interval '1 month'
    AND (activity_type = 'ON_BICYCLE' OR activity_type = 'ON_FOOT' 
    OR activity_type = 'RUNNING')
    GROUP BY userid) eco_activs
    INNER JOIN 
    (SELECT userid, count(*) as denom
    FROM events
    WHERE age(now(), timestampunix) < interval '1 month'
    GROUP BY userid) all_activs
    ON all_activs.userid = eco_activs.userid
    INNER JOIN users 
    ON all_activs.userid = users.userid
    ORDER BY score DESC
    LIMIT 3;
END;
$$ LANGUAGE 'plpgsql';

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

-------- Admin ------------
INSERT INTO users VALUES 
('gjWZqr6e331PZno/2X0Qsu4Lx3Iom6Bw/HMemS4ec+k=', 'Mr', 'Boss', 
'theBoss', 'theBoss@supertrouper.co',
'$2y$10$ThuqNCD6T6RzcEwqAsgzZuvYSZpZImz910aO5vtucrYrtfAq7PgsS','admin');