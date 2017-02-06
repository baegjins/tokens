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
use Alt3\Tokens\Token;
use SebastianBergmann\PeekAndPoke\Proxy;

class TokenTest extends BaseTestCase
{
    /**
     * Make sure we are testing against expected default values
     *
     * @return void
     */
    public function testDefaultConfig()
    {
        $this->assertSame('+3 days', Token::DEFAULT_LIFETIME);
    }

    /**
     * Test setCategory()
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Category modifier must be a string
     */
    public function testSetCategory()
    {
        $proxy = new Proxy(new Token(new ManualAdapter('dummy')));
        $proxy->setCategory('some-category');
        $this->assertSame('some-category', $proxy->category);

        // throw the exception
        $proxy->setCategory(123);
    }

    /**
     * Test setLifetime()
     */
    public function testSetLifetime()
    {
        $proxy = new Proxy(new Token(new ManualAdapter('dummy')));
        $proxy ->setLifetime('+1 year');
        $this->assertSame('+1 year', $proxy->lifetime);
    }

    /**
     * Make sure setLifetime() throws an exception when passing `null`
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Lifetime modifier cannot be null
     */
    public function testSetLifetimeExceptionWithNullArgument()
    {
        $token = new Token(new ManualAdapter('dummy'));
        $token->setLifetime(null);
    }

    /**
     * Make sure setLifetime() throws an exception when passing a non-string
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Lifetime modifier must be a string
     */
    public function testSetLifetimeExceptionWithNonStringArgument()
    {
        $token = new Token(new ManualAdapter('dummy'));
        $token->setLifetime(['some' => 'array']);
    }

    /**
     * Test setPayload()
     */
    public function testSetPayload()
    {
        $proxy = new Proxy(new Token(new ManualAdapter('dummy')));
        $proxy->setPayload('dummy-payload');
        $this->assertSame('dummy-payload', $proxy->payload);
    }

    /**
     * Test getToken()
     */
    public function testGetToken()
    {
        $proxy = new Proxy(new Token(new ManualAdapter('dummy')));
        $this->assertSame('dummy', $proxy->token);
        $this->assertSame('dummy', $proxy->getToken());
    }

    /**
     * Test getCategory()
     */
    public function testGetCategory()
    {
        $proxy = new Proxy(new Token(new ManualAdapter('dummy')));
        $proxy->setCategory('dummy-category');
        $this->assertSame('dummy-category', $proxy->category);
        $this->assertSame('dummy-category', $proxy->getCategory());
    }

    /**
     * Test getLifetime()
     */
    public function testGetLifetime()
    {
        $proxy = new Proxy(new Token(new ManualAdapter('dummy')));
        $this->assertSame('+3 days', $proxy->lifetime);
        $this->assertSame('+3 days', $proxy->getLifetime());
    }

    /**
     * Test getPayload()
     */
    public function testGetPayload()
    {
        $proxy = new Proxy(new Token(new ManualAdapter('dummy')));
        $proxy->setPayload('dummy-payload');
        $this->assertSame('dummy-payload', $proxy->payload);
        $this->assertSame('dummy-payload', $proxy->getPayload());
    }

    /**
     * Test getCreated() and make sure it returns DateTimeImmutable
     */
    public function testGetCreated()
    {
        $proxy = new Proxy(new Token(new ManualAdapter('dummy')));
        $expected = $proxy->created;
        $this->assertSame($expected, $proxy->getCreated());

        $this->assertInstanceOf('DateTimeImmutable', $proxy->created);
    }

    /**
     * Test getCreated() and make sure it returns DateTimeImmutable
     */
    public function testGetExpires()
    {
        $proxy = new Proxy(new Token(new ManualAdapter('dummy')));
        $expected = $proxy->expires;
        $this->assertSame($expected, $proxy->getExpires());

        $this->assertInstanceOf('DateTimeImmutable', $proxy->expires);
    }

    /**
     * Test toArray()
     */
    public function testToArray()
    {
        $token = new Token(new ManualAdapter('dummy'));
        $array = $token->toArray();

        // make sure token properties match (exactly)
        $expectedProperties = [
            'adapter',
            'token',
            'category',
            'payload',
            'lifetime',
            'created',
            'expires'
        ];
        $this->assertSame($expectedProperties, array_keys($array));
    }
}
