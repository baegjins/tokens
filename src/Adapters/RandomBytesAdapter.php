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
 * Adapter class for generating cryptographically secure tokens suitable
 * for e.g. salts, keys and initialization vectors.  Generates the tokens:
 *
 * - using `random_bytes()` on PHP 7.x
 * - using (not to be trusted) `openssl_random_pseudo_bytes()` on PHP 5.x
 *   unless the secure polyfill composer package `random_compat` is installed
 *
 * @see https://github.com/paragonie/random_compat
 */
class RandomBytesAdapter implements AdapterInterface
{
    const DEFAULT_LENGTH = 32;

    /**
     * @var int Token length
     */
    protected $length;

    /**
     * @var array Hash with PHP version and function used to generate the token
     */
    protected $function;

    /**
     * @var string Generated token as byte string
     */
    protected $bytes;

    /**
     * Constructor.
     *
     * @param int $length Token length
     * @param bool $bytes True for byte string token, false for string token
     */
    public function __construct($length = null, $bytes = false)
    {
        if ($length !== null && !is_int($length)) {
            throw new InvalidArgumentException('The RandomBytesAdapter length argument only accepts integers');
        }
        $this->setLength($length);

        $this->bytes = $bytes;
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
                'PHP 5.x native' => 'openssl_random_pseudo_bytes'
            ];

            $bytes = openssl_random_pseudo_bytes($this->length / 2);

            if ($this->bytes === true) {
                return $bytes;
            }

            return bin2hex($bytes);
        }

        // PHP 7.x
        if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
            $this->function = [
                'PHP 7.x' => 'random_bytes'
            ];
        }

        // PHP 5.x with random_compat polyfill
        if (version_compare(PHP_VERSION, '7.0.0', '<')) {
            $this->function = [
                'PHP 5.x paragonie/random_compat' => 'random_bytes'
            ];

            require_once($randomCompat);
        }

        // Handle token generating the PHP 7.x way
        try {
            $bytes = random_bytes($this->length / 2);
        } catch (TypeError $e) {
            throw new Exception('Unexpected TypeError whilst generating the RandomBytes token: ' . $e->getMessage());
        } catch (Error $e) {
            throw new Exception('Unexpected Error whilst generating the RandomBytes token: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception('Could not generate RandomBytes token due to failing CSPRNG: ' . $e->getMessage());
        }

        if ($this->bytes === true) {
            return $bytes;
        }

        return bin2hex($bytes);
    }

    /**
     * Sets the length of generated token.
     *
     * @param int $length Token length
     * @throws \InvalidArgumentException
     * @return void
     */
    protected function setLength($length)
    {
        if ($length === null) {
            $length = self::DEFAULT_LENGTH;
        }

        $this->length = $length;
    }
}
