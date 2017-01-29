<?php
/**
 * Tokens: Framework agnostic PHP library for generating (secure) token objects.
 *
 * @copyright  Copyright 2017 (c) ALT3 B.V. (https://alt3.io)
 * @author     bravo-kernel (https://github.com/bravo-kernel)
 * @license    MIT license (http://www.opensource.org/licenses/mit-license.html)
 */
namespace Alt3\Tokens\Adapters;

use Exception;
use InvalidArgumentException;

/**
 * Adapter class for generating cryptographically random integers suitable
 * for e.g. SMS pin codes. Generates the tokens:
 *
 * - using `random_int()` on PHP 7.x
 * - using (not to be trusted) `mt_rand()` on PHP 5.x unless the secure
 *   polyfill composer package `random_compat` is installed
 *
 * @see https://github.com/paragonie/random_compat
 */
class RandomIntAdapter implements AdapterInterface
{
    /**
     * @var int The min value used for generating the token
     */
    protected $min;

    /**
     * @var int The max value used for generating the token
     */
    protected $max;

    /**
     * @var array Hash with PHP version and function used to generate the token
     */
    protected $function;

    /**
     * Constructor.
     *
     * @param int $min The lowest value to be returned (PHP_INT_MIN or higher)
     * @param int $max The highest value to be returned (less or equal to PHP_INT_MAX)
     */
    public function __construct($min, $max)
    {
        if (!is_int($min)) {
            throw new InvalidArgumentException('RandomIntAdapter min argument only accepts an integer');
        }

        if (!is_int($max)) {
            throw new InvalidArgumentException('RandomIntAdapter max argument only accepts an integer');
        }

        $this->min = $min;
        $this->max = $max;
    }

    /**
     * Interface required method responsible for generating the token.
     *
     * @return string
     */
    public function generate()
    {
        $randomCompat = join(DIRECTORY_SEPARATOR, [
            dirname(dirname(__DIR__)), 'vendor', 'paragonie', 'random_compat', 'lib', 'random.php'
        ]);

        // PHP 5.x native
        if (version_compare(PHP_VERSION, '7.0.0', '<') && !file_exists($randomCompat)) {
            $this->function = [
                'PHP 5.x native' => 'mt_rand'
            ];

            return mt_rand($this->min, $this->max);
        }

        // PHP 7.x
        if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
            $this->function = [
                'PHP 7.x' => 'random_int'
            ];
        }

        // PHP 5.x with random_compat polyfill
        if (version_compare(PHP_VERSION, '7.0.0', '<')) {
            $this->function = [
                'PHP 5.x paragonie/random_compat' => 'random_int'
            ];

            require_once($randomCompat);
        }

        // Handle token generating the PHP 7.x way
        try {
            $token = random_int($this->min, $this->max);
        } catch (TypeError $e) {
            throw new Exception('Unexpected TypeError whilst generating the RandomInt token: ' . $e->getMessage());
        } catch (Error $e) {
            throw new Exception('Unexpected Error whilst generating the RandomInt token: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception('Could not generate RandomInt token due to failing CSPRNG: ' . $e->getMessage());
        }

        return $token;
    }
}
