<?php

class db{

    private $query          = '';
    private $mysqli;
    private $select         = '';
    private $from           = '';
    private $join           = array();
    private $where          = array();
    private $limit          = '';
    private $insert         = '';
    private $insertValues   = array();
    private $insertQuery     = '';

    public function __construct()
    {
        $this->mysqli = new mysqli('localhost', 'denis', '************', 'beforward');
    }

    public function checkQuery($query){
        if (empty($query)) throw new debagException("Query error: Query is missing");
    }

    public function validateUserQuery($query){
        return $this->mysqli->real_escape_string($query);
    }

    public function selectAdd($select) {
        if (!is_array($select)){
            $select = array($select);
        }
        if ($this->select == ''){
            $this->select  = implode(',',$select);
        } else {
            $this->select .= implode(',',$select);
        }
    }

    public function fromAdd($table){
        if (!is_array($table)){
            $table = array($table);
        }
        if ($this->from == ''){
            $this->from  = implode(',',$table);
        } else {
            $this->from .= implode(',',$table);
        }
    }

    public function joinAdd($type,$table,$where){
        $this->join[]   = " $type $table ON $where";
    }

    public function whereAdd($where){
        $this->where[]  = $where;
    }

    public function getQuery(){
        return $this->query;
    }

    public function limitSet($limit,$offset = 0){
        try {
            if(System::is_number($limit) AND System::is_number($offset)){
                $this->limit = sprintf(" LIMIT %d OFFSET %d",$limit,$offset);
            };
        } catch (debagException $e){
            if(DEBAGMOD){
                $e->getDebagMessage('Database error set limit');
            }
            $this->limit = '';
        }
    }

    public function createQuery(){

        if (!empty($this->join)){
            $join = implode(' ',$this->join);
        } else {
            $join = '';
        }

        $this->query = "SELECT $this->select FROM $this->from $join";

        if (!empty($this->where)){
            $where          = implode(' AND ',$this->where);
            $this->query   .= " WHERE $where";
        }

        if (!empty($this->limit)){
            $this->query   .= $this->limit;
        }

        $this->query  .= ";";

    }

    public function dbQuery($query){

        return $this->mysqli->query($query);

    }

    public function dbResultAllFetchAssoc($query){
        $result = $this->dbQuery($query);
        if ($result){
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return false;
    }

    public function dbResultFetchAssoc($query){
        $result = $this->dbQuery($query);
        if ($result){
            return $result->fetch_assoc();
        }
        return false;
    }

    public function dbResultFetchAll($query){
        $result = $this->dbQuery($query);
        if ($result){
            return $result->fetch_all();
        }
        return false;
    }

    public function clearQuery(){
        $this->query    = '';
        $this->select   = '';
        $this->from     = '';
        $this->join     = array();
        $this->where    = array();
        $this->limit   = '';
    }

    public function destroy(){
        $this->mysqli->close();
        $this->clearQuery();
    }

    public function newInsert($tableName){
        $this->insert = sprintf("INSERT INTO %s",$this->validateUserQuery($tableName));
    }

    public function insertVal($key,$value){
        $this->insertValues[$this->validateUserQuery($key)] = $this->validateUserQuery($value);
    }

    public function createInsertQuery(){
        $columns = '';
        $values  = '';
        foreach ($this->insertValues as $key=>$value){
            if (empty($columns) OR empty($values)){
                $columns = $key;
                $values  = "'".$value."'";
            } else {
                $columns .= ','.$key;
                $values  .= ",'".$value."'";
            }
        }
        $this->insertQuery = $this->insert." ($columns) VALUES ($values);";
    }

    public function getInsertQuery(){
        return $this->insertQuery;
    }

}