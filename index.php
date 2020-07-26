<?php
require_once('init.php');
require_once('helpers.php');
require_once('sql-queries.php');
require_once ('sql-queries.php');
$is_auth = rand(0, 1);
$text_max_symbols_number = 300;//максимальная длина текстового содержимого карточки поста
$user_name = 'root'; // укажите здесь ваше имя
$page_title = 'Readme';
$con = database_connecting('localhost',$user_name,'root',$page_title);

/* Список категорий постов */
$content_types_sql_result = content_types($con);

/* Строка запроса: текущий активный тип контента, сортировки и порядок сортировки*/
$get_active_content_type = filter_input(INPUT_GET,'content_type_id') ?? 'all';
$get_active_sorting_type = filter_input(INPUT_GET,'sort_value') ?? 'views';
$get_sorting_order = filter_input(INPUT_GET,'sort_order') ?? 'DESC';

/* параметры запросов*/
$query_params=[];
if($get_active_content_type){
    $query_params['content_type_id'] = $get_active_content_type;
    $query_params['sort_value'] = $get_active_sorting_type;
    $query_params['sort_order'] = $get_sorting_order;
}
$posts_list = popular_post_list($con, $get_active_sorting_type, $get_sorting_order, 6);
/* Список популярных  постов  */
if (isset($_GET['content_type_id'])) {
    if($_GET['content_type_id'] === 'all') {
        $posts_list = popular_post_list($con, $get_active_sorting_type, $get_sorting_order);
    }
    else{
        $posts_list = popular_post_category_sorting($con, $get_active_content_type, $get_active_sorting_type, $get_sorting_order);
    }
}
/* параметры сортировки */

$page_content = include_template('main.php',
    ['posts_list' => $posts_list,
    'text_max_symbols_number' => $text_max_symbols_number,
    'content_types_sql_result' => $content_types_sql_result,
    'query_params' => $query_params]);

$layout_content = include_template('layout.php',
    ['user_name' => $user_name,
    'page_title' => $page_title,
    'is_auth' => $is_auth,
    'page_content' => $page_content]);
print($layout_content);


