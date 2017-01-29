<?php
/**
 * Tokens: Framework agnostic PHP library for generating (secure) token objects.
 *
 * @copyright  Copyright 2017 (c) ALT3 B.V. (https://alt3.io)
 * @author     bravo-kernel (https://github.com/bravo-kernel)
 * @license    MIT license (http://www.opensource.org/licenses/mit-license.html)
 */
namespace Alt3\Tests\TestCase;

use Alt3\Tests\BaseTestCase;
use Alt3\Tokens\Adapters\ManualAdapter;

class ManualAdapterTest extends BaseTestCase
{
    /**
     * Test successful token creation.
     *
     * return void
     */
    public function testSuccess()
    {
        // make sure constructor argument ends up as property value
        $adapter = new ManualAdapter('TEST-STRING');
        $this->assertObjectHasAttribute('token', $adapter);
        $this->assertAttributeEquals('TEST-STRING', 'token', $adapter);

        // make sure token is generated with the expected string
        $this->assertEquals('TEST-STRING', $adapter->generate());
    }

    /**
     * Make sure the adapter constructor does not accept an integer.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage ManualAdapter constructor only accepts a string argument
     */
    public function testConstructorFailWithIntegerArgument()
    {
        new ManualAdapter(123);
    }

    /**
     * Make sure the adapter constructor does not accept a boolean.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage ManualAdapter constructor only accepts a string argument
     */
    public function testConstructorFailWithBooleanArgument()
    {
        new ManualAdapter(false);
    }

    /**
     * Make sure the adapter constructor does not accept an array.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage ManualAdapter constructor only accepts a string argument
     */
    public function testConstructorFailWithArrayArgument()
    {
        new ManualAdapter(['a' => 'b']);
    }

    /**
     * Make sure the adapter constructor does not accept an object.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage ManualAdapter constructor only accepts a string argument
     */
    public function testConstructorFailWithObjectArgument()
    {
        $object = (object)['a' => 'b'];
        $this->assertTrue(is_object($object));
        new ManualAdapter($object);
    }
}
