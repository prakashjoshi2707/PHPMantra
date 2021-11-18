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
      protected static function getDB()
      {
          static $db = null;
          if ($db === null) {
              $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' .
            Config::DB_NAME . ';charset=utf8';
              $db=new Database($dsn, Config::DB_USER, Config::DB_PASSWORD);
            
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
              $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                
              // Throw an Exception when an error occurs
              $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          }
        
          return $db;
      }
    
      public function save()
      {
          //echo(substr(strrchr(get_class($this),"\\"),1));
          $table="tbl".strtolower(substr(strrchr(get_class($this), "\\"), 1));
          //print_r((array)$this);
          $db=self::getDB();
          $dataArray=(array)$this;
          $result=$db->insert($table, $dataArray);
          $response=new Response();
          if ($result) {
              $response->success = 1;
              $response->message = "Successfully Added";
              $response->code=201;
              return $response;
          } else {
              $response->success = 0;
              $response->message= "Something went wrong";
              $response->code=400;
              return $response;
          }
      }
      public function update($condition)
      {
          //echo(substr(strrchr(get_class($this),"\\"),1));
          $table="tbl".strtolower(substr(strrchr(get_class($this), "\\"), 1));
          //print_r((array)$this);
          $db=self::getDB();
          $dataArray=(array)$this;
          $result=$db->update($table, $dataArray, $condition);
          $response=new Response();
          if ($result) {
              $response->success = 1;
              $response->message = "Successfully Updated";
              $response->code=201;
              return $response;
          } else {
              $response->success = 0;
              $response->message= "Something went wrong";
              $response->code=400;
              return $response;
          }
      }
      public function delete($condition)
      {
          $table="tbl".strtolower(substr(strrchr(get_class($this), "\\"), 1));
          $db=self::getDB();
          $result=$db->delete("DELETE FROM $table WHERE $condition");
          $response=new Response();
          if ($result) {
              $response->success = 1;
              $response->message = "Successfully Deleted";
              $response->code=201;
              return $response;
          } else {
              $response->success = 0;
              $response->message= "Something went wrong";
              $response->code=400;
              return $response;
          }
      }
      public function get($condition)
      {
      
        //return ($this);
          $table="tbl".strtolower(substr(strrchr(get_class($this), "\\"), 1));
          $db=self::getDB();
          if ($condition==null) {
              $condition=1;
          }
          $result=$db->select("SELECT * FROM $table WHERE $condition");
          $response=new Response();
          if ($result) {
              $response->success = 1;
              $response->records = $result;
              $response->code=201;
              return $response;
          } else {
              $response->success = 0;
              $response->message= "Something went wrong";
              $response->code=400;
              return $response;
          }
      }
      public function getDistinct($column, $condition)
      {
      
        //return ($this);
          $table="tbl".strtolower(substr(strrchr(get_class($this), "\\"), 1));
          $db=self::getDB();
          if ($condition==null) {
              $condition=1;
          }
          $result=$db->select("SELECT DISTINCT($column) FROM $table WHERE $condition");
          $response=new Response();
          if ($result) {
              $response->success = 1;
              $response->records = $result;
              $response->code=201;
              return $response;
          } else {
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
      public function all($pagination=false, $orderBy='id ASC', $records=null)
      {
          $table="tbl".strtolower(substr(strrchr(get_class($this), "\\"), 1));
          $db=self::getDB();
          if ($pagination==false and $records==null) {
              $result=$db->select("SELECT * FROM $table ORDER BY $orderBy ");
              $total=$db->selectCount("SELECT * FROM $table");
              $response=new Response();
              if ($result) {
                  $response->success = 1;
                  $response->records = $result;
                  $response->first=1;
                  $response->last=$total;
                  $response->total=$total;
                  $response->code=201;
                  return $response;
              } else {
                  $response->success = 0;
                  $response->message= "Something went wrong";
                  $response->code=400;
                  return $response;
              }
          } else {
              $result=$db->select("SELECT * FROM $table ORDER BY $orderBy LIMIT 0,10");
              $total=$db->selectCount("SELECT * FROM $table");
              $response=new Response();
              if ($result) {
                  $response->success = 1;
                  $response->records = $result;
                  $response->first=1;
                  $response->last=5;
                  $response->total=$total;
                  $response->code=201;
                  return $response;
              } else {
                  $response->success = 0;
                  $response->message= "Something went wrong";
                  $response->code=400;
                  return $response;
              }
          }
      }
      public function total($condition=null)
      {
          $table="tbl".strtolower(substr(strrchr(get_class($this), "\\"), 1));
          $db=self::getDB();
          if ($condition==null) {
              $total=$db->selectCount("SELECT * FROM $table");
          } else {
              $total=$db->selectCount("SELECT * FROM $table WHERE $condition");
          }
          $response=new Response();
          if ($total) {
              $response->success = 1;
              $response->total=$total;
              $response->code=200;
              return $response;
          } else {
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
          $table="tbl".strtolower(substr(strrchr(get_class($this), "\\"), 1));
          $db=self::getDB();
          if ($condition==null) {
              $condition=1;
          }
          $result=$db->select("SELECT * FROM $table WHERE $condition");
          $response=new Response();
          if ($result) {
              $response->success = 1;
              $response->message= "Available";
              $response->code=201;
              return $response;
          } else {
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
          if ($response) {
              $response->success = 1;
              $response->message = "uploaded";
              $response->filename = $filename;
              $response->code=201;
              return $response;
          } else {
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
          if ($result) {
              $response->success = 1;
              $response->message = "deleted";
              $response->filename = $result;
              $response->code=201;
              return $response;
          } else {
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
          $table="tbl".strtolower(substr(strrchr(get_class($this), "\\"), 1));
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
          $table="tbl".strtolower(substr(strrchr(get_class($this), "\\"), 1));
          $className=(substr(strrchr(get_class($this), "\\"), 1));
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
          foreach ($result as $value) {
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
          foreach ($result as $value) {
              if ($value['COLUMN_NAME']=="createdAt") {
                  echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
                  echo "<br>";
              } elseif ($value['COLUMN_NAME']=="createdBy") {
                  echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=Session::get('USERNAME');";
                  echo "<br>";
              } elseif ($value['COLUMN_NAME']=="createdFrom") {
                  echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
                  echo "<br>";
              } elseif ($value['COLUMN_NAME']=="modifiedAt") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
          // echo "<br>";
              } elseif ($value['COLUMN_NAME']=="modifiedBy") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=Session::get('USERNAME');";
          // echo "<br>";
              } elseif ($value['COLUMN_NAME']=="modifiedFrom") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          // echo "<br>";
              } elseif ($value['COLUMN_NAME']=="deletedAt") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
          // echo "<br>";
              } elseif ($value['COLUMN_NAME']=="deletedBy") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=Session::get('USERNAME');";
          // echo "<br>";
              } elseif ($value['COLUMN_NAME']=="deletedFrom") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          // echo "<br>";
              } elseif ($value['COLUMN_NAME']=="deleted") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          // echo "<br>";
              } elseif ($value['COLUMN_NAME']=="id") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          // echo "<br>";
              } else {
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
          foreach ($result as $value) {
              if ($value['COLUMN_NAME']=="createdAt") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
          // echo "<br>";
              } elseif ($value['COLUMN_NAME']=="createdBy") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=GlobalSession::get('USERNAME');";
          // echo "<br>";
              } elseif ($value['COLUMN_NAME']=="createdFrom") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          // echo "<br>";
              } elseif ($value['COLUMN_NAME']=="modifiedAt") {
                  echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
                  echo "<br>";
              } elseif ($value['COLUMN_NAME']=="modifiedBy") {
                  echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=Session::get('USERNAME');";
                  echo "<br>";
              } elseif ($value['COLUMN_NAME']=="modifiedFrom") {
                  echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
                  echo "<br>";
              } elseif ($value['COLUMN_NAME']=="deletedAt") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
          // echo "<br>";
              } elseif ($value['COLUMN_NAME']=="deletedBy") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=Session::get('USERNAME');";
          // echo "<br>";
              } elseif ($value['COLUMN_NAME']=="deletedFrom") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          // echo "<br>";
              } elseif ($value['COLUMN_NAME']=="deleted") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          // echo "<br>";
              } elseif ($value['COLUMN_NAME']=="id") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          // echo "<br>";
              } else {
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
   
          foreach ($result as $value) {
              if ($value['COLUMN_NAME']=="createdAt") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
          // echo "<br>";
              } elseif ($value['COLUMN_NAME']=="createdBy") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=GlobalSession::get('USERNAME');";
          // echo "<br>";
              } elseif ($value['COLUMN_NAME']=="createdFrom") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          // echo "<br>";
              } elseif ($value['COLUMN_NAME']=="modifiedAt") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
          // echo "<br>";
              } elseif ($value['COLUMN_NAME']=="modifiedBy") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=Session::get('USERNAME');";
          // echo "<br>";
              } elseif ($value['COLUMN_NAME']=="modifiedFrom") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
          // echo "<br>";
              } elseif ($value['COLUMN_NAME']=="deletedAt") {
                  echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=date('Y-m-d H:i:s');";
                  echo "<br>";
              } elseif ($value['COLUMN_NAME']=="deletedBy") {
                  echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=Session::get('USERNAME');";
                  echo "<br>";
              } elseif ($value['COLUMN_NAME']=="deletedFrom") {
                  echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=\$_SERVER['REMOTE_ADDR'];";
                  echo "<br>";
              } elseif ($value['COLUMN_NAME']=="deleted") {
                  // echo "\$".lcfirst($className)."->".$value['COLUMN_NAME']."=1;";
                  // echo "<br>";
                  echo "if(\$request->input[\"tag\"]==\"delete\"){<br>";
                  echo "&nbsp;&nbsp;&nbsp;\$".lcfirst($className)."->deleted=1;<br>";
                  echo "&nbsp;}else{<br>";
                  echo "&nbsp;&nbsp;&nbsp;\$".lcfirst($className)."->deleted=0;<br>";
                  echo "}<br>";
              } else {
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
          echo nl2br(htmlspecialchars(
              '        <th scope="col"><input type="checkbox" id="check-all"></th>
        <th scope="col">#</th>'
          ))."<br>";
          foreach ($result as $value) {
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
              
          foreach ($result as $value) {
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
          foreach ($result as $value) {
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
          echo "<h1>Create First activity named ".$className."Activity</h1>";
          echo "<h1>Create Second activity named ".$className."ListActivity</h1>";
          echo "<h1>Create Third activity named ".$className."DetailActivity</h1>";
          echo "<h1>activity_".strtolower($className).".xml</h1>";
          foreach ($result as $value) {
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
        <!--Progress Button -->
        <androidx.cardview.widget.CardView
          android:id="@+id/btnParentSave"
          android:layout_width="match_parent"
          android:layout_height="50dp"
          app:cardBackgroundColor="#299617"
          app:cardCornerRadius="10dp"
          app:cardElevation="0dp">
          <RelativeLayout
              android:id="@+id/btnSave"
              android:layout_width="match_parent"
              android:layout_height="match_parent"
              android:clickable="true"
              android:focusable="true">

              <TextView
                  android:id="@+id/btnTitle"
                  android:layout_width="wrap_content"
                  android:layout_height="match_parent"
                  android:layout_centerInParent="true"
                  android:layout_marginTop="30dp"
                  android:text="SAVE"
                  android:textColor="#fff"
                  android:textSize="16dp"
                  android:textStyle="bold" />

              <ProgressBar
                  android:id="@+id/btnProgress"
                  android:layout_width="25dp"
                  android:layout_height="25dp"
                  android:layout_centerInParent="true"
                  android:layout_marginTop="15dp"
                  android:layout_marginRight="5dp"
                  android:layout_toLeftOf="@id/btnTitle"
                  android:indeterminateTint="#fff"
                  android:visibility="gone" />

          </RelativeLayout>
    </androidx.cardview.widget.CardView>
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
          echo "<h1>".$className.".java</h1>";
          $classVariable="";
          $localVaraible="";
          $properties="";
          $getter="";
          $setter="";
          foreach ($result as $value) {
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

        public String $classVariable;
        public $className() {}
        public $className($localVaraible) {
          $properties
        }
    
        //getter and setter
        $getter
        $setter
    }
      </pre>";
          echo "<hr>";

      

          echo "<h1>".$className."Activity.java</h1>";
          echo "//The view objects<br>";
          echo "private EditText ";
          $variable="";
          foreach ($result as $value) {
              if (
          $value['COLUMN_NAME']!="createdAt"  &&  $value['COLUMN_NAME']!="createdBy"  && $value['COLUMN_NAME']!="createdFrom"&&
          $value['COLUMN_NAME']!="modifiedAt" && $value['COLUMN_NAME']!="modifiedBy" && $value['COLUMN_NAME']!="modifiedFrom"&&
          $value['COLUMN_NAME']!="deletedAt" && $value['COLUMN_NAME']!="deletedBy" && $value['COLUMN_NAME']!="deletedFrom" && $value['COLUMN_NAME']!="deleted" && $value['COLUMN_NAME']!="status"
        ) {
                  $variable=$variable."et".ucwords($value['COLUMN_NAME']).", ";
              }
          }
          echo rtrim($variable, ', ').";<br>";
          echo "private Button btnClose;<br><br>";
          echo "private $className ".lcfirst($className)."<br><br>";
          echo "//Progress Button
private CardView btnParentSave;
private RelativeLayout btnSave;
private TextView btnTitle;
private ProgressBar btnProgress;
   
Fetch fetch;<br><br>";

          echo "//initializing view objects<br> ";
          foreach ($result as $value) {
              if (
          $value['COLUMN_NAME']!="createdAt"  &&  $value['COLUMN_NAME']!="createdBy"  && $value['COLUMN_NAME']!="createdFrom"&&
          $value['COLUMN_NAME']!="modifiedAt" && $value['COLUMN_NAME']!="modifiedBy" && $value['COLUMN_NAME']!="modifiedFrom"&&
          $value['COLUMN_NAME']!="deletedAt" && $value['COLUMN_NAME']!="deletedBy" && $value['COLUMN_NAME']!="deletedFrom" && $value['COLUMN_NAME']!="deleted" && $value['COLUMN_NAME']!="status"
        ) {
                  echo "et".ucwords($value['COLUMN_NAME'])." = findViewById(R.id.et".ucwords($value['COLUMN_NAME']).");<br>";
              }
          }

          echo "//Progress Button for save<br>";
          echo "btnParentSave = findViewById(R.id.btnParentSave);<br>";
          echo "btnSave = findViewById(R.id.btnSave);<br>";
          echo "btnTitle = findViewById(R.id.btnTitle);<br>";
          echo "btnProgress = findViewById(R.id.btnProgress);<br>";
          echo "btnClose = findViewById(R.id.btnClose);<br>";
          echo "btnSave.setOnClickListener(this);<br>";
          echo "btnClose.setOnClickListener(this);<br><br>";
          $initVaraible="";
          $validation="";
          foreach ($result as $value) {
              if (
          $value['COLUMN_NAME']!="createdAt"  &&  $value['COLUMN_NAME']!="createdBy"  && $value['COLUMN_NAME']!="createdFrom"&&
          $value['COLUMN_NAME']!="modifiedAt" && $value['COLUMN_NAME']!="modifiedBy" && $value['COLUMN_NAME']!="modifiedFrom"&&
          $value['COLUMN_NAME']!="deletedAt" && $value['COLUMN_NAME']!="deletedBy" && $value['COLUMN_NAME']!="deletedFrom" && $value['COLUMN_NAME']!="deleted" && $value['COLUMN_NAME']!="status"
        ) {
                  $declaration=$className.".".strtolower($value['COLUMN_NAME'])."="."et".strtolower($value['COLUMN_NAME']).".getText().toString();";
                  $initVaraible=$initVaraible."\t". $declaration."<br>";
          
                  $columnName=strtolower($value['COLUMN_NAME']);
                  $columnDescription=ucwords($value['COLUMN_COMMENT']);
                  $controlName="et".ucwords($value['COLUMN_NAME']);

                  $validationRule="
        if(Validation.isEmpty($className.".$columnName."))) {
          $controlName.requestFocus();
          $controlName.setError(\"$columnDescription cannot be empty\");
        }";
                  $validation=$validation.$validationRule;
              }
          }
          $apiUrl=URL.strtolower($className)."/api";
          echo "<pre>
@Override
public void onClick(View v) {
  switch (v.getId()) {
    case R.id.btnSave:
        // onClick save button
        $className ".lcfirst($className). "= new $className();
        $initVaraible
        //Convert second if to elseif
        $validation
        else {
          btnProgress.setVisibility(View.VISIBLE);
          btnTitle.setVisibility(View.VISIBLE);
          btnParentSave.setCardBackgroundColor(Color.BLUE);
          btnTitle.setText(\"Please wait...\");
          btnTitle.setTextColor(Color.WHITE);
          btnSave.setEnabled(false);
          fetch=new Fetch(this, \"$apiUrl\", Request.Method.POST, student, new VolleyCallback() {
              @Override
              public void onSuccessResponse(String result) {
                  try {
                      JSONObject jsonObject = new JSONObject(result);
                      if (jsonObject.getInt(\"success\") == 1) {
                          Toast.makeText(MainActivity.this, \"Success\", Toast.LENGTH_SHORT).show();
                          Intent intentShow = new Intent({$className}Activity.this, {$className}ListActivity.class);
                          startActivity(intentShow);
                      } else {
                          btnProgress.setVisibility(View.GONE);
                          btnTitle.setVisibility(View.VISIBLE);
                          btnParentSave.setCardBackgroundColor(Color.parseColor(\"#299617\"));
                          btnTitle.setText(\"SAVE\");
                          btnTitle.setTextColor(Color.WHITE);
                          btnSave.setEnabled(true);
                      }

                  } catch (JSONException e) {
                      e.printStackTrace();
                  }
              }

              @Override
              public void onErrorResponse(String result) {
                  Toast.makeText({$className}Activity.this, \"Please check network connection\", Toast.LENGTH_LONG).show();
                  btnProgress.setVisibility(View.GONE);
                  btnTitle.setVisibility(View.VISIBLE);
                  btnParentSave.setCardBackgroundColor(Color.parseColor(\"#299617\"));
                  btnTitle.setText(\"SAVE\");
                  btnTitle.setTextColor(Color.WHITE);
                  btnSave.setEnabled(true);
              }
          });
        }
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
          echo "<hr>";
   
          echo "<h1>Create new drawable resources named rounded_drawable.xml</h1>";
          echo  htmlspecialchars('
       <shape
          xmlns:android="http://schemas.android.com/apk/res/android"
          android:shape="oval">
          <solid   android:color="#666666"/>
      </shape>
       ');
          echo "<hr>";
   
          echo "<h1>Create new layout->res named ".strtolower($className)."_list_item.xml</h1>";
          echo "<b>Note: Check the following attribute of the view 
       android:layout_below=\"@+id/element\"
       android:layout_toEndOf=\"@+id/element\"
       android:layout_toRightOf=\"@+id/element\"</b>";
          echo "<pre>";
          echo  htmlspecialchars('
       <RelativeLayout xmlns:android="http://schemas.android.com/apk/res/android"
          xmlns:tools="http://schemas.android.com/tools"
          android:id="@+id/rl'.$className.'"
          android:layout_width="match_parent"
          android:layout_height="wrap_content">
          <TextView
              android:id="@+id/tvId"
              android:layout_width="40dp"
              android:layout_height="40dp"
              android:layout_marginTop="10dp"
              android:background="@drawable/rounded_drawable"
              android:gravity="center"
              android:text="Id"
              android:textColor="@android:color/white"
              android:textSize="20sp" />
       ');
          foreach ($result as $value) {
              if (
          $value['COLUMN_NAME']!="createdAt"  &&  $value['COLUMN_NAME']!="createdBy"  && $value['COLUMN_NAME']!="createdFrom"&&
          $value['COLUMN_NAME']!="modifiedAt" && $value['COLUMN_NAME']!="modifiedBy" && $value['COLUMN_NAME']!="modifiedFrom"&&
          $value['COLUMN_NAME']!="deletedAt" && $value['COLUMN_NAME']!="deletedBy" && $value['COLUMN_NAME']!="deletedFrom" && $value['COLUMN_NAME']!="deleted" && $value['COLUMN_NAME']!="status"
        ) {
                  if ($value['COLUMN_NAME']!="id") {
                      echo  htmlspecialchars('
              <TextView
                android:id="@+id/tv'.ucwords($value['COLUMN_NAME']).'"
                android:layout_width="wrap_content"
                android:layout_height="wrap_content"
                android:layout_marginLeft="10dp"
                android:layout_marginStart="10dp"
                android:layout_below="@+id/'.$lastElement.'"
                android:layout_toEndOf="@+id/tvId"
                android:layout_toRightOf="@+id/tvId"
                android:text="'.ucwords($value['COLUMN_NAME']).'"
                android:textSize="16sp"
                android:textStyle="bold" />
              ');
                  }
                  $lastElement='tv'.ucwords($value['COLUMN_NAME']);
              }
          }
       
        
          echo  htmlspecialchars('
              <TextView
                android:id="@+id/tvTime"
                android:layout_width="wrap_content"
                android:layout_height="wrap_content"
                android:layout_alignBaseline="@id/tvName"
                android:layout_alignParentEnd="true"
                android:layout_alignParentRight="true"
                android:text="Time" />
              <ImageView
                android:id="@+id/ivFavorite"
                android:layout_width="wrap_content"
                android:layout_height="wrap_content"
                android:layout_alignParentEnd="true"
                android:layout_alignParentRight="true"
                android:layout_below="@+id/tvTime"
                android:padding="5dp"
                android:src="@drawable/ic_baseline_star_24" />
              <ImageView
                android:id="@+id/ivDetail"
                android:layout_width="wrap_content"
                android:layout_height="wrap_content"
                android:layout_alignParentEnd="true"
                android:layout_alignParentRight="true"
                android:layout_below="@+id/ivFavorite"
                android:padding="5dp"
                android:src="@drawable/ic_baseline_arrow_forward_ios_24" />
        </RelativeLayout>
        ');
          echo "</pre>";
          echo "<hr>";
   
          echo "<h1>Adapter For RecyclerView</h1>";
          echo "<h1>Create new class named ".$className."Adapter.java</h1>";
          echo "<pre>";
          $objectName=strtolower($className);
          $initVaraible="";
          $initVaraible2="";
          $initVaraible3="";
          $initVaraible4="";
          $initVaraible5="";
          $validation="";
          $classVariable='public TextView ';
          foreach ($result as $value) {
              if (
          $value['COLUMN_NAME']!="createdAt"  &&  $value['COLUMN_NAME']!="createdBy"  && $value['COLUMN_NAME']!="createdFrom"&&
          $value['COLUMN_NAME']!="modifiedAt" && $value['COLUMN_NAME']!="modifiedBy" && $value['COLUMN_NAME']!="modifiedFrom"&&
          $value['COLUMN_NAME']!="deletedAt" && $value['COLUMN_NAME']!="deletedBy" && $value['COLUMN_NAME']!="deletedFrom" && $value['COLUMN_NAME']!="deleted" && $value['COLUMN_NAME']!="status"
        ) {
                  $declaration=$className.".".strtolower($value['COLUMN_NAME'])."="."tv".strtolower($value['COLUMN_NAME']).".getText().toString();";
            
                  $declaration="holder.tv".ucwords($value['COLUMN_NAME']).".setText({$objectName}.get".ucwords($value['COLUMN_NAME'])."());";
                  $initVaraible=$initVaraible."\t". $declaration."<br>";
                  $classVariable=$classVariable."tv".ucwords($value['COLUMN_NAME']).", ";

                  $declaration2="this.tv".ucwords($value['COLUMN_NAME'])." = itemView.findViewById(R.id.tv".ucwords($value['COLUMN_NAME']).");";
                  $initVaraible2=$initVaraible2."\t". $declaration2."<br>";

                  $declaration3="{$objectName}.".strtolower($value['COLUMN_NAME'])."=jsonArray.getJSONObject(i).getString(\"".strtolower($value['COLUMN_NAME'])."\")";
                  $initVaraible3=$initVaraible3."\t". $declaration3."<br>";

                  $declaration4="tv".ucwords($value['COLUMN_NAME'])." = findViewById(R.id.tv".ucwords($value['COLUMN_NAME']).");";
                  $initVaraible4=$initVaraible4."\t". $declaration4."<br>";
            
                  $declaration5="tv".ucwords($value['COLUMN_NAME']).".setText({$objectName}.get".ucwords($value['COLUMN_NAME'])."());";
                  ;
                  $initVaraible5=$initVaraible5."\t". $declaration5."<br>";
              }
          }
          $classVariable= rtrim($classVariable, ', ');
          $apiUrl=URL.strtolower($className)."/api";
      
          echo "
      public class {$className}Adapter extends RecyclerView.Adapter<StudentAdapter.ViewHolder> implements Filterable {
        private List<{$className}> list =new ArrayList<>();
        private List<{$className}> listAll=new ArrayList<>();
        private Context context;
        // RecyclerView recyclerView;
        public {$className}Adapter(Context context, List<{$className}> list) {
            this.list = list;
            this.context=context;
            this.listAll=new ArrayList<>(list);
    
        }
        @NonNull
        @Override
        public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
            LayoutInflater layoutInflater = LayoutInflater.from(parent.getContext());
            View listItem= layoutInflater.inflate(R.layout.{$objectName}_list_item, parent, false);
            ViewHolder viewHolder = new ViewHolder(listItem);
            return viewHolder;
        }
    
        @Override
        public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
           {$className} {$objectName}=list.get(position);
              $initVaraible
                
            holder.ivDetail.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    Toast.makeText(view.getContext(),\"click on item: \"+{$objectName}.getId(), Toast.LENGTH_LONG).show();
                    Intent intentDetail = new Intent(context, {$className}DetailActivity.class);
                    //Create the bundle
                    Bundle bundle = new Bundle();
                    //Add your data to bundle
                    bundle.putString(\"ID\", {$objectName}.getId());
                    //Add the bundle to the intent
                    intentDetail.putExtras(bundle);
                    context.startActivity(intentDetail);
                }
            });
            holder.rl{$className}.setOnLongClickListener(new View.OnLongClickListener() {
                @Override
                public boolean onLongClick(View view) {
                    Toast.makeText(view.getContext(),\"click on item: \"+{$objectName}.getId(), Toast.LENGTH_LONG).show();
                    return false;
                }
            });
    
        }
    
        @Override
        public int getItemCount() {
            return list.size();
        }
    
        @Override
        public Filter getFilter() {
            return filter;
        }
        Filter filter=new Filter() {
            //run on background thread
            @Override
            protected FilterResults performFiltering(CharSequence constraint) {
                List<Student> filteredList=new ArrayList<>();
                Log.d(\"FILTER LEN\", String.valueOf(listAll.size()));
                Log.d(\"FILTER LEN\", String.valueOf(list.size()));
    
                if(constraint==null || constraint.length()==0){
                    Log.d(\"FILTER\",\"EMPTY\");
                    filteredList.addAll(listAll);
    
                }else{
                    Log.d(\"FILTER\",\"FILTER\");
                    String filterPattern=constraint.toString().toLowerCase().trim();
                    Log.d(\"FILTER\",filterPattern);
                    for({$className} {$objectName}:listAll){
                        if(student.getName().toLowerCase().contains(filterPattern)){
                            Log.d(\"FILTER CLASS\",{$objectName}.getId());
                            filteredList.add({$objectName});
                        }
                    }
                }
                FilterResults filterResults=new FilterResults();
                filterResults.values=filteredList;
                Log.d(\"FILTER\",\"FILTER-RESULT\");
                Log.d(\"FILTER\",filterResults.toString());
                return filterResults;
            }
            //run on UI thread
            @Override
            protected void publishResults(CharSequence constraint, FilterResults results) {
                list.clear();
                list.addAll((ArrayList< {$className}>) results.values);
                Log.d(\"FILTER\",\"Publish\");
                
                notifyDataSetChanged();
            }
        };
        public class ViewHolder extends RecyclerView.ViewHolder {
            $classVariable;
            public ImageView ivDetail;
            public RelativeLayout rl{$className};
    
            public ViewHolder(View itemView) {
                super(itemView);
                      $initVaraible2
                this.ivDetail=itemView.findViewById(R.id.ivDetail);
                this.rl{$className}= itemView.findViewById(R.id.rl{$className});
    
            }
        }
    }
      ";
          echo "</pre>";
          echo "<hr>";
          echo "<h1>activity_".strtolower($className)."_list.xml</h1>";
          echo "<pre>";
          echo  htmlspecialchars('
      <androidx.swiperefreshlayout.widget.SwipeRefreshLayout
      android:id="@+id/swipeRefreshLayout"
      android:layout_width="match_parent"
      android:layout_height="match_parent"
      app:layout_constraintBottom_toBottomOf="parent"
      app:layout_constraintEnd_toEndOf="parent"
      app:layout_constraintStart_toStartOf="parent"
      app:layout_constraintTop_toTopOf="parent">

          <androidx.recyclerview.widget.RecyclerView
              android:id="@+id/rv'.$className.'"
              android:layout_width="match_parent"
              android:layout_height="match_parent"
              android:layout_marginStart="5dp"
              android:layout_marginEnd="5dp"
              app:layout_constraintBottom_toBottomOf="parent"
              app:layout_constraintEnd_toEndOf="parent"
              app:layout_constraintHorizontal_bias="0.0"
              app:layout_constraintStart_toStartOf="parent"
              app:layout_constraintTop_toTopOf="parent"
              app:layout_constraintVertical_bias="0.0" />
      </androidx.swiperefreshlayout.widget.SwipeRefreshLayout>

      <com.google.android.material.floatingactionbutton.FloatingActionButton
          android:id="@+id/fab"
          android:layout_width="wrap_content"
          android:layout_height="wrap_content"
          android:layout_gravity="bottom|end"
          android:layout_marginEnd="@dimen/fab_margin"
          android:layout_marginBottom="16dp"
          app:layout_constraintRight_toRightOf="parent"
          app:layout_constraintBottom_toBottomOf="parent"
          app:srcCompat="@android:drawable/ic_input_add" />

        <ProgressBar
            android:id="@+id/progressBar"
            style="@android:style/Widget.DeviceDefault.Light.ProgressBar.Large"
            android:layout_width="wrap_content"
            android:layout_height="wrap_content"
            app:layout_constraintBottom_toBottomOf="parent"
            app:layout_constraintLeft_toLeftOf="parent"
            app:layout_constraintRight_toRightOf="parent"
            app:layout_constraintTop_toTopOf="parent" />
      ');
          echo "</pre>";
          echo "<hr>";
     
          echo "<h1>add res->menu then create new file {$objectName}_menu.xml</h1>";
          echo "<pre>";
          echo  htmlspecialchars('
     <menu xmlns:android="http://schemas.android.com/apk/res/android"
      xmlns:app="http://schemas.android.com/apk/res-auto">
        <item android:id="@+id/action_search"
            android:title="Search"
            android:icon="@drawable/ic_baseline_search_24"
            app:showAsAction="always|collapseActionView"
            app:actionViewClass="androidx.appcompat.widget.SearchView"
        />
    </menu>
     ');
          echo "</pre>";
          echo "<hr>";

          echo "<h1>".$className."ListActivity.java</h1>";
          echo "<pre>";
          echo "
      RecyclerView rv{$className};
      StudentAdapter {$objectName}Adapter;
      FloatingActionButton fab;
      ProgressBar progressBar;
      SwipeRefreshLayout swipeRefreshLayout;
      private List< {$className}> {$objectName}List=new ArrayList<>();
  
      Fetch fetch;
      progressBar=findViewById(R.id.progressBar);
       loadData();

        rv{$className} = findViewById(R.id.rv{$className});
        fab = findViewById(R.id.fab);
        {$objectName}Adapter= new {$className}Adapter(this,{$objectName}List);
        rv{$className}.setHasFixedSize(true);
        rv{$className}.setLayoutManager(new LinearLayoutManager(this));
        rv{$className}.addItemDecoration(new DividerItemDecoration({$className}ListActivity.this, DividerItemDecoration.VERTICAL));
        rv{$className}.setAdapter({$objectName}Adapter);

        fab.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View view) {
                Intent intentAdd = new Intent({$className}ListActivity.this, {$className}Activity.class);
                startActivity(intentAdd);
            }
        });

        swipeRefreshLayout=findViewById(R.id.swipeRefreshLayout);
        swipeRefreshLayout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                {$objectName}List.clear();
                loadData();
                swipeRefreshLayout.setRefreshing(false);
            }
        });

    }
    public void loadData(){
        fetch=new Fetch(this, \"{$apiUrl}\", Request.Method.GET, null, new VolleyCallback() {

            @Override
            public void onSuccessResponse(String result) {
                Log.d(\"DATA\",result);
                try {
                    JSONObject jsonObject = new JSONObject(result);
                    if (jsonObject.getInt(\"success\") == 1) {
                        JSONArray jsonArray= jsonObject.getJSONArray(\"records\");

                        Log.d(\"LENGTH\", String.valueOf(jsonArray.length()));
                        for(int i=0;i< jsonArray.length(); i++){

                            {$className} {$objectName} = new {$className}();
                            $initVaraible3
                            {$objectName}List.add({$objectName});
                        }


                    }

                    {$objectName}Adapter.notifyDataSetChanged();
                    progressBar.setVisibility(View.GONE);
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }

            @Override
            public void onErrorResponse(String result) {

            }
        });
    }
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.{$objectName}_menu, menu);
        MenuItem menuItem=menu.findItem(R.id.action_search);
        SearchView searchView= (SearchView) menuItem.getActionView();
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {
                return false;
            }

            @Override
            public boolean onQueryTextChange(String newText) {
                Log.d(\"FILTER\",newText);
                {$objectName}Adapter.getFilter().filter(newText);
                return false;
            }
        });

        return true;
    }
      ";
          echo "</pre>";
          echo "<hr>";
          echo "<h1>activity_".strtolower($className)."_detail.xml</h1>";
          echo "<pre>";
          echo  htmlspecialchars('
      <LinearLayout
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:orientation="vertical">

        <ProgressBar
            android:id="@+id/progressBar"
            style="@android:style/Widget.DeviceDefault.Light.ProgressBar.Large"
            android:layout_width="match_parent"
            android:layout_height="wrap_content" />
      ');
          foreach ($result as $value) {
              if (
          $value['COLUMN_NAME']!="createdAt"  &&  $value['COLUMN_NAME']!="createdBy"  && $value['COLUMN_NAME']!="createdFrom"&&
          $value['COLUMN_NAME']!="modifiedAt" && $value['COLUMN_NAME']!="modifiedBy" && $value['COLUMN_NAME']!="modifiedFrom"&&
          $value['COLUMN_NAME']!="deletedAt" && $value['COLUMN_NAME']!="deletedBy" && $value['COLUMN_NAME']!="deletedFrom" && $value['COLUMN_NAME']!="deleted" && $value['COLUMN_NAME']!="status"
        ) {
                  echo  htmlspecialchars('
            <TextView
            android:id="@+id/tv'.ucwords($value['COLUMN_NAME']).'"
            android:layout_width="wrap_content"
            android:layout_height="wrap_content"
            android:layout_margin="@dimen/text_margin"

              ');
              }
          }
          echo  htmlspecialchars('
        <LinearLayout
            android:layout_width="match_parent"
            android:layout_height="wrap_content"
            android:orientation="horizontal"
            android:weightSum="100"
            >


        <Button
            android:id="@+id/btnEdit"
            android:layout_width="match_parent"
            android:layout_height="wrap_content"
            android:layout_weight="50"
            android:layout_marginRight="2dp"
            android:text="Edit" />

        <Button
            android:id="@+id/btnDelete"
            android:layout_width="match_parent"
            android:layout_height="wrap_content"
            android:layout_weight="50"
            android:text="Delete" />
        </LinearLayout>

    </LinearLayout>
        ');
          echo "<hr>";
          echo "<h1>".$className."DetailActivity.java</h1>";
          echo "<pre>";
          echo "
      $classVariable
      ProgressBar progressBar ;
      public Button btnEdit,btnDelete;
      Fetch fetch;
      $initVaraible4
      
      progressBar=findViewById(R.id.progressBar);
      btnEdit=findViewById(R.id.btnEdit);
      btnDelete=findViewById(R.id.btnDelete);
      btnEdit.setOnClickListener(this);
      btnDelete.setOnClickListener(this);

        Bundle bundle = getIntent().getExtras();
        if (bundle != null) {
            String value = bundle.getString(\"ID\");
            fetch=new Fetch(this, \"{$apiUrl}?id=\"+value, Request.Method.GET, null, new VolleyCallback() {

                @Override
                public void onSuccessResponse(String result) {
                    Log.d(\"DATA\",result);
                    try {
                        JSONObject jsonObject = new JSONObject(result);
                        if (jsonObject.getInt(\"success\") == 1) {
                            JSONArray jsonArray= jsonObject.getJSONArray(\"records\");

                            Log.d(\"LENGTH\", String.valueOf(jsonArray.length()));
                            for(int i=0;i< jsonArray.length();i++){

                                {$className} {$objectName} = new {$className}();
                                $initVaraible3
                                $initVaraible5
                               
                            }
                            progressBar.setVisibility(View.GONE);

                        }
                    } catch (JSONException e) {
                        e.printStackTrace();
                    }
                }

                @Override
                public void onErrorResponse(String result) {

                }
            });
        }
    }

    @Override
    public void onClick(View v) {
        switch (v.getId()) {
            case R.id.btnEdit:
                // onClick close button
                Intent intentEdit = new Intent(ScrollingActivity.this, MainActivity.class);

                startActivity(intentEdit);
                break;
            case R.id.btnDelete:
                // onClick close button
                Intent intentDelete = new Intent(ScrollingActivity.this, MainActivity3.class);
                startActivity(intentDelete);
                break;
            default:
                break;
        }
    }
      ";
      }
  }
