<?php

class Scrapper
{

    private static $use_proxy = 0;
    private static $force = 0;

    public static function use_proxy()
    {
        self::$use_proxy = 1;
    }

    public static function force()
    {
        self::$force = 1;
    }

    public static function get($url)
    {
        $attempts = self::$force == 1
            ? Config::get('curl')['force_attempts']
            : 1;

        for($i=0; $i<=$attempts; $i++)
        {
            try{
                $curl_result = Curl::get($url, self::getUserAgent(), self::getProxy());
                break;
            }catch (Exception $e){
                log::d($e->getMessage());
                if($i == $attempts)
                {
                    log::flog($url." - get failed: attempts end");
                    return false;
                }
                sleep(Config::get('curl')['sleep']['after_bad_request']);
                continue;
            }
        } // for
        // log::flog($curl_result);
        return str_get_html($curl_result);
    } // func

    public static function getUserAgent()
    {
        $user_agents = Config::get('curl')['user_agents'];
        return $user_agents[rand(0, count($user_agents)-1)];
    }

    public static function getProxy()
    {
        return null;
    }

} // class