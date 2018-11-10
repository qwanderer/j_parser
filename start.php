<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set("log_errors", 1);
ini_set("error_log", "./php_errors.txt");

if(count($argv)<2){ die; }

include_once './Libs/Functions/common.php';
include_once './Libs/Functions/helpers.php';
include_once "./Libs/Config/Config.php";
include_once "./Libs/Logger/log.php";
include_once "./Libs/Scrapper/Scrapper.php";
include_once "./Libs/Scrapper/Scrapper_shdp.php";
include_once "./Libs/Curl/Curl.php";

include_once "./Core/Statistics.php";
include_once "./Core/Parser.php";

Config::getInstance(include_once './config.php');
$db_class_name = "DB_".Config::get('db')['drive'];
include_once "./Libs/DB/{$db_class_name}.php";


$required_folders = [
    './txt/logs/',
    './txt/temp/'
];

foreach($required_folders as $folder)
{
    if(!is_dir($folder)) mkdir($folder, 0777, true);
} // foreach


$file_name = str_replace('.php', '', $argv[1]);
include_once $config['parsers_dir']."/{$file_name}.php";

(new $file_name)->init(Statistics::getInstance(), new $db_class_name());
