CREATE DATABASE yeticave character set UTF8 collate utf8_general_ci;
USE yeticave;
CREATE TABLE category
(
  id int NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  symbol_code varchar(50) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE user
(
  id int NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  password varchar(50) NOT NULL,
  email varchar(50) NOT NULL,
  contact varchar(255) NOT NULL,
  avatar varchar(50),
  reg_date datetime DEFAULT NOW(),
  PRIMARY KEY (id),
  INDEX (password),
  UNIQUE INDEX (name, email)
);

CREATE TABLE lot
(
  id int NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  description varchar(200) NOT NULL,
  id_category int NOT NULL,
  image varchar(50) NOT NULL,
  start_price int NOT NULL,
  bid_step int NOT NULL,
  creation_date datetime DEFAULT NOW(),
  end_date datetime NOT NULL,
  id_creator int NOT NULL,
  id_winner int,
  PRIMARY KEY (id),
  FOREIGN KEY (id_creator) REFERENCES user(id),
  FOREIGN KEY (id_category) REFERENCES category(id),
  INDEX (end_date, id_creator, id_winner)
);

CREATE TABLE bid
(
  id int NOT NULL AUTO_INCREMENT,
  amount int NOT NULL,
  creation_date datetime DEFAULT NOW(),
  id_user int NOT NULL,
  id_lot int NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_user) REFERENCES user(id),
  FOREIGN KEY (id_lot) REFERENCES lot(id),
  INDEX (id_user, id_lot)
)
