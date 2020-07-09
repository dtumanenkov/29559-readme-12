<?php
require_once('init.php');
require_once('helpers.php');
require_once('sql-queries.php');
require_once ('sql-queries.php');
$is_auth = rand(0, 1);
$text_max_symbols_number = 300;//максимальная длина текстового содержимого карточки поста
$user_name = 'Denis'; // укажите здесь ваше имя
$page_title = 'Readme';


$content_types_sql_result = get_array_from_sql_query($con, $sql_content_types);

/* Список постов  */
$posts_list = get_array_from_sql_query($con, $sql_posts_list);

/* Строка запроса: текущий активный тип контента, сортировки и порядок сортировки*/
$get_active_content_type=filter_input(INPUT_GET,'content-type');
$get_active_sorting_type=filter_input(INPUT_GET,'sorting-type');
$get_sorting_order=filter_input(INPUT_GET,'sorting_order');


$page_content = include_template('main.php',
    ['posts_list' => $posts_list,
    'text_max_symbols_number' => $text_max_symbols_number,
    'content_types_sql_result' => $content_types_sql_result]);

$layout_content = include_template('layout.php',
    ['user_name' => $user_name,
    'page_title' => $page_title,
    'is_auth' => $is_auth,
    'page_content' => $page_content]);
print($layout_content);


