<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set("log_errors", 1);
ini_set("error_log", "./txt/logs/php_errors.txt");


include_once '../Libs/Functions/common.php';
include_once '../Libs/Functions/helpers.php';
include_once "../Libs/Config/Config.php";
include_once "../Libs/Logger/log.php";
include_once "../Libs/Scrapper/Scrapper.php";
include_once "../Libs/Scrapper/Scrapper_shdp.php";
include_once "../Libs/Curl/Curl.php";
include_once "../Core/Statistics.php";
include_once "../Core/Parser.php";

Config::getInstance(include_once '../config.php');


$link = mysqli_connect(Config::get('db')['host'], Config::get('db')['user'], Config::get('db')['pass']);
if (!$link) {
    die('Не удалось подключиться к базе данных: ' . mysqli_connect_error());
}

$sql = 'DROP DATABASE '.Config::get('db')['db_name'];
if (mysqli_query($link, $sql)) {
    echo "DB deleted: ".Config::get('db')['db_name'];
} else {
    die('DB deletion error: ' . mysqli_error($link) . "\n");
}