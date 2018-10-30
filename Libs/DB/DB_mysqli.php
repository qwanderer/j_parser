<?php

class DB_mysqli
{
    private $connection;
    private $result;
    public $last_sql;

    public function __construct()
    {
        $connection = mysqli_connect(Config::get('db')['host'], Config::get('db')['user'], Config::get('db')['pass'], Config::get('db')['db_name']);
        if (!$connection) {
            die('Не удалось подключиться к базе данных: ' . mysqli_connect_error());
        }
        $this->connection = $connection;
    } // func

    public function sql($sql)
    {
        $this->last_sql = $sql;
        $temp = $this->connection->query($sql);
        if($temp !== false){
            $this->result = $temp;
        }else{
            d("Mysqli error: ".$this->connection->error. "\n\n".$sql);
        }
        return $this;
    } // func

    public function getLastId()
    {
        return $this->connection->insert_id;
    }

    public function toGenerator()
    {
        if (mysqli_num_rows($this->result) > 0) {
            while($row = mysqli_fetch_assoc($this->result)) {
                yield $row;
            }
        }
    }

    public function toArray()
    {
        $i=0;$ret = array();
        if (mysqli_num_rows($this->result) > 0) {
            while ($row = mysqli_fetch_assoc($this->result)){
                foreach ($row as $key => $value){
                    $ret[$i][$key] = $value;
                }
                $i++;
            }
        }
        return $ret;
    } // func

    public function m_escape($string)
    {
        return mysqli_real_escape_string($this->connection, $string);
    } // func


    public function alreadyInDbOnUrl($url)
    {
        if(Config::get('debug')==1){ d('alreadyInDbOnUrl: '.$url); }
        $sql = "SELECT id FROM p_content WHERE url='".$this->m_escape($url)."'";
        $data = $this->sql($sql)->toArray();
        return ($data and isset($data[0]) and isset($data[0]['id']));
    }


    public function saveClearNode($clear_node, $keys)
    {
        $insert_arr = [];
        foreach ($clear_node as $k=>$v){
            $db_key = $keys[$k];
            $insert_arr["`".$db_key."`"] = "'".$this->m_escape($v)."'";
        } // foreach
        $sql = "INSERT INTO p_content(".implode(',',array_keys($insert_arr)).")VALUES(".implode(',',array_values($insert_arr)).")";
        $this->sql($sql);
    } // func


} // class