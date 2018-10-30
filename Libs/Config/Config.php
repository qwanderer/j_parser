<?php


class Config
{

    private static $instance;
    private static $keyValueDictionary=[];

    public static function getInstance($config)
    {
        if(!(self::$instance instanceof self)){
            self::$instance = new self($config);
        }
        return self::$instance;
    } // func

    private function __construct($config){
        foreach($config as $k=>$v){
            self::propset($k,$v);
        }
    }

    public static function get($name)
    {
        if (!array_key_exists($name, static::$keyValueDictionary)){
            return null;
        }
        return static::$keyValueDictionary[$name];
    }

    public static function propset($name, $value)
    {
        if (array_key_exists($name, static::$keyValueDictionary)) {
            $prev = static::$keyValueDictionary[$name];
        } else {
            $prev = null;
        }
        static::$keyValueDictionary[$name] = $value;
        return $prev;
    }

}