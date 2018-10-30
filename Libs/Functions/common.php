<?php

// DUMP FUNCTIONS
function d($data=[], $var_dump_flag=0){
    $func = $var_dump_flag == 1 ? 'var_dump' : 'print_r';
    if(is_array($data) or is_object($data)){
        $curr_suff = (is_cli())?["\n", ""]:['<pre>', '</pre>'];
        echo $curr_suff[0].$func($data).$curr_suff[1];
    }else{
        echo (is_cli())?"\n":"<br>";$func($data);
    }
} // func

function dd($data=[], $var_dump_flag=0){d($data, $var_dump_flag);die;} // func

function dw($text, $var_dump_falg=0){
    d($text, $var_dump_falg);
    while(1){sleep(1);}
} // func

function et($data, $with_line_numbers=0){
    if(!is_array($data) or !count($data)>0){ return "Table Error - data is not an array"; }
    $arr_keys = getMultiArrayKeys($data);
    $return = "<table border='1' cellpadding='5'>";
    $ln = ($with_line_numbers==1)?"<th>ln</th>":"";
    $headers = "<tr>$ln<th>".implode("</th><th>", $arr_keys)."</th></tr>";
    $table_data = "";$loop=0;
    foreach($data as $row){
        $loop++;
        $ln=($with_line_numbers==1)?"<td>$loop</td>":"";
        $table_data .= "<tr>$ln";
        foreach($arr_keys as $key){
            if(!isset($row[$key])){ $table_data .= "<td></td>";continue; }
            $td_data = (is_array($row[$key])) ? print_r($row[$key],1):$row[$key];
            $table_data .= "<td>".$td_data."</td>";
        }
        $table_data .= "</tr>";
    } // foreach
    echo $return.$headers.$table_data."</table>";
} // func

function etd($data, $with_line_numbers=0){
    et($data, $with_line_numbers);die;
} // func

function getMultiArrayKeys($data) {
    $keys=[];
    foreach($data as $k => $v) {
        is_int($k) OR $keys[]=$k;
        if (is_array($v)){
            $keys = array_merge($keys, getMultiArrayKeys($v));
        }
    }
    return array_unique($keys);
} // func



function findInArr($data, $where){
    if($where and is_array($where) and count($where)>0){
        if($data and is_array($data) and count($data)>0){
            $result=[];
            foreach($data as $row){
                $find_flag = 1;
                foreach($where as $k=>$v){
                    if(!isset($row[$k])){ $find_flag=0; break; }
                    if(is_string($row[$k])){
                        if(strcasecmp(trim($row[$k]), trim($v))!=0){ $find_flag=0; break; }
                    }else{
                        if($row[$k]!=$v){ $find_flag=0; break; }
                    }
                } // foreach where
                if($find_flag == 1){ $result[] = $row; }
            } // foreach data
            return (count($result)>0)?$result:false;
        } // if isset result
    } // if isset where
    return false;
} // func



function is_cli(){
    return (PHP_SAPI==='cli' OR defined('STDIN'));
}




function rrmdir($src) {
    $dir = opendir($src);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            $full = $src . '/' . $file;
            if ( is_dir($full) ) {
                rrmdir($full);
            }
            else {
                unlink($full);
            }
        }
    }
    closedir($dir);
    rmdir($src);
}

