<?php

namespace Core;

use App\Config;
use PDO;
use libs\Database;
use libs\Response;
use libs\FileUpload;

/**
 * Base model
 *
 * PHP version 5.4
 */
  class Model
{
    protected static function getDB(){
        static $db = null;
        if ($db === null) {
            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' .
            Config::DB_NAME . ';charset=utf8';
            $db=new Database($dsn,Config::DB_USER, Config::DB_PASSWORD);
            
            // Throw an Exception when an error occurs
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        }
           return $db;
    }
    
    /**
     * Get the PDO database connection
     *
     * @return mixed
     */
   protected static function getDB1()
    {
        static $db = null;
        
        if ($db === null) {
            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' .
                Config::DB_NAME . ';charset=utf8';
                $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);
                
                // Throw an Exception when an error occurs
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        
        return $db;
    }
    
    public function save()
    {
        //echo(substr(strrchr(get_class($this),"\\"),1));
        $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
        //print_r((array)$this);
        $db=self::getDB();
        $dataArray=(array)$this;
        $result=$db->insert($table,$dataArray);
        $response=new Response();
        if($result){
            $response->success = 1;
            $response->message = "Successfully Added";
            $response->code=201;
            return $response;
          }else{
            $response->success = 0;
            $response->message= "Something went wrong";
            $response->code=400;
            return $response;
          }
    }
    public function update($condition)
    {
        //echo(substr(strrchr(get_class($this),"\\"),1));
        $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
        //print_r((array)$this);
        $db=self::getDB();
        $dataArray=(array)$this;
        $result=$db->update($table,$dataArray,$condition);
        $response=new Response();
        if($result){
            $response->success = 1;
            $response->message = "Successfully Updated";
            $response->code=201;
            return $response;
          }else{
            $response->success = 0;
            $response->message= "Something went wrong";
            $response->code=400;
            return $response;
          }
    }
    public function delete($condition)
    {
        $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
        $db=self::getDB();
        $result=$db->delete("DELETE FROM $table WHERE $condition");
        $response=new Response();
        if($result){
            $response->success = 1;
            $response->message = "Successfully Deleted";
            $response->code=201;
            return $response;
          }else{
            $response->success = 0;
            $response->message= "Something went wrong";
            $response->code=400;
            return $response;
          }
    }
    public function get($condition)
    {
      
        //return ($this);
        $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
        $db=self::getDB();
        if($condition==null){
          $condition=1;
        }
        $result=$db->select("SELECT * FROM $table WHERE $condition");
        $response=new Response();
        if($result){
            $response->success = 1;
            $response->records = $result;
            $response->code=201;
            return $response;
          }else{
            $response->success = 0;
            $response->message= "Something went wrong";
            $response->code=400;
            return $response;
          }
    }
    public function all($columns=array(),$pagination=false,$page=null,$dtsearch=null,$filterColumn,$records=null)
    {
         // print_r($columns);
        $columnsList= implode(",",$columns);
          
        $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
        $db=self::getDB();
        if($pagination==false and $records==null){
          $result=$db->select("SELECT $columnsList FROM $table WHERE $filterColumn LIKE %".$dtsearch."%"); 
          $total=$db->selectCount("SELECT $columnsList FROM $table WHERE $filterColumn LIKE %".$dtsearch."%");  
          $response=new Response();
          if($result){
              $response->success = 1;
              $response->records = $result;
              $response->first=1;
              $response->last=$total;
              $response->total=$total;
              $response->code=201;
              return $response;
            }else{
              $response->success = 0;
              $response->message= "Something went wrong";
              $response->code=400;
              return $response;
            }
        }else{
          $showRecordPerPage = 10;
          if(isset($page) && !empty($page)){
          $currentPage = $page;
          }else{
          $currentPage = 1;
          }
          $startFrom = ($currentPage * $showRecordPerPage) - $showRecordPerPage;
          $total=$db->selectCount("SELECT $columnsList FROM $table WHERE $filterColumn LIKE '%".$dtsearch."%'");  
          $lastPage = ceil($total/$showRecordPerPage);

          $firstPage = 1;
          $nextPage = $currentPage + 1;
          $previousPage = $currentPage - 1;
          $totalFilter=$db->selectCount("SELECT $columnsList FROM $table WHERE $filterColumn LIKE '%".$dtsearch."%' LIMIT $startFrom,$showRecordPerPage"); 

          $result=$db->select("SELECT $columnsList FROM $table  WHERE $filterColumn LIKE '%".$dtsearch."%' LIMIT $startFrom,$showRecordPerPage"); 
          
          $response=new Response();
          if($result){
              $response->success = 1;
              $response->records = $result;
              $response->first=$startFrom+1;
              $response->last=$page==$lastPage?$total:$totalFilter*$page;
              $response->total=$total;
              $response->code=201;
              return $response;
            }else{
              $response->success = 0;
              $response->message= "Something went wrong";
              $response->code=400;
              return $response;
            }
        }
    }
    public function upload($location=null)
    {
      
      $dataArray=(array)$this;
     
      $db=self::getDB();
     
      $fileUpload=new FileUpload();
      $fileUpload->setLocation($location==null?'tmp':'photo/'.$location);
      $filename=$fileUpload->uploadSingleFile($dataArray);     
      $response=new Response();
      if($response){
        $response->success = 1;
        $response->message = "uploaded";
        $response->filename = $filename;
        $response->code=201;
        return $response;
      }else{
        $response->success = 0;
        $response->message= "Something went wrong";
        $response->code=400;
        return $response;
      }
       
    }
    public function deleteFile($location=null)
    {
      
      $dataArray=(array)$this;
      $fileUpload=new FileUpload();
      $fileUpload->setLocation($location==null?'tmp':$location);
      $result=$fileUpload->delete($dataArray);
      $response=new Response();
      if($result){
        $response->success = 1;
        $response->message = "deleted";
        $response->filename = $result;
        $response->code=201;
        return $response;
      }else{
        $response->success = 0;
        $response->message= "Something went wrong";
        $response->code=400;
        return $response;
      }
       
    }
    public function uploadMultiple($location=null)
    {
      $dataArray=(array)$this;
      $fileUpload=new FileUpload();
      $fileUpload->setLocation($location==null?'tmp':$location);
      echo $fileUpload->uploadMultipleFile($dataArray);
       
    }

    public function toJSON()
    {
        return json_encode($this);
    }

    public function json()
    {
        echo json_encode();
    }
}
