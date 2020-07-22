<?php
require_once "helpers.php";
require_once "sql-queries.php";
$post_id = isset($_GET['id']) ? $_GET['id'] : null;
$link = database_connecting('localhost',$user_name,'',$page_title);

$post = get_sql_post($link,$post_id);

if(!$post) {
    header("HTTP/1.0 404 Not Found");
    $error_msg = 'Не удалось выполнить запрос: ' . mysqli_error($link);
    die($error_msg);
}
