<?php

class BaseModel
{
    private $data;
    private $db;
    protected $table;

    public function __construct() {
        $this->data = array();
        $this->db = new PDO('mysql:host=localhost;dbname=pruebaslim', "root", "admin123456");
    }

    private function setNames() {
        return $this->db->query("SET NAMES 'utf8'");
    }

    public function getAll($fields = "*", $where = "1=1") {
        self::setNames();
        $sql = "SELECT {$fields} FROM {$this->table} WHERE {$where}";
        foreach ($this->db->query($sql,PDO::FETCH_OBJ) as $res) {
            $this->data[] = $res;
        }
        return $this->data;
    }

    /**
     * @param $id
     * @param string $fields
     * @param string $searchField
     * @param string $where
     * @return mixed
     */
    public function getOne($id, $fields = "*",$searchField = "id", $where = "1=1") {
        self::setNames();
        if(!$id){
            return false;
        }
        $where = "{$searchField} = '{$id}' AND ({$where})";
        $sql = "SELECT {$fields} FROM {$this->table} WHERE {$where} LIMIT 1";
        foreach ($this->db->query($sql,PDO::FETCH_OBJ) as $res) {
            $this->data = $res;
            return $this->data;
        }
        return false;
    }

    public function store($data) {
        $fields = array();
        $values = array();
        foreach($data as $field=>$value){
            $values[] = "'{$value}'";
            $fields[] = $field;
        }
        self::setNames();
        $sql = "INSERT INTO {$this->table} (".join(',',$fields).") VALUES (".join(',',$values).")";
        $result = $this->db->query($sql);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function update($id, $data) {
        $values = array();
        foreach($data as $field=>$value){
            $values[] = "{$field} = '{$value}'";
        }
        self::setNames();
        $sql = "UPDATE {$this->table} SET ".join(',',$values)." WHERE id = '{$id}'";
        $result = $this->db->query($sql);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}