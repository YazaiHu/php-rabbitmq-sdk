<?php

namespace Mq;

/**
 * Interface ProducerInterface
 * @package Mq
 */
interface ProducerInterface
{
    /**
     * @param string $messageBody
     * @return mixed
     */
    public function publish($messageBody);
}
