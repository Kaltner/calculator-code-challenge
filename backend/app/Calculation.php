<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Calculation extends Model
{
    public $valid_elements = ['+', '-', '.'];
    
    /**
     * $calculation (string)
     * 
     * @return float or array with errors 
     */
    public function calculate($elements) {
        // Errors handlers

        if(!is_array($elements)) return $this->_responseHandler(400,[
            'title' => 'Parameter is not an array', 
            'detail' => 'The "elements" parameter is not an array'
        ]);
        if(empty($elements)) return $this->_responseHandler(400,[
            'title' => 'Empty calculation', 
            'detail' => 'The "elements" parameter is a empty array'
        ]);

        // Check if is there any invalid elements
        $nonValid = [];
        preg_match_all('/[^\d'.implode('', $this->valid_elements).']/', implode('', $elements), $nonValid);

        if(!empty($nonValid[0])) return $this->_responseHandler(400,[
            'title' => 'Invalid Elements in the calculation', 
            'detail' => 'The "elements" parameter has invalid elements.'
        ]);
        // End of errors validators
    
        $finalResult = $this->_calc($elements);

        return  $finalResult;
    }
    
    public function calculateAndSave($elements) {
        // First, calculate the elements
        $calcResult = $this->calculate($elements);

        if(is_array($calcResult)) return $calcResult;

        $this->total = $calcResult;
        $this->save();

        // Save the elements
        $savedElements = [];
        for($i=0; $i < count($elements); $i++)
            $savedElements[] = new CalculationElement(['element' => $elements[$i]]);

        $this->elements()->saveMany($savedElements);

        // Creating response
        $response = [
            "data" => [
                "type" => "calculation",
                "id" => $this->id,
                "attributes" => [
                    "total" => $this->total,
                    "created_at" => null
                ],
                "relationships" => [
                    "elements" => []
                ]
            ],
        ];

        foreach($this->elements as $k => $v) {
            $response["data"]["relationships"]["elements"][] = [
                "id" => $v->id,
                "element" => $v->element
            ];
        }

        return $response;
    }

    public function elements() {
        return $this->hasMany('App\CalculationElement', 'calc_id');
    }

    private function _calc($elem) {
        $result = false;
        $operator = false;
        $invalidOrderError = [
            'title' => 'Elements of the calculation are in not a proper order', 
            'detail' => 'Some elements are out of order, Try to review the calculation you are trying to do'
        ];
        for($i=0; $i < count($elem); $i++) {
            // Errors handlers
            
            if(is_numeric($elem[$i])) {
                // If there is a number as the previous element, but there is no operator, the elements are in a incorrect order
                if($result && $operator === false) return $this->_responseHandler(400, $invalidOrderError);
            }

            if(!is_numeric($elem[$i])) {
                // This will only trigger for the first element if this element is an operator
                if($result === false) return $this->_responseHandler(400, $invalidOrderError);

                // This check if one operator is followed by another operator
                if(isset($elem[$i - 1]) 
                && !is_numeric($elem[$i - 1])) {
                    return $this->_responseHandler(400, $invalidOrderError);
                }
            }
            // End of errors handlers

            // This will only trigger when the first element is a number 
            if(is_numeric($elem[$i]) && $result === false) {
                $result = $elem[$i];
                continue;
            }

            // This will trigger for each operator there is.
            if(!is_numeric($elem[$i]) && $operator === false) {
                $operator = $elem[$i]; 
                continue;
            }

            switch ($operator) {
                case '+':
                    $result = $result + $elem[$i];
                    break;
                case '-':
                    $result = $result - $elem[$i];
                    break;
                default:
                    return $this->_responseHandler(400, [
                        'title' => 'Invalid Elements in the calculation', 
                        'detail' => 'The "elements" parameter has invalid elements.'
                    ]);
                    break;
            }
            // Reset the operator so it can validate errors
            $operator = false;
        }

        // If the last element is an operator, that means the equation might be incomplete. Return error in that case.
        if($operator) return $this->_responseHandler(400, $invalidOrderError);

        return $result;
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