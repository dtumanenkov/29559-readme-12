USE readme;

/* Добавляем пользователей */
INSERT INTO users
    (created_at,email,login,password,avatar)
VALUES ('2020-01-01 20:00', 'larisa@gmail.com', 'Лариса', 'strongpass', 'img/userpic-larisa-small.jpg'),
       ('2020-02-02 22:00', 'vladik@gmail.com', 'Владик', 'strongerpass', 'img/userpic.jpg'),
       ('2020-03-03 23:00', 'victor@gmail.com', 'Виктор', 'abc321', 'img/userpic-mark.jpg');
