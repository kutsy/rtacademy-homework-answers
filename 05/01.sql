-- 5.1

CREATE TABLE users (
    id              int unsigned,                   -- ID (унікальний ідентифікатор)
    lastname        varchar(64),                    -- Прізвище
    firstname       varchar(64),                    -- Імʼя
    middlename      varchar(64),                    -- По батькові
    date_of_birth   date,                           -- Дата народження
    email           varchar(128),                   -- Email
    telephone       varchar(16),                    -- Телефон
    gender          enum('male','female','other')   -- Стать
);
