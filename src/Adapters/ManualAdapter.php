<?php
/**
 * Tokens: Framework agnostic PHP library for generating (secure) token objects.
 *
 * @copyright  Copyright 2017 (c) ALT3 B.V. (https://alt3.io)
 * @author     bravo-kernel (https://github.com/bravo-kernel)
 * @license    MIT license (http://www.opensource.org/licenses/mit-license.html)
 */
namespace Alt3\Tokens\Adapters;

use InvalidArgumentException;

/**
 * Adapter class for generating token objects with a user provided
 * (non-generated) token. Useful for e.g. representing JWT tokens
 * as JSON API resources.
 */
class ManualAdapter implements AdapterInterface
{
    /**
     * @var string Token value
     */
    protected $token;

    /**
     * Constructor.
     *
     * @param string $token Token value
     * @throws \InvalidArgumentException
     */
    public function __construct($token)
    {
        if (!is_string($token)) {
            throw new InvalidArgumentException('ManualAdapter constructor only accepts a string argument');
        }

        $this->token = $token;
    }

    /**
     * Interface required method responsible for generating the token.
     *
     * @return string
     */
    public function generate()
    {
        return $this->token;
    }
}
