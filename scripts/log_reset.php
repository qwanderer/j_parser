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

$checked_dir = '../txt/logs';
echo $checked_dir;
if(is_dir($checked_dir))
{
    rrmdir($checked_dir);
    mkdir($checked_dir, 0777);
}
echo " - done\n";


$checked_dir = '../txt/temp';
echo $checked_dir;
if(is_dir($checked_dir))
{
    rrmdir($checked_dir);
    mkdir($checked_dir, 0777);
}
echo " - done\n";