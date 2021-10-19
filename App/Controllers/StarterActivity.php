<?php
namespace App\Controllers;

use Core\Controller;
use Core\View;
use App\Models\Starter;
use libs\URL;
use libs\Json;
use libs\Header;
use libs\Request;
use libs\Response;
use libs\Validation;
/**
 * Home controller
 *
 * PHP version 7.3
 */
class StarterActivity extends Controller
{
    /**
     * Before filter
     *
     * @return void
     */
    protected function before()
    {
        //echo "(before) ";
        //return false;
      
    }

    /**
     * After filter
     *
     * @return void
     */
    protected function after()
    {
        //echo " (after)";

    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('activity_starter/index.html', [
            'title'    => 'Starter',
            'javascript' => 'activity_starter/index.js',
            'style'=>'activity_starter/index.css',
            'records'=>1
        ]);
    }

    public function showAction()
    {
        View::renderTemplate('activity_starter/show.html', [
            'title'    => 'Show',
            'javascript' => 'activity_starter/show.js',
            'style'=>'activity_starter/show.css',
            'records'=>1
        ]);
    }

    public function uploadPhotoAction()
    {
        $data=Json::writable();
        Starter::setOnUploadFileListener();       
    }

    public function api(Request $request)
    {
        $starter=new Starter();
        if(Request::GET()){
            // $response=$recruiter->all(true); 
            // echo $response->toJSON();  

            if (URL::hasQuery()) {
                $response=$starter->get(URL::toAndOperator());
            } else {
                $response=$starter->all(true);
            }
            echo $response->toJSON();
        }
        if(Request::POST()){
           //$this->store($request);
           Validation::check($request,[
               'name'=>[
                   'required'=>true,
                   'max'=>100
               ],
               'address'=>[
                   'required'=>true,
                   'max'=>100
               ],
           ]);

           if (Validation::isPassed()) {
               $this->store($request);
           } else {
               print_r(Validation::getErrors());
           }
           
        }
        if(Request::PUT()){
            //echo "PUT";
            Validation::check($request,[
                'name'=>[
                    'required'=>true,
                    'max'=>100
                ],
                'address'=>[
                    'required'=>true,
                    'max'=>100
                ],
            ]);
 
            if (Validation::isPassed()) {
                $this->update($request);
            } else {
                print_r(Validation::getErrors());
            }
            
        }
        if(Request::DELETE()){
            //echo "DELETE";
            $this->delete($request);
            //print_r($response->toJSON());
        }
       
    }


    public function create()
    {
        
    }
    public function generate()
    {
        $starter=new Starter();
        echo  $starter->generateContent();
    }
    public function datatable()
    {
        $starter=new Starter();
        echo  $starter->datatable();
    }
    public function store(Request $request)
    {
        // var_dump($request);
        // echo "<hr>";
        $starter=new Starter();
        $starter->name=$request->input["name"];
        $starter->address=$request->input["address"];
        $response=$starter->save(); 
        echo $response->toJSON();
        //Header::setLocation("apii"); 
    }
    public function showa(Request $request)
    {
        $starter=new Starter();
        $response=$starter->get("id=".$request->input["id"]);
        print_r($response->toJSON());
    }

    public function edit(Request $request)
    {
        $starter=new Starter();
        $starter->get("id=".$request->input["id"]);
    }

    public function update(Request $request)
    {
        $starter=new Starter();
        $starter->name=$request->input["name"];
        $starter->address=$request->input["address"];
        $response=$starter->update("id=".$request->input["id"]);
        echo $response->toJSON();
    }

    public function delete(Request $request)
    {
        $starter=new Starter();
       $response=$starter->delete("id=".$request->input["id"]);
       echo($response->toJSON());
        
    }
    public function uploadAction(Request $request)
    {
        $starter=new Starter();
        $starter->file=$request->file['file'];
        $response=$starter->upload("document");
        if($response->success){
            echo "done";
            echo $response->filename;
            // //to store or update  call the method 
            // $this->store($request); OR $this->update($request);
        }
       // echo json_encode($response);
    }
}
