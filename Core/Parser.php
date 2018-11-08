<?php

abstract class Parser
{

    protected $paginator_link;
    protected $page_start = 1;
    protected $page_end = 15;
    protected $page_current;

    // sleep in microseconds (1sec = 1000000)
    protected $sleep = [];

    protected $total_finds = [
        'url',
        'title',
        'descr',
        'category',
        'tag',
        'model',
        'metro',
        'tel',
        'user',
        'google_coords',
        'site_created_at',
        'img_links',
    ];

    protected $scrapper_settings = [
        'use_proxy'=>0,
        'force'=>0,
        'attempts'=>5
    ];

    protected $parsed_data;

    protected $outer_shdp_string;
    protected $outer_finds;

    protected $stat;
    protected $db;


    protected function getPaginatorLinksGenerator()
    {
        for($i=$this->page_start; $i<=$this->page_end; $i++){
            $this->page_current = $i;
            yield str_replace("{@}", $i, $this->paginator_link);
        } // for
    }

    public function init(Statistics $stat, $db)
    {
        $this->stat = $stat;
        $this->db = $db;

        $this->beforeInit();
        $this->parse();
        $this->afterAll();
    } // func


    protected function parse()
    {
        log::d("Starting to parse...");
        foreach ($this->getPaginatorLinksGenerator() as $p_link)
        {
            try{
                $this->parseLink($p_link)->validateParsedData('outer');
            }catch (Exception $e){
                log::flog($e->getMessage());
                continue;
            }
            $this->extractOuterData();
        } // foreach p_link
    } // func


    protected function validateParsedData($type='outer')
    {
        Config::get('debug')==1 and log::d('try to find '.get_class($this));

        if(strpos($this->parsed_data, strtolower(get_class($this)))===false){
            if(Config::get('debug')==1){echo " VF"; }
            throw new Exception(get_class($this).' '.$this->page_current.' valid fail');
        }
        echo " V";
    } // func


    protected function beforeInit(){
        $this->stat->created_at = time();
    }
    protected function afterAll(){
        sleep(3);
    }

    protected function parseLink($p_link)
    {
        if(Config::get('debug')==1){ d('parseLink: '.$p_link); }

        $this->stat->curls++;
        $this->parsed_data = Scrapper::get($p_link, $this->scrapper_settings);
        $this->_sleep('after_parseLink');
        return $this;
    } // func



    protected function extractOuterData()
    {
        if(Config::get('debug')==1){ log::d('extractOuterData'); }

        $outer_nodes = $this->getOuterFindsGenerator();
        if(count($outer_nodes)<1){d(" NO OUTER NODES ");}

        $loop=0;
        foreach($outer_nodes as $outer_node)
        {
            $loop++;
            log::d("page={$this->page_current}|loop=$loop");
            if($this->alreadyInDb($outer_node)){ echo "|skipped"; continue; }

            $clear_node = $this->getNewClearNode();
            foreach($this->getOuterFinds() as $find)
            {
                $find_func_name = 'extract_'.$find;
                $clear_node[$find] = $this->{$find_func_name}($outer_node);
            } // foreach
            $this->extractInnerData($clear_node);
        } // foreach
        return $this;
    } // func


    protected function alreadyInDb($outer_node)
    {
        return $this->db->alreadyInDbOnUrl($this->extract_url($outer_node));
    }



    protected function extractInnerData($clear_node)
    {
        if(Config::get('debug')==1){ log::d('extractInnerData'); }

        try{
            $this->parseLink($clear_node['url'])->validateParsedData('inner');
            echo " inner";
        }catch (Exception $e){
            log::flog($e->getMessage());
            return false;
        }

        foreach($this->getInnerFinds() as $find)
        {
            $find_func_name = 'extract_'.($find);
            $clear_node[$find] = $this->{$find_func_name}($this->parsed_data);
        } // foreach
        $this->saveClearNode($clear_node);
    } // func






    protected function saveClearNode($clear_node)
    {
        echo " save";
        $this->db->saveClearNode($clear_node);
    } // func


    protected function getOuterFinds()
    {
        return $this->outer_finds;
    }

    protected function getInnerFinds()
    {
        return array_diff($this->total_finds, $this->outer_finds);
    }

    protected function getOuterFindsGenerator()
    {
        return $this->parsed_data->find($this->outer_shdp_string);
    }


    protected function extract_url($node){ return ""; }
    protected function extract_title($node){ return ""; }
    protected function extract_descr($node){ return ""; }
    protected function extract_category($node){ return ""; }
    protected function extract_tag($node){ return ""; }
    protected function extract_metro($node){ return ""; }
    protected function extract_model($node){ return ""; }
    protected function extract_tel($node){ return ""; }
    protected function extract_user($node){ return ""; }
    protected function extract_google_coords($node){ return ""; }
    protected function extract_site_created_at($node){ return ""; }
    protected function extract_img_links($node){ return ""; }

    protected function _sleep($type)
    {
        isset($this->sleep[$type])
            ? usleep($this->sleep[$type])
            : usleep(5000000);
    }


    protected function getNewClearNode()
    {
        return [
            'created_at' => time(),
            'updated_at' => time(),
            'project' => get_class($this),
        ];
    } // func

} // class