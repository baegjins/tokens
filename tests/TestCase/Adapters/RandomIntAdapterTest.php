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
use Alt3\Tokens\Adapters\RandomIntAdapter;

class RandomIntAdapterTest extends BaseTestCase
{
    /**
     * Test successful token creation.
     *
     * return void
     */
    public function testSuccess()
    {
        $adapter = new RandomIntAdapter(10, 20);
        $this->assertAttributeEquals(10, 'min', $adapter);
        $this->assertAttributeEquals(20, 'max', $adapter);

        $tokenValue = $adapter->generate();
        $this->assertTrue(is_numeric($tokenValue));
        $this->assertSame(2, strlen($tokenValue));
    }

    /**
     * Make sure first constructor argument does not accept a non-string.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage RandomIntAdapter min argument only accepts an integer
     */
    public function testConstructorFailWithNonStringFirstArgument()
    {
        new RandomIntAdapter('some-string', 10);
    }

    /**
     * Make sure second constructor argument does not accept a non-string.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage RandomIntAdapter max argument only accepts an integer
     */
    public function testConstructorFailWithNonStringSecondArgument()
    {
        new RandomIntAdapter(10, 'some-string');
    }
}
