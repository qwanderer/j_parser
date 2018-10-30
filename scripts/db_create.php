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

$sql = 'CREATE DATABASE '.Config::get('db')['db_name'];
if (mysqli_query($link, $sql)) {
    echo "DB created: ".Config::get('db')['db_name'] . "\n";
} else {
    die('DB creation error: ' . mysqli_error($link) . "\n");
}



if (mysqli_select_db($link, Config::get('db')['db_name'])) {
    echo "DB ".Config::get('db')['db_name'] . " selected as default\n";
} else {
    die('DB selection error: ' . mysqli_error($link) . "\n");
}



$sql = '
CREATE TABLE p_content(
`id` INT(11) NOT NULL AUTO_INCREMENT ,
`created_at` VARCHAR(51) NOT NULL ,
`updated_at` VARCHAR(51) NOT NULL ,
`project` VARCHAR(51) NOT NULL ,
`storage_project_name` VARCHAR(51) NOT NULL ,
`title` VARCHAR(255) NOT NULL ,
`url` VARCHAR(255) NOT NULL ,
`metro` VARCHAR(255) NOT NULL ,
`category` VARCHAR(255) NOT NULL ,
`tag` VARCHAR(255) NOT NULL ,
`model` VARCHAR(255) NOT NULL ,
`video_name` VARCHAR(255) NOT NULL ,
`descr` TEXT NOT NULL ,
`status` INT(2) NOT NULL DEFAULT 0,
`tel` VARCHAR(255) NOT NULL ,
`user` VARCHAR(255) NOT NULL ,
`google_coords` VARCHAR(255) NOT NULL ,
`site_created_at` VARCHAR(255) NOT NULL ,
`img_links` TEXT NOT NULL,
PRIMARY KEY (`id`)) ENGINE = MyISAM CHARSET=utf8 COLLATE utf8_general_ci;
';
if (mysqli_query($link, $sql)) {
    echo "Table p_content created\n";
} else {
    die('Table p_content creation error: ' . mysqli_error($link) . "\n");
}
