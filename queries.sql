USE readme;

/* Добавляем пользователей */
INSERT INTO users
    (created_at, email, login, password, avatar)
VALUES ('2020-01-01 20:00', 'larisa@gmail.com', 'Лариса', 'strongpass', 'userpic-larisa-small.jpg'),
       ('2020-02-02 22:00', 'vladik@gmail.com', 'Владик', 'strongerpass', 'userpic.jpg'),
       ('2020-03-03 23:00', 'victor@gmail.com', 'Виктор', 'abc321', 'userpic-mark.jpg');

/* Добавляем типы контента */
INSERT INTO content_types
    (content_name, content_icon_name)
VALUES ('текст', 'post-text'),
       ('цитата', 'post-quote'),
       ('картинка', 'post-picture'),
       ('видео', 'post-video'),
       ('ссылка', 'post-link');

/*Добаляем посты*/

/* цитата */
INSERT INTO posts
    (header, content, quote_author, views,author_id, content_type_id)
VALUES ('Цитата','Мы в жизни любим только раз, а после ищем лишь похожих','Лариса', 100, 1, 2);
/*текст*/
INSERT INTO posts
    (header, content, views, author_id, content_type_id)
VALUES ('Игра престолов','Главная задача этой функции урезать текст по отдельным словам. Это значит,
        что строка с обрезанным текстом не должна заканчиваться частью слова.
        Чтобы соблюсти это требование вам вначале потребуется разбить весь текст на отдельные слова (по пробелам).
        Затем в цикле вы последовательно считаете длину каждого слова и останавливаете цикл,
        когда суммарная длина символов в посчитанных словах начинает превышать ограничение.
        Теперь нужно сложить отдельные слова обратно в строку и добавить в конец этой строки знак многоточия.',
        200, 2, 1);
/*картинка*/
INSERT INTO posts
    (header, image, views, author_id, content_type_id)
VALUES ('Наконец, обработал фотки!','rock-medium.jpg', 200, 3, 3);
/*картинка*/
INSERT INTO posts
    (header, image, views, author_id, content_type_id)
VALUES ('Моя мечта','coast-medium.jpg', 300, 1, 3);
/*ссылка*/
INSERT INTO posts
    (header, link, views, author_id, content_type_id)
VALUES ('Лучшие курсы','www.htmlacademy.ru', 400, 2, 2);

/*Добаляем комментарии*/
INSERT INTO comments
    (comment, user_id, post_id)
VALUES ('Всем привет, кто в этом чате!', 1, 1),
       ('И тебе привет!', 2, 1),
       ('Аналогично!', 3 , 1),
       ('Отличное фото', 2, 3),
       ('Ссылка не открывается =(', 1, 5);

/*Запросы*/
/* получить список постов с сортировкой по популярности и вместе с именами авторов и типом контента*/
SELECT p.header, p.views, u.login, c.content_name  FROM posts p
    INNER JOIN users u ON p.author_id = u.id
    INNER JOIN content_types c ON p.content_type_id = c.id
    ORDER BY views DESC;
/* получить список постов для конкретного пользователя*/
SELECT login, header FROM posts
    INNER JOIN users ON author_id=id
    WHERE author_id = 2;
/* получить список комментариев для одного поста, в комментариях должен быть логин пользователя*/
SELECT c.comment, p.header, u.login FROM comments c
    INNER JOIN posts p ON c.post_id = p.post_id
    INNER JOIN users u ON user_id = id
    WHERE p.post_id = 1;

/* Добавить лайк к посту */
INSERT INTO likes SET user_id = 1, post_id = 4;

/* подписаться на пользователя */
INSERT INTO subscriptions SET subscription_user_id = 1, user_id = 2;
/*  */
