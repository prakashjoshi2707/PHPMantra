<?php
namespace libs;

use PDO;

/**
 * Database class extends PDO class
 */
class Database extends PDO
{
    public function __construct($dsn, $userName, $password)
    {
        parent::__construct($dsn, $userName, $password);
        //parent::__construct (MySQL::DB_TYPE . ':host=' . MySQL::DB_HOST . ';dbname=' . MySQL::DB_NAME, MySQL::DB_USER, MySQL::DB_PASSWORD );
    }

    public function select($sql, $array=array(), $fetchMode=PDO::FETCH_ASSOC)
    {
        $stmt=$this->prepare($sql);
        /*foreach ($array as $key=>$value){
            $stmt->bindValue(":$key", $value);
        }*/
        $stmt->execute();
        return $stmt->fetchAll($fetchMode);
    }
    public function selectCount($sql, $array=array(), $fetchMode=PDO::FETCH_ASSOC)
    {
        $stmt=$this->prepare($sql);
        /* foreach ($array as $key=>$value){
         $stmt->bindValue(":$key", $value);
            } */
        $stmt->execute();
        $result=$stmt->fetchAll();
        return $stmt->rowCount();
    }

    public function insert($table, $data)
    {
        $fieldNames=implode('`,`', array_keys($data));
        $fieldValues=":".implode(', :', array_keys($data));
        //echo "INSERT INTO $table(`$fieldNames`) VALUES ($fieldValues)";
        //print_r($data);
        //echo "<hr>";
        $stmt=$this->prepare("INSERT INTO $table(`$fieldNames`) VALUES ($fieldValues)");
        foreach ($data as $key=>$value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }
    public function update($table, $data, $where)
    {
        $fieldDetails=null;
        foreach ($data as $key=>$value) {
            $fieldDetails .="`$key`= :$key,";
        }
        $fieldDetails=rtrim($fieldDetails, ',');
        //echo "UPDATE $table SET $fieldDetails WHERE $where";
        $stmt=$this->prepare("UPDATE $table SET $fieldDetails WHERE $where");
        foreach ($data as $key=>$value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }
    public function delete($sql)
    {
        $stmt=$this->prepare($sql);
        return $stmt->execute();
    }

    public function getColumnName($tableName)
    {
        $stmt=$this->prepare("DESCRIBE $tableName");
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_COLUMN);
        return $result;
    }
}
