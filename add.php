<?php
require_once('init.php');
require_once('helpers.php');
require_once('sql-queries.php');
$is_auth = rand(0, 1);
$text_max_symbols_number = 300;//максимальная длина текстового содержимого карточки поста
$user_name = 'root'; // укажите здесь ваше имя
$page_title = 'Readme';
//Запросы на добавление в базу
$add_text_post_query = "INSERT into posts SET header = ?, content = ?, views = ?, author_id = ?, content_type_id = ?";
$add_link_post_query = "INSERT into posts SET header = ?, content = ?, views = ?, author_id = ?, content_type_id = ?";
$add_photo_post_query = "INSERT into posts SET header = ?, content = ?, views = ?, author_id = ?, content_type_id = ?, link = ?";
$add_quote_post_query = "INSERT into posts SET header = ?, content = ?, views = ?, author_id = ?, content_type_id = ?, quote_author = ?";
$add_video_post_query = "INSERT into posts SET header = ?, content = ?, views = ?, author_id = ?, content_type_id = ?, link = ?";
$add_tag_query = "INSERT into hashtag SET tag_name = ?";
$add_post_tag_query = "INSERT into posts_hashtags SET post_id = ?, hashtag_id = ?";
// массив с кодами ошибок
$field_errors_codes=[
    'heading' => 'Заголовок',
    'content' => 'Контент',
    'quote_author' => 'Автор цитаты',
    'tags' => 'теги',
    'link-url' => '',
    'video_url' => '',
    'photo_file' => '',
    'photo_url' => ''
];
//Работаем с тегами
$new_post_tags = array_unique(preg_split("/[\s|,|.]/ui" ,$_POST['tags']));
$read_tags_from_db = "SELECT * FROM hashtags WHERE hashtag in ('".implode(",'",$new_post_tags)."')";
$xxx = db_get_prepare_stmt($connection, $add_text_post_query, ['hi','every',100,1,2]);
mysqli_stmt_execute($xxx);
$content_types_sql_result = get_content_types($connection);
$form_type = 'text';
$page_content = include_template('../templates/post/adding-post.php', [
    'content_types' => $content_types_sql_result,
    'form_type' => $form_type
]);

$layout_content = include_template('layout.php', [
    'user_name' => $user_name,
    'page_title' => $page_title,
    'is_auth' => $is_auth,
    'page_content' => $page_content
]);

print($layout_content);
