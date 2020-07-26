<?php
/* Работа с БД  */

/* Список популярных постов  */
/**
 * Список популярных постов с сортировкой
 * @param mysqli $link
 * @param string $sort_value тип сортировки
 * @param string $sorting по возрастанию/убыванию
 * @param int $limit максимально количество на странице
 * @return array|null
 */
function popular_post_list($link, $sort_value = 'views', $sorting = 'DESC', $limit = 6)
{
    $sql = "SELECT p.post_id, p.date_of_publication, p.header, p.content, p.quote_author, p.image, p.video, p.link,
    p.views, p.content_type_id, u.login, u.avatar, c.content_name, c.content_icon_name, IFNULL(l.likes_count, 0) AS likes_count, IFNULL(com.comments_count, 0) AS comments_count
    FROM posts p
    INNER JOIN users u ON p.author_id = u.id
    INNER JOIN content_types c ON p.content_type_id = c.id
    LEFT JOIN (SELECT l.post_id, COUNT(*) as likes_count FROM likes l GROUP BY l.post_id) AS l ON l.post_id = p.post_id
    LEFT JOIN (SELECT com.post_id, COUNT(*) as comments_count FROM comments com GROUP BY com.post_id) AS com ON com.post_id=p.post_id
    ORDER BY $sort_value DESC LIMIT $limit ";
    $result = get_array_from_sql_query($link, $sql);
    return empty($result) ? NULL : $result;
        /*ORDER BY $sort_value $sorting LIMIT $limit "; */
}
/* Список популярных постов  */
/**
 * Список популярных постов с сортировкой по типу контента
 * @param mysqli $link
 * @param string $content_type тип контента
 * @param string $sort_value тип сортировки
 * @param string $sorting по возрастанию/убыванию
 * @param int $limit максимально количество на странице
 * @return array|null
 */
function popular_post_category_sorting($link, $content_type, $sort_value = 'views', $sorting = 'DESC')
{
    $sql = "SELECT p.post_id, p.date_of_publication, p.header, p.content, p.quote_author, p.image, p.video, p.link,
    p.views, p.content_type_id, u.login, u.avatar, c.content_name, c.content_icon_name, IFNULL(l.likes_count, 0) AS likes_count, IFNULL(com.comments_count, 0) AS comments_count
    FROM posts p
    INNER JOIN users u ON p.author_id = u.id
    INNER JOIN content_types c ON p.content_type_id = c.id
    LEFT JOIN (SELECT l.post_id, COUNT(*) as likes_count FROM likes l GROUP BY l.post_id) AS l ON l.post_id = p.post_id
    LEFT JOIN (SELECT com.post_id, COUNT(*) as comments_count FROM comments com GROUP BY com.post_id) AS com ON com.post_id=p.post_id
    WHERE c.id = $content_type
    ORDER BY $sort_value $sorting";
    $result = get_array_from_sql_query($link, $sql);
    return empty($result) ? NULL : $result;
}

/* Список категорий постов */
/**
 * Список категорий постов
 * @param $link
 * @return array|null
 */
function content_types($link){
    $sql = "SELECT id, content_name, content_icon_name, i.icon_name, i.width, i.height
    FROM content_types
    INNER JOIN icon_sizes_for_content_types i ON i.icon_size_id = content_types.id ";
    $result = get_array_from_sql_query($link, $sql);
    return empty($result) ? NULL : $result;
}
/* Пост по id */
/**
 * Полная информация по посту  по его id
 * @param mysqli $link
 * @param string $post_id
 * @return array|null
 */
function get_post_id($link, $post_id)
{
    $sql = "SELECT p.*, ct.content_icon_name, u.avatar, u.login,
    IFNULL((SELECT COUNT(*) FROM likes l WHERE l.post_id = p.post_id), 0) AS likes_count,
    IFNULL((SELECT COUNT(*) FROM comments com WHERE com.post_id = p.post_id), 0) AS comments_count,
    IFNULL((SELECT COUNT(*) FROM subscriptions sub WHERE sub.subscription_user_id = p.author_id), 0) AS subscribers_count
    FROM posts p
    JOIN users u ON p.author_id = u.id
    JOIN content_types ct ON p.content_type_id = ct.id
    LEFT JOIN likes l ON l.post_id = p.post_id
    LEFT JOIN comments com ON com.post_id = p.post_id
    LEFT JOIN subscriptions sub ON sub.subscription_user_id = p.author_id
    WHERE p.post_id = $post_id
    GROUP BY l.post_id";
    $result = get_array_from_sql_query($link, $sql);
    return empty($result)? NULL : $result;
}

/**
 * Список постов пользователя по id
 * @param mysqli $link
 * @param string $user_id идентификатор пользователя
 * @return array|null
 */
function get_user_posts($link, $user_id){
    $sql = "SELECT p.id
    FROM posts p
    WHERE user_id = $user_id";
    $result = get_array_from_sql_query($link, $sql);
    return empty($result) ? NULL : $result;
}


/* Пост
$sql_post = "SELECT p.post_id, p.date_of_publication, p.header, p.content, p.quote_author, p.image, p.video, p.link,
p.views, p.content_type_id, p.author_id, c.content_name, c.content_icon_name,
(SELECT COUNT(*) from likes WHERE p.post_id = likes.post_id) AS likes_count,
(SELECT COUNT(*) from comments WHERE p.post_id = comments.post_id) AS comments_count
FROM posts p
INNER JOIN content_types c ON p.content_type_id = c.id
";
*/
