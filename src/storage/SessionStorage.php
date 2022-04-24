<?php

namespace yzh52521\ShopCart\storage;


class SessionStorage implements Storage
{
    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        request()->session()->set($key, $value);
    }

    /**
     * @param $key
     * @param null $default
     */
    public function get($key, $default = null)
    {
        return request()->session()->get($key, $default);
    }

    /**
     * @param $key
     */
    public function forget($key)
    {
        request()->session()->forget($key);
    }
}
