<?php
use core\Object;

require(dirname(dirname(__FILE__)) . '/setup.php');

// create a sample test-class
class Test extends Object {
	public $definedVariable = 'hello!';
	private $hiddenVariable = 'no!';
}

class ObjectTest extends PHPUnit_Framework_TestCase {
	
	public function testGetterAndSetter() {
		$test = new Test();
		
		// make sure we actually sub-class the Object class
		$this->assertTrue($test instanceof Object);
		
		// let's see if we can overwrite the defined variable
		$test->definedVariable = 'overwritten';
		
		// try to get the defined variable
		$value = $test->definedVariable;
		$this->assertEquals('overwritten', $value);
		
		// let's see if we can overwrite the undefined variable
		$failed = false;
		try {
			$test->hiddenVariable = 'this should fail.';
		} catch (Exception $e) {
			$failed = true;
		}
		$this->assertTrue($failed);
		
		// let's see if we can write to an undefined variable
		$failed = false;
		try {
			$test->doesNotExist = 'ruht roh';
		} catch (Exception $e) {
			$failed = true;
		}
		$this->assertTrue($failed);
		
		// let's see if we can get an undefined variable
		$failed = false;
		try {
			$value = $test->doesNotExist;
		} catch (Exception $e) {
			$failed = true;
		}
		$this->assertTrue($failed);
	}
	
}
