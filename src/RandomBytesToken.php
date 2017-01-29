<?php
/**
 * Tokens: Framework agnostic PHP library for generating (secure) token objects.
 *
 * @copyright  Copyright 2017 (c) ALT3 B.V. (https://alt3.io)
 * @author     bravo-kernel (https://github.com/bravo-kernel)
 * @license    MIT license (http://www.opensource.org/licenses/mit-license.html)
 */
namespace Alt3\Tokens;

use Alt3\Tokens\Adapters\RandomBytesAdapter;

/**
 * Convenience class for generating tokens using the RandomBytesAdapter.
 */
class RandomBytesToken extends Token
{

    /**
     * Constructor MUST use the exact same arguments as the Adapter.
     *
     * @param int $length Token length
     * @param bool $bytes True for byte string token, false for string token
     */
    public function __construct($length = null, $bytes = false)
    {
        parent::__construct(new RandomBytesAdapter($length, $bytes));
    }
}
