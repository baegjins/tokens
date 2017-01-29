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
use Alt3\Tokens\Adapters\RandomBytesAdapter;

class RandomBytesAdapterTest extends BaseTestCase
{
    /**
     * Make sure we are testing against expected default values
     *
     * @return void
     */
    public function testDefaultConfig()
    {
        $adapter = new RandomBytesAdapter();

        $this->assertSame(32, $adapter::DEFAULT_LENGTH);
    }

    /**
     * Test successful token creation.
     *
     * return void
     */
    public function testSuccess()
    {
        // make sure constructor `null` argument uses default length
        $adapter = new RandomBytesAdapter();
        $this->assertAttributeEquals(32, 'length', $adapter);

        // make sure constructor integer argument ends up as property value
        $adapter = new RandomBytesAdapter(10);
        $this->assertAttributeEquals(10, 'length', $adapter);

        // make sure token is generated using the given length
        $result = $adapter->generate();
        $this->assertSame(10, strlen($result));

        // make sure bytes token is generated when passing second argument
        $adapter = new RandomBytesAdapter(10, true);
        $result = $adapter->generate();
        $this->assertSame(5, strlen($result)); // bytes are half the size of eventual string token
    }

    /**
     * Make sure the adapter constructor does not accept a string.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The RandomBytesAdapter length argument only accepts integers
     */
    public function testConstructorFailWithStringArgument()
    {
        new RandomBytesAdapter('some-string');
    }

    /**
     * Make sure the adapter constructor does not accept a boolean.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The RandomBytesAdapter length argument only accepts integers
     */
    public function testConstructorFailWithBooleanArgument()
    {
        new RandomBytesAdapter(false);
    }

    /**
     * Make sure the adapter constructor does not accept an array.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The RandomBytesAdapter length argument only accepts integers
     */
    public function testConstructorFailWithArrayArgument()
    {
        new RandomBytesAdapter(['a' => 'b']);
    }

    /**
     * Make sure the adapter constructor does not accept an object.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The RandomBytesAdapter length argument only accepts integers
     */
    public function testConstructorFailWithObjectArgument()
    {
        $object = (object)['a' => 'b'];
        $this->assertTrue(is_object($object));
        new RandomBytesAdapter($object);
    }
}
