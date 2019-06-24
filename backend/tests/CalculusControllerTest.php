<?php

class CalculusControllerTest extends TestCase
{
    
    public $headers = ['Accept' => 'application/x.calculator_code_challenge.v1+json'];
    /**
     * Trying to create a new calculation with empty input. 
     *
     * @return void
     */
    public function testCreateEmpty() {
        $expectedResponse = [
            'error' => [
                'status' => 400,
                'title' => 'Input can\'t be empty',
                'description' => 'The input "calculation" can\'t be empty'
            ]
        ];

        $params = [
            'calculation' => []
        ];

        $this->json('POST', '/calculus', $params, $this->headers)
             ->seeJsonEquals($expectedResponse);
        $this->assertEquals(400, $this->response->status());

        // Test with no Parameters
        $params = [];

        $this->json('POST', '/calculus', $params, $this->headers)
             ->seeJsonEquals($expectedResponse);
    
        $this->assertEquals(400, $this->response->status());
    }

    /**
     * Trying to create a new calculation with invalid input.
     *
     * @return void
     */
    public function testCreateInvalidFormat() {
        $expectedResponse = [
            'error' => [
                'status' => 400,
                'title' => 'Invalid input',
                'description' => 'The format of the input "calculation" is invalid. It must be an array'
            ]
        ];

        $params = [
            'calculation' => 'string'
        ];

        $this->json('POST', '/calculus', $params, $this->headers)
             ->seeJsonEquals($expectedResponse);
        $this->assertEquals(400, $this->response->status());

        $expectedResponse['error']['description'] = 'The format of the input "calculation" is invalid. All the elements inside the array must be of the string type';

        $params = [
            'calculation' => ['1', '+', []]
        ];
        
        $this->json('POST', '/calculus', $params, $this->headers)
             ->seeJsonEquals($expectedResponse);
    
        $this->assertEquals(400, $this->response->status());


        $params = [
            'calculation' => ['1', '+', true]
        ];

        $this->json('POST', '/calculus', $params, $this->headers)
             ->seeJsonEquals($expectedResponse);
    
        $this->assertEquals(400, $this->response->status());
    }

    /**
     * Trying to create a calculation with invalid parameters for the calculation.
     *
     * @return void
     */
    public function testCreateInvalidCalculation() {
        $expectedResponse = [
            'error' => [
                'status' => 400,
                'title' => 'Elements of the calculation are in not a proper order', 
                'detail' => 'Some elements are out of order, Try to review the calculation you are trying to do'
            ]
        ];
        
        $params = [
            'calculation' => ['1', '+', '+']
        ];

        $this->json('POST', '/calculus', $params, $this->headers)
             ->seeJsonEquals($expectedResponse);
    
        $this->assertEquals(400, $this->response->status());
    }

    /**
     * Trying to create a valid calculation.
     *
     * @return void
     */
    public function testCreateValidCalculation() {
        $expectedResponse = [
            "data" => [
                "type" => "calculation",
                "attributes" => [
                    "total" => "2",
                ]
            ],
        ];
        
        $params = [
            'calculation' => ['1', '+', '1']
        ];

        $this->json('POST', '/calculus', $params, $this->headers);
    
        $this->seeJson($expectedResponse, $this->response)
             ->assertEquals(200, $this->response->status());
    }

}