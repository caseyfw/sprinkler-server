CREATE TABLE sprinklers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR NOT NULL,
    state VARCHAR NOT NULL
);

insert into sprinklers (name, state) values('Back yard', 'off');
insert into sprinklers (name, state) values('Front yard', 'off');
insert into sprinklers (name, state) values('Front lawn', 'off');

