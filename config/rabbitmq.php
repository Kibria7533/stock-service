<?php

return [
    'exchanges' => [
        'stock' => [
            'name' => 'stock.x',
            'type' => 'topic',
            'durable' => true,
            'autoDelete' => false,
            'alternateExchange' => [
                'name' => 'stock.alternate.x',
                'type' => 'fanout',
                'durable' => true,
                'autoDelete' => false,
                'queue' => 'stock.alternate.q',
                'queueDurable' => true,
                'queueAutoDelete' => false,
                'queueMode' => 'lazy',
            ],
            'dlx' => [
                'name' => 'stock.dlx',
                'type' => 'topic',
                'durable' => true,
                'autoDelete' => false
            ],
            'queue' => [
                'checkStock' => [
                    'name' => 'stock.q',
                    'binding' => 'stock',
                    'durable' => true,
                    'autoDelete' => false,
                    'queueMode' => 'lazy',
                    'dlq' => [
                        'name' => 'stock.dlq',
                        'x_message_ttl' => 50000,
                        'durable' => true,
                        'autoDelete' => false,
                        'queueMode' => 'lazy'
                    ],
                ]
            ],
        ],
        'user' => [
            'name' => 'user.x',
            'type' => 'topic',
            'durable' => true,
            'autoDelete' => false,
            'alternateExchange' => [
                'name' => 'user.alternate.x',
                'type' => 'fanout',
                'durable' => true,
                'autoDelete' => false,
                'queue' => 'user.alternate.q',
                'queueDurable' => true,
                'queueAutoDelete' => false,
                'queueMode' => 'lazy',
            ],
            'dlx' => [
                'name' => 'user.dlx',
                'type' => 'topic',
                'durable' => true,
                'autoDelete' => false
            ],
            'queue' => [
                'orderMake' => [
                    'name' => 'user.q',
                    'binding' => 'user',
                    'durable' => true,
                    'autoDelete' => false,
                    'queueMode' => 'lazy',
                    'dlq' => [
                        'name' => 'user.dlq',
                        'x_message_ttl' => 50000,
                        'durable' => true,
                        'autoDelete' => false,
                        'queueMode' => 'lazy'
                    ],
                ]
            ],
        ],
        'payment' => [
            'name' => 'payment.x',
            'type' => 'topic',
            'durable' => true,
            'autoDelete' => false,
            'alternateExchange' => [
                'name' => 'payment.alternate.x',
                'type' => 'fanout',
                'durable' => true,
                'autoDelete' => false,
                'queue' => 'payment.alternate.q',
                'queueDurable' => true,
                'queueAutoDelete' => false,
                'queueMode' => 'lazy',
            ],
            'dlx' => [
                'name' => 'payment.dlx',
                'type' => 'topic',
                'durable' => true,
                'autoDelete' => false
            ],
            'queue' => [
                'makePayment' => [
                    'name' => 'payment.q',
                    'binding' => 'payment',
                    'durable' => true,
                    'autoDelete' => false,
                    'queueMode' => 'lazy',
                    'dlq' => [
                        'name' => 'payment.dlq',
                        'x_message_ttl' => 50000,
                        'durable' => true,
                        'autoDelete' => false,
                        'queueMode' => 'lazy'
                    ],
                ]
            ],
        ]
    ],
    'consume' => 'checkStock.q'
];
