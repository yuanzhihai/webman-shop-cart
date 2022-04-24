<?php

namespace yzh52521\ShopCart\storage;


use support\Cache;

class CacheStorage implements Storage
{
    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        Cache::set($key, $value);
    }

    /**
     * @param $key
     * @param $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return Cache::get($key, $default);
    }

    /**
     * @param $key
     */
    public function forget($key)
    {
        Cache::delete($key);
    }
}
