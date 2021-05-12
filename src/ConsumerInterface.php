<?php

namespace Mq;

/**
 * Interface ConsumerInterface
 * @package Mq
 */
interface ConsumerInterface
{
    /**
     * @param callable $callback
     * @return mixed
     */
    public function setCallback($callback);

    /**
     * @param integer $number
     * @return mixed
     */
    public function consume($number);
}
