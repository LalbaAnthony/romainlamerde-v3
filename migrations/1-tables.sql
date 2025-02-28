DROP TABLE IF EXISTS sentence;
DROP TABLE IF EXISTS quote_category;
DROP TABLE IF EXISTS quote;
DROP TABLE IF EXISTS category;
DROP TABLE IF EXISTS drug;
DROP TABLE IF EXISTS place;
DROP TABLE IF EXISTS user;

#------------------------------------------------------------
# Table: user
#------------------------------------------------------------

DROP TABLE IF EXISTS user;
CREATE TABLE user(
        id INT AUTO_INCREMENT NOT NULL UNIQUE,
        name VARCHAR (50) NOT NULL,
        color VARCHAR (7) NOT NULL UNIQUE DEFAULT '#000000',
        birthdate DATE,
        token VARCHAR (500),
        password VARCHAR (150) NOT NULL,
        last_login DATETIME,
        is_admin BOOLEAN NOT NULL DEFAULT 0,
        is_deleted BOOLEAN NOT NULL DEFAULT 0,
        updated_at DATETIME,
        created_at DATETIME NOT NULL DEFAULT NOW(),
        CONSTRAINT user_PK PRIMARY KEY (id)
)ENGINE=InnoDB;

#------------------------------------------------------------
# Table: place
#------------------------------------------------------------

DROP TABLE IF EXISTS place;
CREATE TABLE place(
        id INT AUTO_INCREMENT NOT NULL UNIQUE,
        label VARCHAR (50),
        is_verified BOOLEAN NOT NULL DEFAULT 0,
        is_deleted BOOLEAN NOT NULL DEFAULT 0,
        updated_at DATETIME,
        created_at DATETIME NOT NULL DEFAULT NOW(),
        CONSTRAINT place_PK PRIMARY KEY (id)
)ENGINE=InnoDB;

#------------------------------------------------------------
# Table: drug
#------------------------------------------------------------

DROP TABLE IF EXISTS drug;
CREATE TABLE drug(
        id INT AUTO_INCREMENT NOT NULL UNIQUE,
        label VARCHAR (50) NOT NULL,
        to_censor BOOLEAN NOT NULL DEFAULT 0,
        is_verified BOOLEAN NOT NULL DEFAULT 0,
        is_deleted BOOLEAN NOT NULL DEFAULT 0,
        updated_at DATETIME,
        created_at DATETIME NOT NULL DEFAULT NOW(),
        CONSTRAINT drug_PK PRIMARY KEY (id)
)ENGINE=InnoDB;

#------------------------------------------------------------
# Table: category
#------------------------------------------------------------

DROP TABLE IF EXISTS category;
CREATE TABLE category(
        id INT AUTO_INCREMENT NOT NULL UNIQUE,
        label VARCHAR (50) NOT NULL,
        description VARCHAR (500),
        is_verified BOOLEAN NOT NULL DEFAULT 0,
        is_deleted BOOLEAN NOT NULL DEFAULT 0,
        updated_at DATETIME,
        created_at DATETIME NOT NULL DEFAULT NOW(),
        CONSTRAINT category_PK PRIMARY KEY (id)
)ENGINE=InnoDB;

#------------------------------------------------------------
# Table: quote
#------------------------------------------------------------

DROP TABLE IF EXISTS quote;
CREATE TABLE quote(
        id INT AUTO_INCREMENT NOT NULL UNIQUE,
        place_id INT,
        drug_id INT,
        user_id INT,
        date DATE,
        explanation VARCHAR (1000),
        is_highlighted BOOLEAN NOT NULL DEFAULT 0,
        is_verified BOOLEAN NOT NULL DEFAULT 0,
        is_deleted BOOLEAN NOT NULL DEFAULT 0,
        updated_at DATETIME,
        created_at DATETIME NOT NULL DEFAULT NOW(),
        CONSTRAINT quote_PK PRIMARY KEY (id),
        CONSTRAINT quote_user_FK FOREIGN KEY (user_id) REFERENCES user(id),
        CONSTRAINT quote_place_FK FOREIGN KEY (place_id) REFERENCES place(id),
        CONSTRAINT quote_drug_FK FOREIGN KEY (drug_id) REFERENCES drug(id)
)ENGINE=InnoDB;

#------------------------------------------------------------
# Table: quote_category
#------------------------------------------------------------

CREATE TABLE quote_category(
        id INT AUTO_INCREMENT NOT NULL UNIQUE,
        quote_id INT NOT NULL,
        category_id INT NOT NULL,
        is_deleted BOOLEAN NOT NULL DEFAULT 0,
        updated_at DATETIME,
        created_at DATETIME NOT NULL DEFAULT NOW(),
        CONSTRAINT quote_category_PK PRIMARY KEY (id),
        CONSTRAINT quote_category_quote_FK FOREIGN KEY (quote_id) REFERENCES quote(id) ON DELETE CASCADE,
        CONSTRAINT quote_category_category_FK FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE CASCADE
) ENGINE = InnoDB;

#------------------------------------------------------------
# Table: sentence
#------------------------------------------------------------

CREATE TABLE sentence(
        id INT AUTO_INCREMENT NOT NULL UNIQUE,
        quote_id INT NOT NULL,
        user_id INT,
        arrangement INT NOT NULL,
        content VARCHAR (1000) NOT NULL,
        is_deleted BOOLEAN NOT NULL DEFAULT 0,
        updated_at DATETIME,
        created_at DATETIME NOT NULL DEFAULT NOW(),
        CONSTRAINT sentence_PK PRIMARY KEY (id),
        CONSTRAINT sentence_quote_FK FOREIGN KEY (quote_id) REFERENCES quote(id) ON DELETE CASCADE,
        CONSTRAINT sentence_user_FK FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
) ENGINE = InnoDB;
