<?php
require_once 'helpers.php';
date_default_timezone_set("Europe/Moscow"); // временная зон апо умолчанию
define("SEC_IN_MINUTE",60); // константы времени
define("SEC_IN_HOUR",3600);
define("SEC_IN_DAY",86400);
define("SEC_IN_WEEK",604800);
define("SEC_IN_MONTH",2419200);

$connection = database_connecting('localhost','root','root','readme');


