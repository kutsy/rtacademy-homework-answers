
CREATE TABLE actors (
    id        int unsigned AUTO_INCREMENT, 
    lastname  varchar(64)  NOT NULL,
    firstname varchar(64)  NOT NULL,
    date_of_birth   date   NOT NULL,
    
    CHECK ( length(lastname) > 1 ),
    CHECK ( length(firstname) > 1 ),
    CHECK ( date_of_birth >= '1900-01-01' ),
    
    PRIMARY KEY (id)
);

CREATE TABLE friends (
    id        int unsigned AUTO_INCREMENT,
    lastname  varchar(64) not null,
    firstname varchar(64) not null,    
    actor_id  int unsigned NOT NULL,
    
    CHECK ( length(lastname) > 1 ),
    CHECK ( length(firstname) > 1 ),
    
    PRIMARY KEY (id),
    FOREIGN KEY (actor_id) REFERENCES actors(id)
);

INSERT INTO actors (lastname, firstname, date_of_birth) VALUES
('Jennifer', 'Aniston', '1969-02-11'),
('Courteney', 'Cox', '1964-05-15'),
('Lisa', 'Kudrow', '1963-06-30'),
('Matt', 'LeBlanc', '1967-06-25'),
('Matthew', 'Perry', '1969-08-19'),
('David', 'Schwimmer', '1966-11-02');

INSERT INTO friends (lastname, firstname, actor_id) VALUES
('Rachel', 'Green', 1),
('Monica', 'Geller',2),
('Phoebe', 'Buffay', 3),
('Joey', 'Tribbiani', 4),
('Chandler', 'Bing', 5),
('Dr. Ross', 'Geller', 6);


