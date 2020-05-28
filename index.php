<?php
require_once('init.php');
require_once('helpers.php');
$is_auth = rand(0, 1);
$text_max_symbols_number = 300;//максимальная длина текстового содержимого карточки поста
$user_name = 'Denis'; // укажите здесь ваше имя
$page_title = 'Readme';
$posts=[
    [
        'post_header'=>'Цитата',
        'type'=>'post-quote',
        'content'=>'Мы в жизни любим только раз, а после ищем лишь похожих',
        'user_name'=>'Лариса',
        'avatar'=>'userpic-larisa-small.jpg'
    ],
    [
        'post_header'=>'Игра престолов',
        'type'=>'post-text',
        'content'=>'Главная задача этой функции урезать текст по отдельным словам. Это значит, что строка с обрезанным текстом не должна заканчиваться частью слова. Чтобы соблюсти это требование вам вначале потребуется разбить весь текст на отдельные слова (по пробелам).
                    Затем в цикле вы последовательно считаете длину каждого слова и останавливаете цикл, когда суммарная длина символов в посчитанных словах начинает превышать ограничение.
                    Теперь нужно сложить отдельные слова обратно в строку и добавить в конец этой строки знак многоточия.',
        'user_name'=>'Владик',
        'avatar'=>'userpic.jpg'
    ],
    [
        'post_header'=>'Наконец, обработал фотки!',
        'type'=>'post-photo',
        'content'=>'rock-medium.jpg',
        'user_name'=>'Виктор',
        'avatar'=>'	userpic-mark.jpg'
    ],
    [
        'post_header'=>'Моя мечта',
        'type'=>'post-photo',
        'content'=>'coast-medium.jpg',
        'user_name'=>'Лариса',
        'avatar'=>'userpic-larisa-small.jpg'
    ],
    [
        'post_header'=>'Лучшие курсы',
        'type'=>'post-link',
        'content'=>"<script>alert('Fuck')</script>",   //xss проверка
        'user_name'=>'Владик',
        'avatar'=>'userpic.jpg'
    ],
];
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
 * @author
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
$page_content = include_template('main.php',['posts' => $posts,
    'text_max_symbols_number' => $text_max_symbols_number]);
$layout_content = include_template('layout.php',['user_name' => $user_name,'page_title' => $page_title,
    'is_auth' => $is_auth,'page_content' => $page_content]);
print($layout_content);
?>

