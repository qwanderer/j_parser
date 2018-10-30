<?php


class log
{

    private static $instance;

    public static function getInstance()
    {
        if(!(self::$instance instanceof self)){
            self::$instance = new self();
        }
        return self::$instance;
    } // func

    private function __construct(){}

    public static function d($data=[], $var_dump_flag=0)
    {
        $func = $var_dump_flag == 1 ? 'var_dump' : 'print_r';
        if(is_array($data) or is_object($data)){
            $curr_suff = (is_cli())?["", ""]:['<pre>', '</pre>'];
            echo $curr_suff[0].$func($data).$curr_suff[1];
        }else{
            echo (is_cli())?"\n":"<br>";$func($data);
        }
    }

    public static function dbd($data=[], $var_dump_flag=0)
    {
        if(Config::get('debug')==1){
            self::d($data, $var_dump_flag);
        }
    }

    public static function dd($data=[], $var_dump_flag=0)
    {
        self::d($data, $var_dump_flag);die;
    }

    public static function flog($data)
    {
        $data = is_array($data) ? print_r($data,1) : $data;
        file_put_contents(Config::get('txt')['logs']['flog'], date('d/m H:i:s')." => ".$data."\n", FILE_APPEND);
    }

    public static function dbflog($data)
    {
        if(Config::get('debug')==1){
            self::flog($data);
        }
    }

} // class