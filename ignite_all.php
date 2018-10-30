<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set("log_errors", 1);
ini_set("error_log", "./txt/logs/php_errors.txt");

require_once("./config.php");
require_once("./Libs/Functions/helpers.php");

$exclude = [
    'Rutube.php',
    'Youtube.php',
];


$smth_else_to_init = [
    [
        'path_to_file' => './se/send_to_api.php',
        'descr' => 'Send parsed video info to api',
        'args' => []
    ],
];

$smth_else_to_init = [];

array_map(function($file_name){
    pclose(popen('start "'.$file_name.'" php start.php '.$file_name, 'r'));
}, getAllParsers($config['parsers_dir'], $exclude));

array_map(function($init_data){
    pclose(popen('start "'.$init_data['descr'].'" php '.$init_data['path_to_file'].' '.implode(' ', $init_data['args']), 'r'));
}, $smth_else_to_init);
