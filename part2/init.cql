DROP KEYSPACE IF EXISTS csproj;

CREATE KEYSPACE csproj
WITH REPLICATION = { 'class' : 'SimpleStrategy', 'replication_factor' : 1 };

USE csproj;

CREATE TABLE tagranks (
    tag text,
    cnt int,
    pol double,
    sub double,
    PRIMARY KEY(tag, cnt)
) WITH CLUSTERING ORDER BY (cnt DESC);

CREATE TABLE dailytagranks (
    day text,
    tag text,
    cnt int,
    pol double,
    sub double,
    PRIMARY KEY(day, tag, cnt)
) WITH CLUSTERING ORDER BY (cnt DESC);

CREATE TABLE dailytaglocs (
    day text,
    tag text,
    cnt int,
    loc text,
    PRIMARY KEY(text, tag, cnt)
) WITH CLUSTERING ORDER BY (cnt DESC);

COPY tagranks FROM '/home/jgome026/tagranks.csv';
COPY dailytagranks FROM '/home/jgome026/dailytagranks.csv';
COPY dailytagocs FROM '/home/jgome026/dailytaglocs.csv';
