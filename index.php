<?php
require_once('init.php');
require_once('helpers.php');
require_once('sql-queries.php');
require_once ('sql-queries.php');
$is_auth = rand(0, 1);
$text_max_symbols_number = 300;//максимальная длина текстового содержимого карточки поста
$user_name = 'Denis'; // укажите здесь ваше имя
$page_title = 'Readme';
$con = database_connecting('localhost',$user_name,'',$page_title);

$content_types_sql_result = get_array_from_sql_query($con, $sql_content_types);

/* Список  постов  */
$posts_list = get_array_from_sql_query($con, $sql_popular_posts_list);

/* Строка запроса: текущий активный тип контента, сортировки и порядок сортировки*/
$get_active_content_type = filter_input(INPUT_GET,'content-type');
$get_active_sorting_type = filter_input(INPUT_GET,'sorting-type');
$get_sorting_order = filter_input(INPUT_GET,'sorting_order');

/* параметры запросов*/
$query_params=[];
if($get_active_content_type){
    $query_params['content_type_id'] = $get_active_content_type ?? 'all';
    $query_params['sort_value'] = $get_active_sorting_type ?? 'views';
    $query_params['sort_order'] = $get_sorting_order ?? 'DESC';
}

/* параметры сортировки */

$page_content = include_template('main.php',
    ['posts_list' => $posts_list,
    'text_max_symbols_number' => $text_max_symbols_number,
    'content_types_sql_result' => $content_types_sql_result,
    'content-type' => $get_active_content_type,
    'sorting-type' => $get_active_sorting_type,
    'sorting_order' => $get_sorting_order]);

$layout_content = include_template('layout.php',
    ['user_name' => $user_name,
    'page_title' => $page_title,
    'is_auth' => $is_auth,
    'page_content' => $page_content]);
print($layout_content);


