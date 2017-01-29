<?php
/**
 * Tokens: Framework agnostic PHP library for generating (secure) token objects.
 *
 * @copyright  Copyright 2017 (c) ALT3 B.V. (https://alt3.io)
 * @author     bravo-kernel (https://github.com/bravo-kernel)
 * @license    MIT license (http://www.opensource.org/licenses/mit-license.html)
 */
namespace Alt3\Tokens\Adapters;

/**
 * Interface AdapterInterface
 *
 */
interface AdapterInterface
{

    /**
     * Returns a newly generated token.
     *
     * @return string
     */
    public function generate();
}
