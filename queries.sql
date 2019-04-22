/* Добавление данных */

INSERT INTO category (name, symbol_code)
VALUES ('Доски и лыжи', 'boards'), ('Крепления', 'attachment'), ('Ботинки', 'boots'), ('Одежда', 'clothing'),
  ('Инструмент', 'tools'), ('Разное', 'other');

INSERT INTO user (name, password, email, contact, avatar)
VALUES ('user1', 'user1', 'user1@user.com', 'No contact available', 'user1.jpg'),
  ('user2', 'user2', 'user2@user.com', 'No contact available', 'user2.jpg');

INSERT INTO lot (name, description, id_category, image, start_price, bid_step, end_date, id_creator)
VALUES ('2014 Rossignol District Snowboard', 'Доски и лыжи', '1', 'img/lot-1.jpg', '10999', '100', '2019-04-30', '1'),
  ('DC Ply Mens 2016/2017 Snowboard', 'Доски и лыжи', '1', 'img/lot-2.jpg', '159999', '1000', '2019-04-30', '1'),
  ('Крепления Union Contact Pro 2015 года размер L/XL', 'Крепления', '2', 'img/lot-3.jpg', '8000', '100', '2019-04-30', '1'),
  ('Ботинки для сноуборда DC Mutiny Charocal', 'Ботинки', '3', 'img/lot-4.jpg', '10999', '100', '2019-04-30', '2'),
  ('Куртка для сноуборда DC Mutiny Charocal', 'Одежда', '4', 'img/lot-5.jpg', '7500', '100', '2019-04-30', '2'),
  ('Маска Oakley Canopy', 'Разное', '5', 'img/lot-6.jpg', '5400', '100', '2019-04-30', '2');

INSERT INTO bid (amount, id_user, id_lot)
VALUES ('11099', '2', '1'), ('160999', '2', '2');

/* Получение данных */

/* Получение всех категорий */
SELECT * FROM category;
/* Получение всех свежих открытых лотов с названиями категорий */
SELECT l.name, l.start_price, l.image, c.name AS category_name
  FROM lot AS l
  INNER JOIN category AS c ON l.id_category = c.id
  WHERE l.end_date > NOW()
  ORDER BY l.creation_date DESC;
/* Обновление лота по идентификатору*/
UPDATE lot SET name = 'new_name' WHERE id = '1';
/* Получить самые свежие ставки по идентификатору */
SELECT * FROM bid AS b
  INNER JOIN lot AS l ON b.id_lot = l.id
  WHERE l.id = 1
  ORDER BY b.creation_date DESC;
/* Получение лота по идентификатору*/
SELECT l.name, c.name AS category_name FROM lot as l
  INNER JOIN category AS c ON l.id_category = c.id
  WHERE l.id = '1';