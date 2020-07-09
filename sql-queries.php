<?php
/* Работа с БД  */
$sql_posts_list = "SELECT p.date_of_publication, p.header, p.content, p.quote_author, p.image, p.video, p.link,
p.views, p.content_type_id, u.login, u.avatar, c.content_name, c.content_icon_name
FROM posts p
INNER JOIN users u ON p.author_id = u.id
INNER JOIN content_types c ON p.content_type_id = c.id
ORDER BY p.views DESC LIMIT 6; ";

$sql_content_types = "SELECT id, content_name, content_icon_name, i.icon_name, i.width, i.height
FROM content_types
INNER JOIN icon_sizes_for_content_types i ON i.icon_size_id=content_types.id; ";
/* Типы постов  */
