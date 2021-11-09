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
use libs\Session;

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
            
    }

    /**
     * After filter
     *
     * @return void
     */
    protected function after()
    {
       
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
            'title'    => 'Starter',
            'javascript' => 'activity_starter/show.js',
            'style'=>'activity_starter/show.css',
            'records'=>1
        ]);
    }

    public function detailAction()
    {
        View::renderTemplate('activity_starter/detail.html', [
            'title'    => 'Starter',
            'javascript' => 'activity_starter/detail.js',
            'style'=>'activity_starter/detail.css',
            'records'=>1
        ]);
    }

    public function api(Request $request)
    {
        $starter=new Starter();
        if(Request::GET()){
            if (URL::hasQuery()) {
                $response=$starter->get(URL::toAndOperator());
            } else {
                $response=$starter->all(false,'id DESC');
            }
            echo $response->toJSON();
        }
        if(Request::POST()){
           Validation::check($request,[
            
        'name'=>[
                'required'=>true,
                'max'=>250,
              ],
        'address'=>[
                'required'=>true,
                'max'=>65535,
              ],
        'phone'=>[
                'required'=>true,
                
              ],
        'email'=>[
                'required'=>true,
                'max'=>250,
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
                'id'=>[
                    'required'=>true,
                    
                  ],
            'name'=>[
                    'required'=>true,
                    'max'=>250,
                  ],
            'address'=>[
                    'required'=>true,
                    'max'=>65535,
                  ],
            'phone'=>[
                    'required'=>true,
                    
                  ],
            'email'=>[
                    'required'=>true,
                    'max'=>250,
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
    public function datatable(Request $request)
    {
        $starter=new Starter();
        echo  $starter->datatable($request->input["deleted"]);
    }
    public function store(Request $request)
    {
        $starter=new Starter();
        $starter->name=$request->input["name"];
        $starter->address=$request->input["address"];
        $starter->phone=$request->input["phone"];
        $starter->email=$request->input["email"];
        $starter->createdAt=date('Y-m-d H:i:s');
        $starter->createdBy=Session::get('USERNAME');
        $starter->createdFrom=$_SERVER['REMOTE_ADDR'];
        $response=$starter->save();
        echo $response->toJSON();
    }
    public function find(Request $request)
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
        $starter->phone=$request->input["phone"];
        $starter->email=$request->input["email"];
        $starter->modifiedAt=date('Y-m-d H:i:s');
        $starter->modifiedBy=Session::get('USERNAME');
        $starter->modifiedFrom=$_SERVER['REMOTE_ADDR'];
        $response=$starter->update("id=".$request->input["id"]);
        echo $response->toJSON();
    }

    public function delete(Request $request)
    {
        if(is_array($request->input["id"])){
            $id=implode(',', $request->input["id"]);
         }else{
           $id= $request->input["id"];
         }
        $starter=new Starter();
        if($request->input["tag"]=="delete"){
           $starter->deleted=1;
         }else{
           $starter->deleted=0;
        }
        $starter->deletedAt=date('Y-m-d H:i:s');
        $starter->deletedBy=Session::get('USERNAME');
        $starter->deletedFrom=$_SERVER['REMOTE_ADDR'];
        $response=$starter->update(" id IN ($id) ");
        echo $response->toJSON();
        
    }
}
