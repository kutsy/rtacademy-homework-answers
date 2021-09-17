-- 5.2.1

CREATE TABLE users (
    id              int unsigned                        NOT NULL AUTO_INCREMENT,            -- ID (унікальний ідентифікатор)
    lastname        varchar(64)                         NOT NULL,                           -- Прізвище
    firstname       varchar(64)                         NOT NULL,                           -- Імʼя
    middlename      varchar(64)                         NOT NULL,                           -- По батькові
    date_of_birth   date                                NOT NULL,                           -- Дата народження
    email           varchar(128)                        NOT NULL,                           -- Email
    telephone       varchar(16)                         NOT NULL,                           -- Телефон
    gender          enum('male','female','other')       NOT NULL,                           -- Стать
    PRIMARY KEY ( id ),
    UNIQUE ( email ),
    CHECK ( length(lastname) > 1 ),
    CHECK ( length(firstname) > 1 ),
    CHECK ( date_of_birth >= '1900-01-01' ),
    CHECK ( length(email) > 5 ),
    CHECK ( length(telephone) > 1 )
);

-- 5.2.2
CREATE TABLE users2 (
    id              int unsigned                        NOT NULL,                           -- ID (унікальний ідентифікатор)
    lastname        varchar(64)                         NOT NULL,                           -- Прізвище
    firstname       varchar(64)                         NOT NULL,                           -- Імʼя
    middlename      varchar(64)                         NOT NULL,                           -- По батькові
    date_of_birth   date                                NOT NULL,                           -- Дата народження
    email           varchar(128)                        NOT NULL,                           -- Email
    telephone       varchar(16)                         NOT NULL,                           -- Телефон
    gender          enum('male','female','other')       NOT NULL                            -- Стать
);

ALTER TABLE users2 CHANGE id id int unsigned AUTO_INCREMENT;
ALTER TABLE users2 ADD CONSTRAINT PRIMARY KEY ( id );
ALTER TABLE users2 ADD CONSTRAINT UNIQUE ( email );
ALTER TABLE users2 ADD CONSTRAINT CHECK ( length(lastname) > 1 );
ALTER TABLE users2 ADD CONSTRAINT CHECK ( length(firstname) > 1 );
ALTER TABLE users2 ADD CONSTRAINT CHECK ( date_of_birth >= '1900-01-01' );
ALTER TABLE users2 ADD CONSTRAINT CHECK ( length(email) > 5 );
ALTER TABLE users2 ADD CONSTRAINT CHECK ( length(telephone) > 1 );
