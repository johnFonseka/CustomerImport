<?php
/**
 * Class DataTest
 * @package Jf\CustomerImport\Helper
 *
 * @author John Fonseka <shan4max@gmail.com>
 * @date 2022-05-13 09:06
 */

namespace Jf\CustomerImport\Test\Unit;

use PHPUnit\Framework\TestCase;

class DataTest extends TestCase
{

    private object $data;

    protected function setUp(): void
    {
        parent::setUp();
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->data = $objectManager->getObject('\Jf\CustomerImport\Helper\Data');
    }

    /**
     * Non testable due to module is not loading when we're just accessing Data class
     */
//    public function testGetProfileClass()
//    {
//
//        $command = 'sample-csv';
//        $return = $this->data->getProfileClass($command);
//        $this->assertNotNull($return, 'Returned null');
//        $this->assertIsObject($return, 'Did not return an object');
//        $this->assertInstanceOf('ProfileInterface', $return, 'Class is not a instance of "Profile interface"');
//    }

    public function testGetClassNameFromProfileArg()
    {
        $command = 'sample-csv';
        $return = $this->data->getClassNameFromProfileArg($command);
        $this->assertEquals('SampleCsv', $return, 'Invalid profile class name');

        $command = 'sample-json';
        $return = $this->data->getClassNameFromProfileArg($command);
        $this->assertEquals('SampleJson', $return, 'Invalid profile class name');

        $command = 'url';
        $return = $this->data->getClassNameFromProfileArg($command);
        $this->assertEquals('Url', $return, 'Invalid profile class name');
    }

    public function testGetRandomText()
    {
        $length = 10;
        $return = $this->data->getRandomText($length);
        $this->assertNotNull($return, 'Is null');
        $this->assertIsString($return, "Not string");
        $this->assertEquals($length, strlen($return), 'String is not in expected length');
    }
}
