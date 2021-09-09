<?php
namespace App\Controllers;

use Core\Controller;
use Core\View;
use App\Models\Customer;
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
class CustomerActivity extends Controller
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
        View::renderTemplate('activity_customer/index.html', [
            'title'    => 'Customer',
            'javascript' => 'activity_customer/index.js',
            'style'=>'activity_customer/index.css',
            'records'=>1
        ]);
    }

    public function showAction()
    {
        View::renderTemplate('activity_customer/show.html', [
            'title'    => 'Customer',
            'javascript' => 'activity_customer/show.js',
            'style'=>'activity_customer/show.css',
            'records'=>1
        ]);
    }

    public function detailAction()
    {
        View::renderTemplate('activity_customer/detail.html', [
            'title'    => 'Customer',
            'javascript' => 'activity_customer/detail.js',
            'style'=>'activity_customer/detail.css',
            'records'=>1
        ]);
    }

    public function api(Request $request)
    {
        $customer=new Customer();
        if(Request::GET()){
            if (URL::hasQuery()) {
                $response=$customer->get(URL::toAndOperator());
            } else {
                $response=$customer->all(true);
            }
            echo $response->toJSON();
        }
        if(Request::POST()){
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
    public function store(Request $request)
    {
        $customer=new Customer();
        $starter->name=$request->input["name"];
        $starter->address=$request->input["address"];
        $response=$customer->save(); 
        echo $response->toJSON();
    }
    public function find(Request $request)
    {
        $customer=new Customer();
        $response=$customer->get("id=".$request->input["id"]);
        print_r($response->toJSON());
    }

    public function edit(Request $request)
    {
        $customer=new Customer();
        $starter->get("id=".$request->input["id"]);
    }

    public function update(Request $request)
    {
        $customer=new Customer();
        $starter->name=$request->input["name"];
        $starter->address=$request->input["address"];
        $response=$customer->update("id=".$request->input["id"]);
        echo $response->toJSON();
    }

    public function delete(Request $request)
    {
       $customer=new Customer();
       $response=$customer->delete("id=".$request->input["id"]);
       echo($response->toJSON());
        
    }
}
