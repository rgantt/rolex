<?php
namespace rolex;

require_once dirname(__FILE__).'/../lib/profiler.php';

class profiler_test extends \PHPUnit_Framework_TestCase {
	private $rolex = null;
	
	protected function setUp() {
		$this->rolex = new rolex();
	}
	
	protected function tearDown() {
		unset( $this->rolex );
	}
}