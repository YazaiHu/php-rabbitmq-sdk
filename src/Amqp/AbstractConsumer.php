<?php

namespace Mq\Amqp;

use Exception;
use Mq\ConsumerInterface;

/**
 * Class AbstractConsumer
 * @package Mq\Amqp
 */
abstract class AbstractConsumer extends BaseAmqp implements ConsumerInterface
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @param callable $callback
     * @return $this
     * @throws Exception
     */
    public function setCallback($callback)
    {
        if (is_callable($callback) === false) {
            throw new Exception("Callback $callback is not callable");
        }

        $this->callback = $callback;

        return $this;
    }

    /**
     * @param int $number
     * @return mixed
     */
    abstract public function consume($number);
}
