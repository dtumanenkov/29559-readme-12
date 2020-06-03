USE readme;

/* Добавляем пользователей */
INSERT INTO users
    (created_at,email,login,password,avatar)
VALUES ('2020-01-01 20:00', 'larisa@gmail.com', 'Лариса', 'strongpass', 'img/userpic-larisa-small.jpg'),
       ('2020-02-02 22:00', 'vladik@gmail.com', 'Владик', 'strongerpass', 'img/userpic.jpg'),
       ('2020-03-03 23:00', 'victor@gmail.com', 'Виктор', 'abc321', 'img/userpic-mark.jpg');

/* Добавляем типы контента */
INSERT INTO content_types
    (content_name,content_icon_name)
VALUES ('текст', 'post-text'),
       ('цитата', 'post-quote'),
       ('картинка', 'post-picture'),
       ('видео', 'post-video'),
       ('ссылка', 'post-link');

/*Добаляем посты*/
    /* цитата */
INSERT INTO posts
    (header,content,quote_author,views,author_id,content_type_id)
VALUES ('Цитата','Мы в жизни любим только раз, а после ищем лишь похожих','Лариса','100','1','2');
