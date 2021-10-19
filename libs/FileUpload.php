<?php
    namespace libs;
    class FileUpload
    {
        public $extension;
        public $absoluteLocation;
        public $relativeLocation;
        public function setExtension($extension)
        {
            $this->extension=$extension;
        }
        public function setLocation($location)
        {
            $this->absoluteLocation=URL_UPLOAD.$location;
            $this->relativeLocation=$location;
        }
        public function uploadSingleFile($media)
        {
              $file=array_keys($media)[0];
            // echo json_encode($media["file"]["name"]);
                if (!is_dir($this->absoluteLocation)) {
                    mkdir($this->absoluteLocation, 0777, true);
                }
                if($media[$file]['name'] && file_exists($this->absoluteLocation.'/'.$media[$file]['name'])){
                    unlink($this->absoluteLocation.'/'.$media[$file]['name']);   
                }
                //Getting actual file name
                $extension=explode("/", $media[$file]['type'])[1];
                $name= md5(uniqid(mt_rand(), true).microtime(true)).".".$extension; 
                //$name =strtoupper(md5(uniqid(mt_rand(), true).microtime(true))); //$file['file']['name'];
                //Getting temporary file name stored in php tmp folder 
               
                $tmp_name = $media[$file]['tmp_name'];
                move_uploaded_file($tmp_name,$this->absoluteLocation.'/'.$name); 
                return $name;          
        }
        public function uploadMultipleFile($file)
        {
                if (!is_dir($this->absoluteLocation)) {
                    mkdir($this->absoluteLocation, 0777, true);
                }
                $count=count($file['file']['tmp_name']);
                for($i=0;$i<$count;$i++){
                    //Getting actual file name
                    $name = $file['file']['name'][$i];
                    //Getting temporary file name stored in php tmp folder 
                    $tmp_name = $file['file']['tmp_name'][$i];
                    move_uploaded_file($tmp_name,$this->absoluteLocation.'/'.$name);
                }
            }
            public static function move($source,$destination)
            {
              copy($source,$destination); 
              unlink($source);         
            }
        public function delete($data)
        {
            if($data['filename'] && file_exists($this->absoluteLocation.'/'.$data['filename'])){
                unlink($this->absoluteLocation.'/'.$data['filename']);
                return 'done' ;  
            }else{
                return 'error';
            }
            // print_r($data['filename']);
        }
    }
?>