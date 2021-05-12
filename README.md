## 消息队列扩展包

### dev 环境

http://rabbitmq.dev.lanxinka.com/

Username: admin

Password: admin

### 使用说明

建立连接

```php
use PhpAmqpLib\Connection\AMQPLazyConnection;

$connection = new AMQPLazyConnection(
    $config['host'],
    $config['port'],
    $config['user'],
    $config['password']
);
```

发送消息（普通发送）

```php
use Mq\Amqp\Producer;

$producer = new Producer($connection);

// 配置
$producer
    ->setExchangeOptions([
        'name'  => 'msgcenter.light.direct',
        'type'  => 'direct',
    ])
    ->setRoutingKey('msgcenter.light.msg_transfer');

// 发送
$message = json_encode([
    'event' => 'user.addPoint',
    'body'  => [
        'user_id'   => 1,
        'point'     => 666,
    ],
]);
$producer->publish($message);
```

可靠性发送

```php
use Mq\Amqp\Producer;
use PhpAmqpLib\Message\AMQPMessage;

$producer = new Producer($connection);

$channel = $producer->getChannel();

// 设置 ack 成功回调函数
$channel->set_ack_handler(
    function (AMQPMessage $message) {
        echo 'ack ----' . PHP_EOL;
        var_dump($message->body, $message->delivery_info);
    }
);

// 设置 ack 失败回调函数
$channel->set_nack_handler(
    function (AMQPMessage $message) {
        echo 'no ack ----' . PHP_EOL;
    }
);

// 配置
$producer
    ->setExchangeOptions([
        'name'  => 'msgcenter.light.direct',
        'type'  => 'direct',
    ])
    ->setRoutingKey('msgcenter.light.msg_transfer');

// 确认模式
$channel->confirm_select();

// 发送
$producer->publish('111');
$producer->publish('222');

// 等待
$channel->wait_for_pending_acks();
```

消费消息

```php
use Mq\Amqp\Consumer;

$consumer = new Consumer($connection);

// 回调函数
$callback = function($messageBody) {
    var_dump($messageBody);
    return false;
};

// 配置
$consumer
    ->setQos([
        'prefetch_size'     => 0,
        'prefetch_count'    => 2,
        'global'            => true,
    ])
    ->setQueueOptions([
        'name'  => 'msgcenter.light.queue.msg_transfer',
    ])
    ->setCallback($callback)
    // 重试次数，在调用 $callback 后返回 false 或 throwable 后消息重回队列
    ->setMaxRetries(3);

// 消费
$consumer->consume(2);
```
