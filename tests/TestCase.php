<?php

namespace Vyke\Tests;
/**
 * Vyke (c) Copyright 
 *
 *
 * @package    Vyke\Tests\TestCase
 * @version    1.0.0
 * @author     Learnsty Inc.
 * @license    MIT License
 * @copyright  (c) 2016, Learnsty Inc.
 * @link       http://dev.learnsty.com/
 */


use Mockery as m;
use PHPUnit_Framework_TestCase;

class TestCase extends PHPUnit_Framework_TestCase {
    
    protected $mockObjects;

    protected $mockClassNames;

    protected $config;

    /**
     * Constructor
     *
     * Gets all config and mocks ready
     *
     * @param array $mockClassNames
     * @param array $testingConfig
     *
     */

    public function __construct(array $mockClassNames, array $testingConfig = array()){
        
         $this->config = $testingConfig; #unitTesting = true

         $this->mockClassNames = $mockClassNames;

         $this->mockObjects = array();

         parent::__construct();
        
    }

    /**
     * Setup test dependencies.
     *
     * @param void
     * @return void
     */
    public function setUp(){

        foreach ($this->mockClasses as $index => $className) {
             
             $this->mockObjects[$className] = m::mock($className); 

        }  

    }

    /**
     * Close mockery.
     *
     * @param void
     * @return void
     */
    public function tearDown()
    {
        m::close();
    }

}


?>