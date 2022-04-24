<?php

namespace yzh52521\ShopCart\storage;

use support\Db;
use Tightenco\Collect\Support\Arr;
use Tightenco\Collect\Support\Collection;
use yzh52521\ShopCart\Item;

class LaravelDatabaseStorage implements Storage
{
    /**
     * @var string
     */
    private string $table;


    public function __construct($table)
    {
        $this->table = $table ?? 'shopping_cart';
    }

    /**
     * @var array
     */
    private array $filed = ['__raw_id', 'id', 'name', 'qty', 'price', 'total', '__model', 'type', 'status'];


    /**
     * @param $key
     * @param $values
     * @return mixed|void
     */
    public function set($key, $values)
    {
        if (is_null($values)) {
            $this->forget($key);

            return;
        }

        $rawIds = $values->pluck('__raw_id');

        //Delete the data that has been removed from cart.
        Db::table($this->table)->whereNotIn('__raw_id', $rawIds)->where('key', $key)->delete();

        $keys = explode('.', $key);

        $userId = end($keys);
        $guard  = prev($keys);
        $values = $values->toArray();
        foreach ($values as $value) {
            $item   = Arr::only($value, $this->filed);
            $attr   = json_encode(Arr::except($value, $this->filed));
            $insert = array_merge($item, ['attributes' => $attr, 'key' => $key, 'guard' => $guard, 'user_id' => $userId]);
            if (Db::table($this->table)->where('key', $key)->where('__raw_id', $item['__raw_id'])->first()) {
                Db::table($this->table)->where('key', $key)->where('__raw_id', $item['__raw_id'])
                    ->update(Arr::except($insert, ['key', '__raw_id']));
            } else {
                Db::table($this->table)->insert($insert);
            }
        }
    }

    /**
     * @param $key
     * @param null $default
     *
     * @return Collection
     */
    public function get($key, $default = null)
    {
        $items      = Db::table($this->table)->where('key', $key)->get();
        $collection = [];
        foreach ($items as $item) {
            $item                          = json_decode(json_encode($item), true);
            $attr                          = json_decode($item['attributes'], true);
            $item                          = Arr::only($item, $this->filed);
            $item                          = array_merge($item, $attr);
            $collection[$item['__raw_id']] = new Item($item);
        }
        return new Collection($collection);
    }

    /**
     * @param $key
     * @return mixed|void
     */
    public function forget($key)
    {
        Db::table($this->table)->where('key', $key)->delete();
    }
}
