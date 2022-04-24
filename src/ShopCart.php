<?php

namespace yzh52521\ShopCart;


class ShopCart
{
    protected static $_instance = null;

    public static function instance()
    {
        if (!static::$_instance) {
            $config            = config('plugin.yzh52521.shop-cart.app.shop_cart');
            static::$_instance = new \yzh52521\ShopCart\Cart($config['storage'], null);
        }
        return static::$_instance;
    }


    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return static::instance()->{$name}(... $arguments);
    }
}
