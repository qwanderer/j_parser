<?php

final class Statistics{

    private static $instance;

    public $curls=0;

    public static function getInstance()
    {
        if(!(self::$instance instanceof self)){
            self::$instance = new self();
        }
        return self::$instance;
    } // func

    public function save()
    {
        d(self::$instance->created_at);
    }

    private function __construct(){}

} // class