DROP DATABASE IF EXISTS supertrouper;
CREATE DATABASE supertrouper
    WITH 
    OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'Greek_Greece.1253'
    LC_CTYPE = 'Greek_Greece.1253'
    TABLESPACE = pg_default
    CONNECTION LIMIT = -1;
\c supertrouper;

CREATE EXTENSION postgis;

CREATE TYPE site_user_type AS ENUM('user', 'admin');

CREATE TABLE IF NOT EXISTS users (
    username VARCHAR NOT NULL UNIQUE PRIMARY KEY,
    password VARCHAR NOT NULL,
    user_type site_user_type DEFAULT 'user' NOT NULL
);

CREATE TYPE detected_activity_type AS ENUM('IN_VEHICLE', 'ON_BICYCLE',
'ON_FOOT', 'RUNNING', 'STILL', 'TILTING', 'UNKNOWN', 'WALKING');

DROP TABLE IF EXISTS events;
CREATE TABLE IF NOT EXISTS events (
    username TEXT NOT NULL,
    activity_type TEXT,
    longitude FLOAT,
    latitude FLOAT,
    timestampMs TIMESTAMP,
    PRIMARY KEY (username, timestampMs),
    CONSTRAINT ACTIVE_USER FOREIGN KEY (username) REFERENCES users(username)
);

--------insert users------------
insert into users (username,password,user_type) 
values ('anna','anna1234','user'),
('klelia','klelia1998','admin');



---My precious-----

DROP TABLE IF EXISTS events;
CREATE TABLE IF NOT EXISTS events (
    username TEXT NOT NULL,
    activity_type TEXT,
    coordinates GEOGRAPHY(POINT),
    timestampMs TIMESTAMP,
    PRIMARY KEY (username, timestampMs),
    CONSTRAINT ACTIVE_USER FOREIGN KEY (username) REFERENCES users(username)
);

select username, ST_X(coordinates::geometry) as longtidute,ST_Y(coordinates::geometry) as latt from events;
------end my precious-------