<?php
require_once('init.php');
require_once('helpers.php');
require_once('sql-queries.php');
$is_auth = rand(0, 1);
$text_max_symbols_number = 300;//максимальная длина текстового содержимого карточки поста
$user_name = 'root'; // укажите здесь ваше имя
$page_title = 'Readme';

$content_types_sql_result = get_content_types($connection);
$form_type = 'quote';
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
