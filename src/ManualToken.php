<?php
/**
 * Tokens: Framework agnostic PHP library for generating (secure) token objects.
 *
 * @copyright  Copyright 2017 (c) ALT3 B.V. (https://alt3.io)
 * @author     bravo-kernel (https://github.com/bravo-kernel)
 * @license    MIT license (http://www.opensource.org/licenses/mit-license.html)
 */
namespace Alt3\Tokens;

use Alt3\Tokens\Adapters\ManualAdapter;

/**
 * Convenience class for generating tokens using the ManualAdapter.
 */
class ManualToken extends Token
{
    /**
     * Constructor MUST use the exact same arguments as the Adapter.
     *
     * @param string $token Token value
     */
    public function __construct($token)
    {
        parent::__construct(new ManualAdapter($token));
    }
}
