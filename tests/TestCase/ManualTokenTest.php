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
use Alt3\Tokens\ManualToken;

class ManualTokenTest extends BaseTestCase
{
    /**
     * Test token convenience class
     */
    public function testSuccess()
    {
        new ManualToken('woohoo');
    }
}
