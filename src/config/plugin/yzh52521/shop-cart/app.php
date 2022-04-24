<?php
return [
    'enable'    => true,
    'shop_cart' => [
        'storage' => \yzh52521\ShopCart\storage\SessionStorage::class,
        'event'   => \yzh52521\event\Event::class,
        'table'   => 'shopping_cart',
    ]
];
