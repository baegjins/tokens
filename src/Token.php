<?php
/**
 * Tokens: Framework agnostic PHP library for generating (secure) token objects.
 *
 * @copyright  Copyright 2017 (c) ALT3 B.V. (https://alt3.io)
 * @author     bravo-kernel (https://github.com/bravo-kernel)
 * @license    MIT license (http://www.opensource.org/licenses/mit-license.html)
 */
namespace Alt3\Tokens;

use Alt3\Tokens\Adapters\AdapterInterface;
use Alt3\Tokens\Adapters\RandomBytesAdapter;
use DateTime;
use InvalidArgumentException;

/**
 * Token class responsible for generating the token objects.
 */
class Token
{
    const DEFAULT_LIFETIME = '+3 days';

    /**
     * @var \Alt3\Tokens\Adapters\AdapterInterface
     */
    protected $adapter;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $category;

    /**
     * @var mixed
     */
    protected $payload;

    /**
     * @var string
     */
    protected $lifetime;

    /**
     * @var \DateTimeInterface
     */
    protected $created;

    /**
     * @var string DateTime::ATOM
     */
    protected $expires;

    /**
     * Token constructor.
     *
     * @param \Alt3\Tokens\Adapters\AdapterInterface $adapter Adapter instance
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $this->setToken();
        $this->setCreated();
        $this->setExpires();
    }

    /**
     * Public setter used to specify the token category.
     *
     * @param string $category Any string
     * @return void
     * @see http://php.net/manual/en/datetime.modify.php
     */
    public function setCategory($category)
    {
        if (!is_string($category)) {
            throw new InvalidArgumentException('Category modifier must be a string');
        }

        $this->category = $category;
    }

    /**
     * Public setter used to update the `expires` property by specifying
     * the token lifetime in PHP DateTime::modify format (e.g. '+3 days').
     *
     * @param string $modify Any modifier supported by PHP DateTime::modify
     * @throws \InvalidArgumentException
     * @return void
     * @see http://php.net/manual/en/datetime.modify.php
     */
    public function setLifetime($modify)
    {
        if (empty($modify)) {
            throw new InvalidArgumentException('Lifetime modifier cannot be null');
        }

        if (!is_string($modify)) {
            throw new InvalidArgumentException('Lifetime modifier must be a string');
        }

        $this->lifetime = $modify;

        $this->setExpires();
    }

    /**
     * Public setter used to transport additional information.
     *
     * @param mixed $payload Anything you need to transport.
     * @return void
     * @see http://php.net/manual/en/datetime.modify.php
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Public getter used to retrieve the token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Public getter used to retrieve the token category.
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Public getter used to retrieve the token lifetime.
     *
     * @return string
     */
    public function getLifetime()
    {
        return $this->lifetime;
    }

    /**
     * Public getter used to retrieve the token payload.
     *
     * @return string
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Public getter used to retrieve the date the token was created.
     *
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Public getter used to retrieve the date the token will expire.
     *
     * @return string
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Return token as an array whilst making sure content of the `adapter`
     * and `payload` properties is not changed (to e.g. preserve objects
     * being passed as payload).
     *
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * Sets the 'token' property by generating a toking using the current Adapter.
     *
     * @return void
     */
    protected function setToken()
    {
        $this->token = $this->adapter->generate();
    }

    /**
     * Set the 'created` property.
     *
     * @return void
     */
    protected function setCreated()
    {
        $time = new DateTime;
        $this->created = $time->format(DateTime::ATOM);
    }

    /**
     * Set the 'expires' property by adding lifetime to the 'created'  property.
     *
     * @return void
     */
    protected function setExpires()
    {
        $time = DateTime::createFromFormat(DateTime::ATOM, $this->created);

        if ($this->lifetime === null) {
            $this->lifetime = self::DEFAULT_LIFETIME;
        }

        $time->modify($this->lifetime);
        $this->expires = $time->format(DateTime::ATOM);
    }
}
