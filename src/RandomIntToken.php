<?php
/**
 * Tokens: Framework agnostic PHP library for generating (secure) token objects.
 *
 * @copyright  Copyright 2017 (c) ALT3 B.V. (https://alt3.io)
 * @author     bravo-kernel (https://github.com/bravo-kernel)
 * @license    MIT license (http://www.opensource.org/licenses/mit-license.html)
 */
namespace Alt3\Tokens;

use Alt3\Tokens\Adapters\RandomIntAdapter;

/**
 * Convenience class for generating tokens using the RandomIntAdapter.
 */
class RandomIntToken extends Token
{

    /**
     * Constructor MUST use the exact same arguments as the Adapter.
     *
     * @param int $min The lowest value to be returned (PHP_INT_MIN or higher)
     * @param int $max The highest value to be returned (less or equal to PHP_INT_MAX)
     */
    public function __construct($min, $max)
    {
        parent::__construct(new RandomIntAdapter($min, $max));
    }
}
