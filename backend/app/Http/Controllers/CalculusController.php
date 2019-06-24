<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Calculation;

class CalculusController extends Controller
{
    public function index(Request $request) {
        $Calculation = new Calculation;

        if($request->has("filter")) {
            foreach($request->input("filter") as $k => $v) {
                $Calculation = $Calculation->where($k, $v);
            }
        }
        $resultCollection = $Calculation->get();


        // Response
        $result = [
            "links" => [
                "self" => $request->fullUrl()
            ],
            "data" => []
        ];

        foreach($resultCollection as $k => $v) {
            $result['data'][$v->id] = [
                "type" => "calculation",
                "id" => $v->id,
                "attributes" => [
                    "total" => $v->total,
                    "created_at" => [
                        "milli" => $v->created_at->valueOf(),
                        "date" => $v->created_at->toDateString()
                    ]
                ],
                "relationships" => [
                    "elements" => []
                ]
            ];

            foreach($v->elements as $kEle => $vEle) {
                $result["data"][$v->id]["relationships"]["elements"][] = [
                    "id" => $vEle->id,
                    "element" => $vEle->element
                ];
            }
        }

        sort($result['data']);

        return $this->response->array($result)->setStatusCode(200);
    }   
    
    public function create(Request $request) {
        $calcInput = $request->input("calculation");

        // Errors handlers
        if(empty($calcInput)) 
            return  $this->response->array($this->_responseHandler(400, [
                'title' => 'Input can\'t be empty', 
                'description' => 'The input "calculation" can\'t be empty'
                ]))->setStatusCode(400);
        
        if(!is_array($calcInput))
            return  $this->response->array($this->_responseHandler(400, [
                'title' => 'Invalid input', 
                'description' => 'The format of the input "calculation" is invalid. It must be an array'
                ]))->setStatusCode(400);
        
        // Check if every element is a string
        if(count(array_filter($calcInput, 'is_string')) != count($calcInput))
            return  $this->response->array($this->_responseHandler(400, [
                'title' => 'Invalid input', 
                'description' => 'The format of the input "calculation" is invalid. All the elements inside the array must be of the string type'
                ]))->setStatusCode(400);
        

        $Calculation = new Calculation;
        $result = $Calculation->calculateAndSave($calcInput);
        
        if(isset($result['error']))
            return  $this->response->array($result)->setStatusCode(400);

        return $this->response->array($result)->setStatusCode(200);
    }

    private function _responseHandler($status, $responseArray) {
        if(!is_int($status)) throw new InvalidArgumentException('The status is not a number');
        if(!is_array($responseArray)) throw new InvalidArgumentException('The erros options are not an array');

        $responseArray['status'] = $status;
        $return = [ 'data' => $responseArray ];
        if($status >= 300) $return = [ 'error' => $responseArray ];
        return $return;
    }
}
