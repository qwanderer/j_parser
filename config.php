<?php


$config['base_dir'] = __DIR__;
$config['parsers_dir'] = $config['base_dir'].'/projects';


$config['debug'] = 0;
$config['save_parsed_data_to_file'] = [
    'status'=>1,
    'folder' => $config['base_dir'].'/txt/temp/'
];

$config['txt'] = [
    'logs'=>[
        'flog'=>$config['base_dir'].'/txt/logs/flog.txt'
    ]
];


$config['curl'] = [
    'user_agents' => [
        'Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0',
        'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0',
        'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36'
    ],
    'proxy_list_path'=>$config['base_dir'].'/proxy_list.txt',
    'sleep' => [
        'after_bad_request' => 5
    ]
];


$config['db']=[
    'host'=>'localhost',
    'user'=>'root',
    'pass'=>'',
    'db_name'=>'jparser',
    'drive'=>'mysqli',
];

return $config;