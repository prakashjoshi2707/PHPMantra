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

    public function beginTransaction()
    {
      $db=self::getDB();
      $db->beginTransaction();
    }
    public function rollback()
    {
      $db=self::getDB();
      $db->rollback();
    }
    public function commit()
    {
      $db=self::getDB();
      $db->commit();
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
                $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                
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
    public function getDistinct($column,$condition)
    {
      
        //return ($this);
        $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
        $db=self::getDB();
        if($condition==null){
          $condition=1;
        }
        $result=$db->select("SELECT DISTINCT($column) FROM $table WHERE $condition");
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
    // public function all($columns=array(),$pagination=false,$page=null,$dtsearch=null,$filterColumn,$records=null)
    // {
    //      // print_r($columns);
    //     $columnsList= implode(",",$columns);
          
    //     $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
    //     $db=self::getDB();
    //     if($pagination==false and $records==null){
    //       $result=$db->select("SELECT $columnsList FROM $table WHERE $filterColumn LIKE %".$dtsearch."%"); 
    //       $total=$db->selectCount("SELECT $columnsList FROM $table WHERE $filterColumn LIKE %".$dtsearch."%");  
    //       $response=new Response();
    //       if($result){
    //           $response->success = 1;
    //           $response->records = $result;
    //           $response->first=1;
    //           $response->last=$total;
    //           $response->total=$total;
    //           $response->code=201;
    //           return $response;
    //         }else{
    //           $response->success = 0;
    //           $response->message= "Something went wrong";
    //           $response->code=400;
    //           return $response;
    //         }
    //     }else{
    //       $showRecordPerPage = 10;
    //       if(isset($page) && !empty($page)){
    //       $currentPage = $page;
    //       }else{
    //       $currentPage = 1;
    //       }
    //       $startFrom = ($currentPage * $showRecordPerPage) - $showRecordPerPage;
    //       $total=$db->selectCount("SELECT $columnsList FROM $table WHERE $filterColumn LIKE '%".$dtsearch."%'");  
    //       $lastPage = ceil($total/$showRecordPerPage);

    //       $firstPage = 1;
    //       $nextPage = $currentPage + 1;
    //       $previousPage = $currentPage - 1;
    //       $totalFilter=$db->selectCount("SELECT $columnsList FROM $table WHERE $filterColumn LIKE '%".$dtsearch."%' LIMIT $startFrom,$showRecordPerPage"); 

    //       $result=$db->select("SELECT $columnsList FROM $table  WHERE $filterColumn LIKE '%".$dtsearch."%' LIMIT $startFrom,$showRecordPerPage"); 
          
    //       $response=new Response();
    //       if($result){
    //           $response->success = 1;
    //           $response->records = $result;
    //           $response->first=$startFrom+1;
    //           $response->last=$page==$lastPage?$total:$totalFilter*$page;
    //           $response->total=$total;
    //           $response->code=201;
    //           return $response;
    //         }else{
    //           $response->success = 0;
    //           $response->message= "Something went wrong";
    //           $response->code=400;
    //           return $response;
    //         }
    //     }
    // }
    public function all($pagination=false,$orderBy='id ASC',$records=null)
    {
        $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
        $db=self::getDB();
        if($pagination==false and $records==null){
          $result=$db->select("SELECT * FROM $table ORDER BY $orderBy "); 
          $total=$db->selectCount("SELECT * FROM $table");  
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
          $result=$db->select("SELECT * FROM $table ORDER BY $orderBy LIMIT 0,10"); 
          $total=$db->selectCount("SELECT * FROM $table");  
          $response=new Response();
          if($result){
              $response->success = 1;
              $response->records = $result;
              $response->first=1;
              $response->last=5;
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
    public function total($condition=null)
    {
        $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
        $db=self::getDB();
        if($condition==null){
         $total=$db->selectCount("SELECT * FROM $table");
        }else{
          $total=$db->selectCount("SELECT * FROM $table WHERE $condition");
        } 
        $response=new Response();
        if($total){
            $response->success = 1;
            $response->total=$total;
            $response->code=200;
            return $response;
          }else{
            $response->success = 0;
            $response->total=0;
            $response->code=200;
            return $response;
        }
    }

    // To check the unique reocrds
    public function check($condition)
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
            $response->message= "Available";
            $response->code=201;
            return $response;
          }else{
            $response->success = 0;
            $response->message= "Not Available";
            $response->code=400;
            return $response;
          }
    }

    // public function upload($location=null)
    // {
      
    //   $dataArray=(array)$this;
    //   $db=self::getDB();
    //   $result=$db->insert('tbltmpstorage',$dataArray);
    //   $fileUpload=new FileUpload();
    //   $fileUpload->setLocation($location==null?'tmp':'photo/'.$location);
    //   $result=$fileUpload->uploadSingleFile($dataArray);
    //   $response=new Response();
    //   if($result){
    //     $response->success = 1;
    //     $response->message = "uploaded";
    //     $response->filename = $result;
    //     $response->code=201;
    //     return $response;
    //   }else{
    //     $response->success = 0;
    //     $response->message= "Something went wrong";
    //     $response->code=400;
    //     return $response;
    //   }
       
    // }
    public function upload($location=null)
    {
      
      // echo json_encode((array)$this);
      $dataArray=(array)$this;     
      $fileUpload=new FileUpload();
      $fileUpload->setLocation($location==null?'tmp':$location);
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
    public function lastInsertID()
    {
      $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
      $db=self::getDB();
      $result=$db->lastInsertId();
      return $result;
    }
    public function toJSON()
    {
        return json_encode($this);
    }

    public function json()
    {
        echo json_encode();
    }
    // To Create PHP Class Object Based on Table, Validation, HTML Form and JS validation
    public function generateContent()
    {
        
       //return ($this);
       $table="tbl".strtolower(substr(strrchr(get_class($this),"\\"),1));
       $className=(substr(strrchr(get_class($this),"\\"),1));
       $db=self::getDB();
       $result=$db->select("SELECT COLUMN_NAME, IS_NULLABLE,CHARACTER_MAXIMUM_LENGTH,DATA_TYPE, COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'$table' and TABLE_SCHEMA = '".Config::DB_NAME."' ORDER BY `ORDINAL_POSITION` ");
      //  print_r($result);

      // ==============================FROM===================================================
      echo "<h1>ALTER TABLE with following fields =>phpmyadmin->SQL</h1>";
      echo "
      ALTER TABLE $table ADD (<br>
      &nbsp;&nbsp;`createdAt` timestamp NULL DEFAULT NULL  COMMENT 'Created at date & time',<br>
      &nbsp;&nbsp;`createdBy` varchar(50) DEFAULT NULL COMMENT 'Created by username or unique id or email or phone',<br>
      &nbsp;&nbsp;`createdFrom` varchar(20) DEFAULT NULL COMMENT 'Created from location or IP address',<br>
      &nbsp;&nbsp;`modifiedAt` datetime DEFAULT NULL COMMENT 'Modified at date & time',<br>
      &nbsp;&nbsp;`modifiedBy` varchar(50) DEFAULT NULL COMMENT 'Modified by username or unique id or email or phone',<br>
      &nbsp;&nbsp;`modifiedFrom` varchar(20) DEFAULT NULL COMMENT 'Modified from location or IP address',<br>
      &nbsp;&nbsp;`deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Record is Deleted',<br>
      &nbsp;&nbsp;`deletedAt` datetime DEFAULT NULL COMMENT 'Deleted at date & time',<br>
      &nbsp;&nbsp;`deletedBy` varchar(50) DEFAULT NULL COMMENT 'Deleted by username or unique id or email or phone',<br>
      &nbsp;&nbsp;`deletedFrom` varchar(20) DEFAULT NULL COMMENT 'Deleted from location or IP address'<br>
      )";
      echo "<hr>";
       echo "<h1>HTML FORM =>index.html</h1>";
       echo "<h4>Note: Please make ID column hidden for editing purpose because it is autogenerated field</h4>";
       echo htmlspecialchars('<form method="POST" action="'.lcfirst($className).'/api" enctype="multipart/form-data" id="form-'.strtolower($className).'" name="form-'.strtolower($className).'">');
       foreach ($result as $value){
  
         if (
           $value['COLUMN_NAME']!="createdAt"  &&  $value['COLUMN_NAME']!="createdBy"  && $value['COLUMN_NAME']!="createdFrom"&&
           $value['COLUMN_NAME']!="modifiedAt" && $value['COLUMN_NAME']!="modifiedBy" && $value['COLUMN_NAME']!="modifiedFrom"&&
           $value['COLUMN_NAME']!="deletedAt" && $value['COLUMN_NAME']!="deletedBy" && $value['COLUMN_NAME']!="deletedFrom" && $value['COLUMN_NAME']!="deleted" && $value['COLUMN_NAME']!="status"
         ) {
           
         
             $max=$value['DATA_TYPE']=="varchar" || $value['DATA_TYPE']=="text" || $value['DATA_TYPE']=="mediumtext" || $value['DATA_TYPE']=="longtext"?"maxlength=\"".$value['CHARACTER_MAXIMUM_LENGTH']."\"":false;
             $formFiled=$value['DATA_TYPE']=="text"?
        '<textarea class="form-control"  id="'.$value['COLUMN_NAME'].'" name="'.$value['COLUMN_NAME'].'" '.$max.' placeholder="Enter '.ucwords($value['COLUMN_COMMENT']).'"  rows="2"></textarea>':
          $formFiled=$value['DATA_TYPE']=="int"? '<input type="number" class="form-control" id="'.$value['COLUMN_NAME'].'" name="'.$value['COLUMN_NAME'].'" '.$max.' placeholder="Enter '.ucwords($value['COLUMN_COMMENT']).'">':
            '<input type="text" class="form-control" id="'.$value['COLUMN_NAME'].'" name="'.$value['COLUMN_NAME'].'" '.$max.' placeholder="Enter '.ucwords($value['COLUMN_COMMENT']).'">';

             echo "<pre>";
             echo htmlspecialchars('
  <label for="'.$value['COLUMN_NAME'].'" class="form-label">'.ucwords($value['COLUMN_COMMENT']).'</label>
  '.$formFiled);
             
             echo "</pre>";
            
         }
         
       }
       echo htmlspecialchars('<button type="submit" id="submit" name="submit" class="btn btn-primary">SAVE</button>')."<br>";
       echo htmlspecialchars("</form>");
       //====================================JAVASCRIPT====================================================
       echo "<hr>";
       echo "<h1>Javascript Validation =>index.js</h1>";
       echo "<h4>Note: Please remove ID column because it is autogenerated field</h4>";
       foreach ($result as $value) {
           if (
          $value['COLUMN_NAME']!="createdAt"  &&  $value['COLUMN_NAME']!="createdBy"  && $value['COLUMN_NAME']!="createdFrom"&&
          $value['COLUMN_NAME']!="modifiedAt" && $value['COLUMN_NAME']!="modifiedBy" && $value['COLUMN_NAME']!="modifiedFrom"&&
          $value['COLUMN_NAME']!="deletedAt" && $value['COLUMN_NAME']!="deletedBy" && $value['COLUMN_NAME']!="deletedFrom" && $value['COLUMN_NAME']!="deleted" && $value['COLUMN_NAME']!="status"
        ) {
               $required=$value['IS_NULLABLE']=="NO"?"true":"false";
               echo "<pre>";
               echo "
        ".$value['COLUMN_NAME'].": {
          isRequired: ".$required.",
          caption: '".ucwords($value['COLUMN_COMMENT'])."',
        },
        ";
               echo "</pre>";
           }
       }
      //  ================================================================================================

      echo "<hr>";
      echo "<h1>PHP Validation =>{$className}Activity.php inside POST and PUT</h1>";
      echo "<h4>Note: Please remove ID column only from POST because it is autogenerated field</h4>";
      foreach ($result as $value) {
          if (
          $value['COLUMN_NAME']!="createdAt"  &&  $value['COLUMN_NAME']!="createdBy"  && $value['COLUMN_NAME']!="createdFrom"&&
          $value['COLUMN_NAME']!="modifiedAt" && $value['COLUMN_NAME']!="modifiedBy" && $value['COLUMN_NAME']!="modifiedFrom"&&
          $value['COLUMN_NAME']!="deletedAt" && $value['COLUMN_NAME']!="deletedBy" && $value['COLUMN_NAME']!="deletedFrom" && $value['COLUMN_NAME']!="deleted" && $value['COLUMN_NAME']!="status"
        ) {
              $required=$value['IS_NULLABLE']=="NO"?"true":"false";
              $max=$value['DATA_TYPE']=="varchar" || $value['DATA_TYPE']=="text"?"'max'=>".$value['CHARACTER_MAXIMUM_LENGTH'].",":false;
              echo "<pre>";
              echo " '".$value['COLUMN_NAME']."'=>[
         'required'=>$required,
         $max
       ],";
              echo "</pre>";
          }
      }
     
      //===================================================================================================

       echo "<h1>Class Definition To Store</h1>";
       echo "<h4>Note: Please remove ID column because it is autogenerated field</h4>";
       echo "<h4>Note: Please remove ID column for storing because it is autogenerated field</h4>";
       echo "\$".lcfirst($className)."=new ".$className."();<br>";
       foreach ($result as $value){
         if($value['COLUMN_NAME']=="createdAt"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="createdBy"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=Session::get('USERNAME');";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="createdFrom"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="modifiedAt"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
          // echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="modifiedBy"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=Session::get('USERNAME');";
          // echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="modifiedFrom"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          // echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="deletedAt"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
          // echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="deletedBy"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=Session::get('USERNAME');";
          // echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="deletedFrom"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          // echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="deleted"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          // echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="id"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          // echo "<br>";
         }
         else{
           echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$request->input[\"".$value['COLUMN_NAME']."\"];";
           echo "<br>";
         }
       }
       echo "\$response=\$".lcfirst($className)."->save();";
       echo "<br>";

       echo " echo \$response->toJSON();";
       echo "<br>";
       echo "<hr>";
       echo "<h1>Class Definition To Update</h1>";
       echo "<h4>Note: Please remove ID column because ID for identification purpose but keep in update condition because model will update the record based on id </h4>";
       echo "\$".lcfirst($className)."=new ".$className."();<br>";
       foreach ($result as $value){
       
         if($value['COLUMN_NAME']=="createdAt"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
          // echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="createdBy"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=GlobalSession::get('USERNAME');";
          // echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="createdFrom"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          // echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="modifiedAt"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="modifiedBy"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=Session::get('USERNAME');";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="modifiedFrom"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="deletedAt"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
          // echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="deletedBy"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=Session::get('USERNAME');";
          // echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="deletedFrom"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          // echo "<br>";
         } elseif($value['COLUMN_NAME']=="deleted"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          // echo "<br>";
         } elseif($value['COLUMN_NAME']=="id"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          // echo "<br>";
         }
         else{
           echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$request->input[\"".$value['COLUMN_NAME']."\"];";
           echo "<br>";
         }
       }
       echo "\$response=\$".lcfirst($className)."->update(\"id=\".\$request->input[\"id\"]);";
       echo "<br>";

       echo " echo \$response->toJSON();";
       echo "<br>";


       echo "<hr>";
       echo "<h1>Class Definition To Delete</h1>";
       
       
       echo "if(is_array(\$request->input[\"id\"])){<br>";
       echo " &nbsp;&nbsp;&nbsp; \$id=implode(',', \$request->input[\"id\"]);<br>";
       echo "&nbsp;}else{<br>";
       echo "&nbsp;&nbsp;&nbsp;\$id= \$request->input[\"id\"];<br>";
       echo "&nbsp;}<br>";
       echo "\$".lcfirst($className)."=new ".$className."();<br>";
   
       foreach ($result as $value){
       
         if($value['COLUMN_NAME']=="createdAt"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
          // echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="createdBy"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=GlobalSession::get('USERNAME');";
          // echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="createdFrom"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          // echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="modifiedAt"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
          // echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="modifiedBy"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=Session::get('USERNAME');";
          // echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="modifiedFrom"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          // echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="deletedAt"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="deletedBy"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=Session::get('USERNAME');";
          echo "<br>";
         }
         elseif($value['COLUMN_NAME']=="deletedFrom"){
          echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          echo "<br>";
         } elseif($value['COLUMN_NAME']=="deleted"){
          // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=1;";
          // echo "<br>";
          echo "if(\$request->input[\"tag\"]==\"delete\"){<br>";
            echo "&nbsp;&nbsp;&nbsp;\$".lcfirst($className)."->deleted=1;<br>"; 
           echo "&nbsp;}else{<br>";
            echo "&nbsp;&nbsp;&nbsp;\$".lcfirst($className)."->deleted=0;<br>"; 
           echo "}<br>";
         }
         else{
          //  echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$request->input[\"".$value['COLUMN_NAME']."\"];";
          //  echo "<br>";
         }
       }
       echo "\$response=\$".lcfirst($className)."->update(\" id IN (\$id) \");";
       echo "<br>";

       echo " echo \$response->toJSON();";
       echo "<br>";

      
       echo "<hr>";
      //  Datatable
      echo "<h1>Datatable Definition</h1>";
      echo "<h1>Table Definition for Show View => show.html</h1>";
      echo nl2br(htmlspecialchars("
            <style>
        .active{
          color:brown !important;
          border-bottom: 1px solid brown;
        }
      </style>
      "));
      echo nl2br(htmlspecialchars('

      <div class="row my-2">

  <div class="col-8">
    <nav class="nav ">
      <a class="nav-link active"  href="#"  id="tab-show-all"><i class="fas fa-table"></i> All <span id="total-show-all">(0)</span></a>
    
      <a class="nav-link" href="#"  id="tab-show-trash"><i class="fas fa-trash"></i> Trash <span id="total-show-trash">(0)</span></a>
    </nav>
  </div>

  <div class="col-4">

<a class="btn btn-primary float-right" href="{{URL}}'.strtolower($className).'/index" role="button">Add New '.$className.' </a>

<a class="btn btn-danger float-right mr-2" style="display: none;" href="#" role="button" id="btn-delete-selected">Delete Selected </a>
<a class="btn btn-info float-right mr-2" style="display: none;" href="#" role="button" id="btn-restore-selected">Restore Selected </a>
  </div>

</div>
      '));

      echo "<pre>";
   
       echo htmlspecialchars('
<table id="'.strtolower($className).'" name="'.strtolower($className).'" class="table w-100">
    <thead>
      <tr>');
    echo "<br>";
    echo nl2br(htmlspecialchars('        <th scope="col"><input type="checkbox" id="check-all"></th>
        <th scope="col">#</th>'
        
    ))."<br>";
    foreach ($result as $value){
    
      echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".htmlspecialchars('<th scope="col">'.ucwords(strtolower($value['COLUMN_NAME'])).'</th>');
     echo "<br>";
  }
  echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".htmlspecialchars('<th scope="col">Action</th>');
    echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".htmlspecialchars('</tr>
  </thead>
  <tbody>
  </tbody>
</table>
       ');
      echo "<br>{{ include('Template/alert.html') }}";
      echo "</pre>";
      
      echo "<h1>Datatable Definition: Table Definition for {$className}.php Model</h1>";
      echo "<h4>Note: Please remove ID column because it is autogenerated field</h4>";
      echo "<pre>";
  echo" public  function datatable(\$deleted)
  {
      try {
          \$db = static::getDB();
          \$query = '';
          \$output = array();
          \$query .= \"SELECT * FROM $table WHERE deleted=\$deleted \";
          if (isset(\$_POST[\"search\"][\"value\"])) {
              // Please provide the search field instead of id
              \$query .= ' AND name LIKE \"%' . \$_POST[\"search\"][\"value\"] . '%\" ';
          }
          if (isset(\$_POST[\"order\"])) {
              \$query .= ' ORDER BY ' . \$_POST['order']['0']['column'] . ' ' . \$_POST['order']['0']['dir'] . ' ';
          } else {
            // Please provide the sort field instead of id
              \$query .= 'ORDER BY id DESC ';
          }
          if (\$_POST[\"length\"] != - 1) {
              \$query .= 'LIMIT ' . \$_POST['start'] . ', ' . \$_POST['length'];
          }
          
          \$statement = \$db->prepare(\$query);
          \$statement->execute();
          \$result = \$statement->fetchAll();
          \$data = array();
          \$filtered_rows = \$statement->rowCount();

          // Please remove the id from sub_array[] because slno need to used for page wise record number 

          \$slno=1;
          foreach (\$result as \$row) {
              \$sub_array = array();
              \$sub_array[] ='".htmlspecialchars("<input type=\"checkbox\" class=\"check-item\" value=\"'.\$row[\"id\"].'\">';").
              "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$sub_array[]=\$slno+ \$_POST['start'];<br>";
              
            foreach ($result as $value){
                  echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$sub_array[] = \$row[\"".$value['COLUMN_NAME']."\"];";
                  echo "<br>";
              }
              echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; if(\$deleted==0){";
              echo"&nbsp;&nbsp;
              \$sub_array[] =".htmlspecialchars("'<a class=\"btn btn-sm btn-icon btn-primary btn-edit\" edit-row='.\$row[\"id\"].'><i class=\"fa fa-pencil-alt\"></i></a>
                <a class=\"btn btn-sm btn-icon btn-danger btn-delete\" delete-row='.\$row[\"id\"].'><i class=\"far fa-trash-alt\"></i></a> 
                <a class=\"btn btn-sm btn-icon btn-info btn-detail\" get-row='.\$row[\"id\"].'><i class=\"fas fa-info-circle\"></i></a> ';");
              echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}"; 
              echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;else{<br>";
              echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$sub_array[] ='".htmlspecialchars("<a class=\"btn btn-sm btn-icon btn-info btn-restore\" restore-row='.\$row[\"id\"].'><i class=\"fas fa-trash-restore\"></i></a>';");
              echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}";  
              echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$data[] = \$sub_array;
              \$slno++;  
              
          }
          \$output = array(
            \"draw\" => intval(\$_POST[\"draw\"]),
            \"recordsTotal\" => \$filtered_rows,
            \"recordsFiltered\" => self::getTotal".($className)."(\$deleted),
            \"data\" => \$data,
            \"totalShowAll\"=>self::getTotal".($className)."(0),
            \"totalShowTrash\"=>self::getTotal".($className)."(1),
        );
        return json_encode(\$output);
    } catch (PDOException \$e) {
        echo \$e->getMessage();
    }
  }
      
public static function getTotal".($className)."(\$deleted=0)
{
  \$db = static::getDB();
  \$statement = \$db->prepare(\"SELECT * FROM $table WHERE deleted=\$deleted \" );
  \$statement->execute();
  return \$statement->rowCount();
}";
     
     
      echo "</pre>";

      echo "<hr>";
      echo "<h1>DETAIL => detail.html</h1>";
      echo htmlspecialchars("<a class=\"btn btn-primary\" href=\"{{URL}}".strtolower($className)."/show\" role=\"button\">Show ".ucfirst(strtolower($className))." Details </a>");
      foreach ($result as $value){
       echo "<pre>";
       echo htmlspecialchars("<span id=".$value['COLUMN_NAME']."></span><br>");
       echo "</pre>";
      }
      echo "<hr>"; 
      echo "<h1>ANDROID Global Configuration</h1>";
      echo "<h4>Add dependency in app > Gradle Scripts > build.gradle(app)</h4> ";
      echo "implementation 'com.android.volley:volley:1.2.1'"; 
      echo "<br>";
      echo "<h4>Add permissions AndroidManifest.xml </h4> ";
      echo  nl2br(htmlspecialchars('<!--permissions for INTERNET-->
      <uses-permission android:name="android.permission.INTERNET"/>
        '));
        
      echo "<hr>";
   
      echo "<h1>ANDROID UI.xml</h1>";
      foreach ($result as $value){
  
        if (
          $value['COLUMN_NAME']!="createdAt"  &&  $value['COLUMN_NAME']!="createdBy"  && $value['COLUMN_NAME']!="createdFrom"&&
          $value['COLUMN_NAME']!="modifiedAt" && $value['COLUMN_NAME']!="modifiedBy" && $value['COLUMN_NAME']!="modifiedFrom"&&
          $value['COLUMN_NAME']!="deletedAt" && $value['COLUMN_NAME']!="deletedBy" && $value['COLUMN_NAME']!="deletedFrom" && $value['COLUMN_NAME']!="deleted" && $value['COLUMN_NAME']!="status"
        ) {
            $max=$value['DATA_TYPE']=="varchar" || $value['DATA_TYPE']=="text" || $value['DATA_TYPE']=="mediumtext" || $value['DATA_TYPE']=="longtext"?"android:maxLength=\"".$value['CHARACTER_MAXIMUM_LENGTH']."\"":false;
            $formFiled=$value['DATA_TYPE']=="text"?
       '
        <EditText
          android:id="@+id/et'.ucwords($value['COLUMN_NAME']).'"
          android:layout_width="match_parent"
          android:layout_height="wrap_content"
          android:ems="10"
          '.$max.'
          android:hint="Enter '.ucwords($value['COLUMN_COMMENT']).'"
          android:inputType="textMultiLine" />
        ':
         $formFiled=$value['DATA_TYPE']=="int"? '
         <EditText
          android:id="@+id/et'.ucwords($value['COLUMN_NAME']).'"
          android:layout_width="match_parent"
          android:layout_height="wrap_content"
          android:ems="10"
         '.$max.'
          android:hint="Enter '.ucwords($value['COLUMN_COMMENT']).'"
          android:inputType="number" />
       '
        :
           '
        <EditText
          android:id="@+id/et'.ucwords($value['COLUMN_NAME']).'"
          android:layout_width="match_parent"
          android:layout_height="wrap_content"
          android:ems="10"
          '.$max.'
          android:hint="Enter '.ucwords($value['COLUMN_COMMENT']).'"
          android:inputType="text" />
       '
        ;

            echo "<pre>";
            echo htmlspecialchars('
        <TextView
          android:id="@+id/tv'.ucwords($value['COLUMN_NAME']).'"
          android:layout_width="match_parent"
          android:layout_height="wrap_content"
          android:text="'.ucwords($value['COLUMN_COMMENT']).'" />
 '.$formFiled);
            
            
        }
        }
        echo  htmlspecialchars('
        <Button
          android:id="@+id/btnSave"
          android:layout_width="match_parent"
          android:layout_height="wrap_content"
          android:text="Save" />
        ');
        
        echo  htmlspecialchars('
        <Button
          android:id="@+id/btnClose"
          android:layout_width="match_parent"
          android:layout_height="wrap_content"
          android:text="Close" />
        ');
        echo "</pre>";

        echo "<hr>";
      echo "<h1>ANDROID JAVA Code</h1>";
      echo "<h1>Class Definition (Create new class)</h1>";
      $classVariable="";
      $localVaraible="";
      $properties="";
      $getter="";
      $setter="";
      foreach ($result as $value){
        if (
          $value['COLUMN_NAME']!="createdAt"  &&  $value['COLUMN_NAME']!="createdBy"  && $value['COLUMN_NAME']!="createdFrom"&&
          $value['COLUMN_NAME']!="modifiedAt" && $value['COLUMN_NAME']!="modifiedBy" && $value['COLUMN_NAME']!="modifiedFrom"&&
          $value['COLUMN_NAME']!="deletedAt" && $value['COLUMN_NAME']!="deletedBy" && $value['COLUMN_NAME']!="deletedFrom" && $value['COLUMN_NAME']!="deleted" && $value['COLUMN_NAME']!="status"
        ) {
            $classVariable=$classVariable.$value['COLUMN_NAME'].", ";
            $localVaraible=$localVaraible."String ".$value['COLUMN_NAME'].", ";
            $properties=$properties."this.".$value['COLUMN_NAME']." = ".$value['COLUMN_NAME'].";\n &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            $getter=$getter.
            "public String get".ucwords($value['COLUMN_NAME'])."() {
            return this.".$value['COLUMN_NAME'].";
        }\n\n &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        $setter=$setter.
            "public void set".ucwords($value['COLUMN_NAME'])."() {
            this.".$value['COLUMN_NAME']." = ".$value['COLUMN_NAME'].";
        }\n\n &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        }
       
       }
       $classVariable= rtrim($classVariable, ', ');
       $localVaraible= rtrim($localVaraible, ', ');
       
      echo "<pre>
      public class $className {

        private String $classVariable;
        
        public $className($localVaraible) {
          $properties
        }
    
        //getter and setter
        $getter
        $setter
    }
      </pre>";
      echo "<hr>";

      


      echo "//The view objects<br>";
      echo "private EditText ";
      $variable="";
      foreach ($result as $value){
        if (
          $value['COLUMN_NAME']!="createdAt"  &&  $value['COLUMN_NAME']!="createdBy"  && $value['COLUMN_NAME']!="createdFrom"&&
          $value['COLUMN_NAME']!="modifiedAt" && $value['COLUMN_NAME']!="modifiedBy" && $value['COLUMN_NAME']!="modifiedFrom"&&
          $value['COLUMN_NAME']!="deletedAt" && $value['COLUMN_NAME']!="deletedBy" && $value['COLUMN_NAME']!="deletedFrom" && $value['COLUMN_NAME']!="deleted" && $value['COLUMN_NAME']!="status"
        ) {
            $variable=$variable."et".ucwords($value['COLUMN_NAME']).", ";
        }
       
       }
       echo rtrim($variable, ', ').";<br>";
       echo "private Button btnSave, btnClose;<br><br>";

       echo "//initializing view objects<br> ";
       foreach ($result as $value){
        if (
          $value['COLUMN_NAME']!="createdAt"  &&  $value['COLUMN_NAME']!="createdBy"  && $value['COLUMN_NAME']!="createdFrom"&&
          $value['COLUMN_NAME']!="modifiedAt" && $value['COLUMN_NAME']!="modifiedBy" && $value['COLUMN_NAME']!="modifiedFrom"&&
          $value['COLUMN_NAME']!="deletedAt" && $value['COLUMN_NAME']!="deletedBy" && $value['COLUMN_NAME']!="deletedFrom" && $value['COLUMN_NAME']!="deleted" && $value['COLUMN_NAME']!="status"
        ) {
            
        echo "et".ucwords($value['COLUMN_NAME'])." = findViewById(R.id.et".ucwords($value['COLUMN_NAME']).");<br>";
        }
       
       }
       echo "btnSave =findViewById(R.id.btnSave);<br>";
       echo "btnClose =findViewById(R.id.btnClose);<br><br>";
       echo "btnSave.setOnClickListener(this);<br>";
       echo "btnClose.setOnClickListener(this);<br><br>";
       echo "<pre>
@Override
public void onClick(View v) {
  switch (v.getId()) {
    case R.id.btnSave:
        // onClick save button
        break;
    case R.id.btnClose:
        // onClick close button
        finish();
        break;
    default:
        break;
  }
}
       </pre>";
    }
}
