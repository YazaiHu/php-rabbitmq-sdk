<?php

namespace Mq\Amqp;

use Mq\ProducerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

/**
 * Class AbstractProducer
 * @package Mq\Amqp
 */
abstract class AbstractProducer extends BaseAmqp implements ProducerInterface
{
    /**
     * @param string $messageBody
     * @param array $options
     * @return AMQPMessage
     */
    public function getMessage($messageBody, $options = [])
    {
        $this->setParameter('delivery_mode', AMQPMessage::DELIVERY_MODE_PERSISTENT);

        $message = new AMQPMessage(
            $messageBody,
            $this->getParameters()
        );

        if (!empty($options)) {
            $message->set('application_headers', new AMQPTable($options));
        }

        return $message;
    }

    /**
     * @param string $messageBody
     * @return mixed
     */
    abstract public function publish($messageBody);
}
