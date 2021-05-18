<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CalculatorTest
 *
 * @author i.mouzouri
 */
require_once '..\..\Calculator.php';
class CalculatorTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var \RemoteWebDriver
	 */
	protected $object;
	
	
	/**
     * Generated from @assert (1, 1) == 2.
     */
	
    public function testAdd()
    {
        $this->assertEquals(2,$this->object->add(1, 1));
    }

}
