DROP TABLE IF EXISTS "cars";

CREATE TABLE cars (
    id integer PRIMARY KEY,
    name character varying UNIQUE NOT NULL,
    price numeric
);

insert into cars values (1, 'nissan', 1.12), (10, 'kia', null);
-------
UPDATE cars SET price = price * 2 WHERE name = 'nissan';
UPDATE cars SET name = 'bmw' WHERE id = 10;
-----
CREATE TABLE cars (
    id SERIAL PRIMARY KEY,
    name character varying,
    price numeric DEFAULT 1.22
);
----
ALTER TABLE products
    ADD COLUMN amount integer,
    ALTER COLUMN name TYPE varchar,
    ALTER COLUMN name SET NOT NULL,
    ADD CONSTRAINT name_uniq UNIQUE (name),
    ALTER COLUMN price DROP DEFAULT;
------
CREATE SCHEMA custom;
CREATE SEQUENCE custom.serial;
-----
CREATE VIEW cars_without_price AS select id, name FROM cars;
----
