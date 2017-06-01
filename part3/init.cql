DROP KEYSPACE IF EXISTS csproj;

CREATE KEYSPACE csproj
WITH REPLICATION = { 'class' : 'SimpleStrategy', 'replication_factor' : 1 };

USE csproj;

CREATE TABLE tagpol (
    tag text,
    data text,
    PRIMARY KEY(tag)
);

COPY tagpol (tag, data) FROM '/var/www/html/total_pol.csv' WITH DELIMITER =';';