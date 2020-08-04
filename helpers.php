<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else {
                if (is_string($value)) {
                    $type = 's';
                } else {
                    if (is_double($value)) {
                        $type = 'd';
                    }
                }
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Проверяет, что переданная ссылка ведет на публично доступное видео с youtube
 * @param string $youtube_url Ссылка на youtube видео
 * @return bool
 */
function check_youtube_url($youtube_url)
{
    $res = false;
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $api_data = ['id' => $id, 'part' => 'id,status', 'key' => 'AIzaSyBN-AXBnCPxO3HJfZZdZEHMybVfIgt16PQ'];
        $url = "https://www.googleapis.com/youtube/v3/videos?" . http_build_query($api_data);

        $resp = file_get_contents($url);

        if ($resp && $json = json_decode($resp, true)) {
            $res = $json['pageInfo']['totalResults'] > 0 && $json['items'][0]['status']['privacyStatus'] == 'public';
        }
    }

    return $res;
}

/**
 * Возвращает код iframe для вставки youtube видео на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_video($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = "https://www.youtube.com/embed/" . $id;
        $res = '<iframe width="760" height="400" src="' . $src . '" frameborder="0"></iframe>';
    }

    return $res;
}

/**
 * Возвращает img-тег с обложкой видео для вставки на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_cover($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = sprintf("https://img.youtube.com/vi/%s/mqdefault.jpg", $id);
        $res = '<img alt="youtube cover" width="320" height="120" src="' . $src . '" />';
    }

    return $res;
}

/**
 * Извлекает из ссылки на youtube видео его уникальный ID
 * @param string $youtube_url Ссылка на youtube видео
 * @return array
 */
function extract_youtube_id($youtube_url)
{
    $id = false;

    $parts = parse_url($youtube_url);

    if ($parts) {
        if ($parts['path'] == '/watch') {
            parse_str($parts['query'], $vars);
            $id = $vars['v'] ?? null;
        } else {
            if ($parts['host'] == 'youtu.be') {
                $id = substr($parts['path'], 1);
            }
        }
    }

    return $id;
}

/**
 * @param $index
 * @return false|string
 */
function generate_random_date($index)
{
    $deltas = [['minutes' => 59], ['hours' => 23], ['days' => 6], ['weeks' => 4], ['months' => 11]];
    $dcnt = count($deltas);

    if ($index < 0) {
        $index = 0;
    }

    if ($index >= $dcnt) {
        $index = $dcnt - 1;
    }

    $delta = $deltas[$index];
    $timeval = rand(1, current($delta));
    $timename = key($delta);

    $ts = strtotime("$timeval $timename ago");
    $dt = date('Y-m-d H:i:s', $ts);

    return $dt;
}
/**
 * Возвращаем ассоциативный массив из SQL запроса
 * @param mysqli $connection подключение к БД
 * @param string $sql_query sql запрос
 * @return array
 */
function get_array_from_sql_query($connection, $sql_query){
    $query = mysqli_query($connection, $sql_query);
    if (! $query){
        exit(mysqli_error($query));
    }
    return mysqli_fetch_all($query,MYSQLI_ASSOC);
}

/**
 * Подсчет разницы времени между публикацией и текущей датой
 *
 * @param string $post_time - дата публикации
 *
 * @author Tumanenkov Denis, skyline400@gmail.com
 *
 * @return string
 */
function time_delta($post_time){
    $end_ts = strtotime($post_time);
    $ts_diff = time() - $end_ts;

    if ($ts_diff <= SEC_IN_MINUTE) { /**менее 60 сек */
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

/**
 * функция для обрезки длинного текста
 *
 * @param string $str  входная строка
 * @param int $symbols_number максимальная длина строки
 * @return string
 * @author Tumanenkov Denis, skyline400@gmail.com
 *
 */
function trim_text($str,$symbols_number = 300){ //
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
 * Добавляем параметры GET в url страницы
 * @param string $category_id категория элементов
 * @param string $page_url путь текущей страницы
 * @param string $sort_value тип сортировки
 * @param string $sorting по возрастанию/убыванию
 * @return string  URL-кодированная строка запроса
 */
function create_url($category_id, $sort_value, $sorting, string $page_url = "index.php"){
    $parameters = $_GET;
    $parameters['content_type_id'] = $category_id;
    $parameters['sort_value'] = $sort_value;
    $parameters['sorting'] = $sorting;
    $query = http_build_query($parameters);
    $url = "/". $page_url. "?". $query;
    return $url;
}

/**
 * Подключение к БД
 * @param string $host Хост
 * @param string $user Имя пользователя
 * @param string $password Пароль
 * @param string $database Имя БД
 *
 * @return mysqli
 */
function database_connecting($host, $user, $password, $database)
{
    $con = mysqli_connect("localhost", "root", "root", "readme");
    if (!$con) {
        die("Error!" . mysqli_connect_error());
    }
    mysqli_set_charset($con, "utf8");
    return $con;
}

