<?php
/**
 * Tokens: Framework agnostic PHP library for generating (secure) token objects.
 *
 * @copyright  Copyright 2017 (c) ALT3 B.V. (https://alt3.io)
 * @author     bravo-kernel (https://github.com/bravo-kernel)
 * @license    MIT license (http://www.opensource.org/licenses/mit-license.html)
 */
namespace Alt3\Tests;

use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    /**
     * Tear down test.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }
}
