<?php

use App\Calculation;

class CalculationModelTest extends TestCase
{
    
    public $Calculation = false;
    /**
     * Setting up the Calculation Class to be used in the whole class
     * 
     * @return void
     */

    public function setUp(): void {
        parent::setUp();

        $this->Calculation = new Calculation();
    } 

    /**
     * Trying to create an empty Calculation. 
     *
     * @return void
     */
    public function testIsCalculationEmpty(){
        $expectedError = [
            'error' => [
                'status' => 400,
                'title' => 'Empty calculation',
                'detail' => 'The "elements" parameter is a empty array'
            ]
        ];
        $this->assertArraySubset($expectedError, $this->Calculation->calculate([]));
    }

    /**
     * Trying to create a Calculation with a parameter different than an array. 
     *
     * @return void
     */
    public function testIsElementNotAnArray(){
        $expectedError = [
            'error' => [
                'status' => 400,
                'title' => 'Parameter is not an array', 
                'detail' => 'The "elements" parameter is not an array'
            ]
        ];
        $this->assertArraySubset($expectedError, $this->Calculation->calculate(''));
        $this->assertArraySubset($expectedError, $this->Calculation->calculate(500));
    }

    /**
     * Trying to create a Calculation with invalid elements. 
     *
     * @return void
     */
    public function testTryingToCalculateWithInvalidElements(){
        $expectedError = [
            'error' => [
                'status' => 400,
                'title' => 'Invalid Elements in the calculation', 
                'detail' => 'The "elements" parameter has invalid elements.'
            ]
        ];

        $this->assertArraySubset($expectedError, $this->Calculation->calculate(['a','+','a']));
        $this->assertArraySubset($expectedError, $this->Calculation->calculate(['1','+','a']));
        $this->assertArraySubset($expectedError, $this->Calculation->calculate(['1','^','1']));
        $this->assertArraySubset($expectedError, $this->Calculation->calculate(['¹','+','1']));
        $this->assertArraySubset($expectedError, $this->Calculation->calculate(['1','+','1','-','1','+','2','c']));

        // Those are important because as soon as a "()", "*" and "/" are implemented, this test should fail
        $this->assertArraySubset($expectedError, $this->Calculation->calculate(['1','*','1']));
        $this->assertArraySubset($expectedError, $this->Calculation->calculate(['1','/','1']));
        $this->assertArraySubset($expectedError, $this->Calculation->calculate(['(','1','+','1',')']));
    }

    /**
     * Trying to create a Calculation with invalid order of elements. 
     *
     * @return void
     */
    public function testInvalidOrderOfElementsTest()
    {
        $expectedError = [
            'error' => [
                'status' => 400,
                'title' => 'Elements of the calculation are in not a proper order', 
                'detail' => 'Some elements are out of order, Try to review the calculation you are trying to do'
            ]
        ];
        
        $this->assertArraySubset($expectedError, $this->Calculation->calculate(['+','1','+']));
        $this->assertArraySubset($expectedError, $this->Calculation->calculate(['-','1','+']));
        $this->assertArraySubset($expectedError, $this->Calculation->calculate(['1','1','+']));
        $this->assertArraySubset($expectedError, $this->Calculation->calculate(['1','+','1','+']));
        $this->assertArraySubset($expectedError, $this->Calculation->calculate(['1','+','1','1','+','1']));
        $this->assertArraySubset($expectedError, $this->Calculation->calculate(['1','+','1','+','+','1']));
    }
    
    /**
     * Trying to create a valid sum 
     *
     * @return void
     */
    public function testSum() {
        
        $this->assertEquals(2, $this->Calculation->calculate(['1','+','1']));
        $this->assertEquals(3, $this->Calculation->calculate(['1','+','1', '+', '1']));
        $this->assertEquals(3, $this->Calculation->calculate(['1.5','+','1.5']));
        $this->assertEquals(1.95, $this->Calculation->calculate(['1','+','0.95']));
    }

    /**
     * Trying to create a valid subtraction 
     *
     * @return void
     */
    public function testSubtraction() {
        
        $this->assertEquals( 0, $this->Calculation->calculate(['1','-','1']));
        $this->assertEquals(-1, $this->Calculation->calculate(['1','-','1', '-', '1']));
        $this->assertEquals( 0, $this->Calculation->calculate(['-1.5','-','-1.5']));
        $this->assertEquals( 0.05, $this->Calculation->calculate(['1','-','0.95']));

    }

    /**
     * Trying to create a valid addition with subtraction  
     *
     * @return void
     */
    public function testAddAndSubtraction() {
    
        $this->assertEquals( 0, $this->Calculation->calculate(['-1','+','1']));
        $this->assertEquals(-1, $this->Calculation->calculate(['1','+','1', '-', '3']));
        $this->assertEquals( 0, $this->Calculation->calculate(['1.5','+','-1.5']));
        $this->assertEquals( 0.05, $this->Calculation->calculate(['1','+','-0.95']));

    }

    /**
     * Trying to save in database some invalid Calculations
     * 
     * @return void
     */
    public function testSaveInvalidCalc() {
        $expectedError = [
            'error' => [
                'status' => 400,
                'title' => 'Empty calculation',
                'detail' => 'The "elements" parameter is a empty array'
            ]
        ];
        $this->assertArraySubset($expectedError, $this->Calculation->calculateAndSave([]));
        $expectedError = [
            'error' => [
                'status' => 400,
                'title' => 'Parameter is not an array', 
                'detail' => 'The "elements" parameter is not an array'
            ]
        ];
        $this->assertArraySubset($expectedError, $this->Calculation->calculateAndSave(''));
        $expectedError = [
            'error' => [
                'status' => 400,
                'title' => 'Invalid Elements in the calculation', 
                'detail' => 'The "elements" parameter has invalid elements.'
            ]
        ];

        $this->assertArraySubset($expectedError, $this->Calculation->calculateAndSave(['a','+','a']));
        $expectedError = [
            'error' => [
                'status' => 400,
                'title' => 'Elements of the calculation are in not a proper order', 
                'detail' => 'Some elements are out of order, Try to review the calculation you are trying to do'
            ]
        ];
        $this->assertArraySubset($expectedError, $this->Calculation->calculateAndSave(['+','1','+']));
    }

     /**
     * Trying to save in database some valid Calculations
     * 
     * @return void
     */
    public function testSaveValidCalc() {

        $expectedResponse = [
            "data" => [
                "type" => "calculation",
                "id" => null,
                "attributes" => [
                    "total" => "0",
                    "created_at" => null
                ],
                "relationships" => [
                    "elements" => [
                        ["id"=> null, "element" => "1"],
                        ["id"=> null, "element" => "+"],
                        ["id"=> null, "element" => "1"],
                    ]
                ]
            ],
        ];
        
        /**
         * Compare if the $expectedResponse and the $response have similiar structures
         * If the valus of the $expectedResponse is != null, then also validate the value
         */

        function isArrayInFormat($expectedResponse, $response) {
            function recursiveItirateArray($arr, $responseElement) {
                foreach($arr as $k => $v) {
                    if(!array_key_exists($k,$responseElement)) return false;
                    if(is_array($v)) return recursiveItirateArray($v, $responseElement[$k]);
                    
                    if($v !== null && $v != $responseElement[$k]) return false; 
                }
                return true;
            }

            if(!is_array($response)) return false;

            return recursiveItirateArray($expectedResponse,$response);
        }

        $this->assertTrue(isArrayInFormat($expectedResponse,$this->Calculation->calculateAndSave(['1','-','1'])));
    }

}
