<?php

function getAllParsers($path_to_folder_with_parsers, $exclude=[]){
    $data = array_diff(
        scandir($path_to_folder_with_parsers),
        array_merge(['.','..'], $exclude)
    );
    $data = array_map(function($file_name){
        return str_replace('.php', '', $file_name);
    }, $data);

    if(count($data)<1){error_log('no parsers available');die;}
    return $data;
}