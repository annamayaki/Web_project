DROP DATABASE IF EXISTS supertrouper;
CREATE DATABASE supertrouper (
    WITH 
    OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'Greek_Greece.1253'
    LC_CTYPE = 'Greek_Greece.1253'
    TABLESPACE = pg_default
    CONNECTION LIMIT = -1
);

\c supertrouper;

CREATE EXTENSION postgis;

CREATE TYPE site_user_type AS ENUM('user', 'admin');

CREATE TABLE IF NOT EXISTS users (
    userId VARCHAR UNIQUE NOT NULL PRIMARY KEY,
    firstname VARCHAR NOT NULL,
    lastname VARCHAR NOT NULL,
    username VARCHAR NOT NULL,
    email VARCHAR NOT NULL,
    password VARCHAR NOT NULL,
    user_type site_user_type DEFAULT 'user' NOT NULL
);

-- CREATE TYPE detected_activity_type AS ENUM('IN_VEHICLE', 'ON_BICYCLE',
-- 'ON_FOOT', 'RUNNING', 'STILL', 'TILTING', 'UNKNOWN', 'WALKING');

DROP TABLE IF EXISTS events;
CREATE TABLE IF NOT EXISTS events (
    userId VARCHAR NOT NULL,
    -- username VARCHAR NOT NULL,
    heading INT,
    activity_type VARCHAR,
    activity_confidence INT,
    activity_timestampMs TIMESTAMP,
    verticalAccuracy INT,
    velocity INT,
    accuracy INT,
    longitude FLOAT NOT NULL,
    latitude FLOAT NOT NULL,
    altitude INT,
    timestampMs TIMESTAMP NOT NULL,
    PRIMARY KEY (userId, timestampMs),
    CONSTRAINT ACTIVE_USER FOREIGN KEY (userId) REFERENCES users(userId)
    ON DELETE CASCADE ON UPDATE CASCADE
);

--------insert users------------
insert into users (username,password,user_type) 
values ('anna','anna1234','user'),
('klelia','klelia1998','admin');



---My precious-----

DROP TABLE IF EXISTS events;
CREATE TABLE IF NOT EXISTS events (
    userId VARCHAR NOT NULL,
    -- username VARCHAR NOT NULL,
    activity_type VARCHAR,
    coordinates GEOGRAPHY(POINT),
    timestampMs TIMESTAMP,
    PRIMARY KEY (username, timestampMs),
    CONSTRAINT ACTIVE_USER FOREIGN KEY (userId) REFERENCES users(userId)
    ON DELETE CASCADE ON UPDATE CASCADE
);

select username, ST_X(coordinates::geometry) as longtidute,ST_Y(coordinates::geometry) as latt from events;
------end my precious-------

ALTER TABLE users 
    ADD COLUMN userId VARCHAR UNIQUE,
    ADD COLUMN firstname VARCHAR NOT NULL DEFAULT 'fname',
    ADD COLUMN lastname VARCHAR NOT NULL DEFAULT 'lname',
    ADD COLUMN email VARCHAR NOT NULL  DEFAULT 'mail';

ALTER TABLE events 
    ADD COLUMN userId VARCHAR UNIQUE;

UPDATE users SET 
    userId = 'WuROtp3cuC1XpCGd4/8yv0kNzMy33Mt54SPwmBB036w=',
    email = 'klelia@icloud.com',
    firstname = 'Κλέλια',
    lastname = 'Λ'
WHERE username = 'klelia';

