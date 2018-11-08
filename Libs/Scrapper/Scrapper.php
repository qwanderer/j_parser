<?php

class Scrapper
{



    public static function get($url, $settings)
    {

        $attempts = self::getAttempts($settings);

        for($i=0; $i<=$attempts; $i++)
        {
            try{
                $curl_result = Curl::get($url, self::getUserAgent(), self::getProxy($settings));
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
        return (empty($curl_result))? false : str_get_html($curl_result);
    } // func


    public static function getAttempts($settings)
    {
        return (isset($settings['attempts']) && $settings['attempts']>0) ? $settings['attempts'] : 1;
    }


    public static function getProxy($settings)
    {
        if(!isset($settings['use_proxy']) || $settings['use_proxy']==false){ return null; }

        $proxy_list_file_path = Config::get('curl')['proxy_list_path'];
        if(!file_exists($proxy_list_file_path)){ return null; }

        $proxy_list = file($proxy_list_file_path);
        $proxy_list = array_map(function($row){
                return str_replace(["\r","\n"," "], "", $row);
            }, $proxy_list);

        $proxy = $proxy_list[rand(0, count($proxy_list)-1)];
        $proxy = str_replace("\t", ":", $proxy);
        if(Config::get('debug')==1) { log::d($proxy); }
        return $proxy;
    }


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