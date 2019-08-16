-- SQLite
DROP TABLE IF EXISTS coffees;
DROP TABLE IF EXISTS ground_coffee;
DROP TABLE IF EXISTS cups;

CREATE TABLE coffees (id INTEGER PRIMARY KEY AUTOINCREMENT, name VARCHAR(255) NOT NULL, origin VARCHAR(255), fair_trade BOOLEAN, amount_left FLOAT NOT NULL);
CREATE TABLE ground_coffee (id INTEGER PRIMARY KEY AUTOINCREMENT, from_coffee_id INT NOT NULL UNIQUE, amount FLOAT NOT NULL);
CREATE TABLE cups (id INTEGER PRIMARY KEY AUTOINCREMENT, name VARCHAR(255) NOT NULL, size FLOAT NOT NULL, coffee_level FLOAT NOT NULL DEFAULT 0);

INSERT INTO coffees (name, origin, fair_trade, amount_left) VALUES
('Folgers', '?', 0, 10.5),
('French Roast', 'France', 1, 4.2),
('Italian Roast', 'Italy', 1, 1.0),
('Dunkin Donuts Blend', 'USA', 0, 12.1);

INSERT INTO cups (name, size, coffee_level) VALUES
('Whale cup', 12, 0),
('Skull mug', 12, 2.4),
('Big Bertha', 24, 0.5),
('Paper cup', 8, 0);