<?php
require_once('init.php');
require_once('helpers.php');
$is_auth = rand(0, 1);
$text_max_symbols_number = 300;//максимальная длина текстового содержимого карточки поста
$user_name = 'Denis'; // укажите здесь ваше имя
$page_title = 'Readme';

function trim_text($str,$symbols_number = 300){ //функция для обрезки длинного текста
    $trim_str = "";//переменная для работы со строкой
    if (strlen($str) < $symbols_number) {   //если длина меньше 300 -  просто возвращаем строку
        $trim_str = $str;
    } else {
        $str_array = explode(" ",$str); // разбиваем исходную строку на массив с разделителем пробелом
        $total_length = 0;// счетчик длины строки

        for ($i = 0; $i < count($str_array); $i++) { //пройдем по массиву подсчитывая длину каждого элемента
            $total_length = $total_length + strlen($str_array[$i]) + 1;//общая длина слов массива + 1 символ пробела в конце каждого слова

            if($total_length > $symbols_number) {//если выбиваемся за предел количества символов - значит текущее слово уже не влезет
                $trim_str = array_slice($str_array,0,$i); //обрезаем массив по i-тому элементу(не i-1 - т.к отсчет массива начинается с 0!)
                break; // прерываем цикл for
            }
        }
        $trim_str = implode(" ",$trim_str).'...'; //собираем обрезанный массив обратно в строку
    }
    return $trim_str;
}

/**
 * Подсчет разницы времени между публикацией и текущей датой
 *
 * @param $post_time - дата публикации
 *
 * @author Tumanenkov Denis, skyline400@gmail.com
 *
 * @return string
 */
function time_delta($post_time){
    $end_ts = strtotime($post_time);
    $ts_diff = time() - $end_ts;

    if ($ts_diff <= 60) { /**менее 60 сек */
        return 'менее минуты назад';

    } elseif ($ts_diff <= SEC_IN_HOUR && $ts_diff > 60) { /**менее 60 минут*/

        return floor($ts_diff / 60).get_noun_plural_form(floor($ts_diff / 60),
                ' минуту назад',' минуты назад',' минут назад');
    } elseif ($ts_diff <= SEC_IN_DAY && $ts_diff > SEC_IN_HOUR) { /**менее 24 часов но более 60 минут*/

        return floor($ts_diff / SEC_IN_HOUR).get_noun_plural_form(floor($ts_diff / SEC_IN_HOUR),
                ' час назад',' часа назад',' часов назад');
    } elseif ($ts_diff <= SEC_IN_WEEK && $ts_diff > SEC_IN_DAY) { /**менее 7 дней, но больше 24 часов*/

        return floor($ts_diff / SEC_IN_DAY).get_noun_plural_form(floor($ts_diff / SEC_IN_DAY),
                ' день назад',' дня назад',' дней назад');
    } elseif ($ts_diff <= SEC_IN_MONTH && $ts_diff > SEC_IN_WEEK) { /**менее 5 недель, но больше 7 дней*/

        return floor($ts_diff / SEC_IN_WEEK).get_noun_plural_form(floor($ts_diff / SEC_IN_WEEK),
                ' неделю назад',' недели назад',' недель назад');
    } else {
        return floor($ts_diff / SEC_IN_MONTH).get_noun_plural_form(floor($ts_diff / SEC_IN_MONTH),
                ' месяц назад',' месяца назад',' месяцев назад');
    }

}
/* Работа с БД  */

$sql_posts_list = "SELECT p.date_of_publication, p.header, p.content, p.quote_author, p.image, p.video, p.link,
            p.views, p.content_type_id, u.login, u.avatar, c.content_name, c.content_icon_name
            FROM posts p
	        INNER JOIN users u ON p.author_id = u.id
            INNER JOIN content_types c ON p.content_type_id = c.id
	        ORDER BY p.views DESC LIMIT 6; ";

$sql_content_types = "SELECT content_name, content_icon_name, i.icon_name, i.width, i.height
            FROM content_types
            INNER JOIN icon_sizes_for_content_types i ON i.icon_size_id=content_types.id; ";
/* Типы постов  */
$content_types_sql_result = get_array_from_sql_query($con, $sql_content_types);

/* Список постов  */
$posts_list = get_array_from_sql_query($con, $sql_posts_list);

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


