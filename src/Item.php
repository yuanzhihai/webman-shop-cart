<?php

namespace yzh52521\ShopCart;


use Tightenco\Collect\Support\Collection;

class Item extends Collection
{
    /**
     * The  model a cart is associated with.
     *
     * @var string
     */
    protected $model;

    /**
     * Magic accessor.
     *
     * @param string $property property name
     *
     * @return mixed
     */
    public function __get($property)
    {
        if ($this->has($property)) {
            return $this->get($property);
        }

        if (!$this->get('__model')) {
            return;
        }

        $model = $this->get('__model');

        $class = explode('\\', $model);

        if (strtolower(end($class)) === $property || 'model' === $property) {
            $model = new $model();
            return $model->find($this->id);
        }
    }

    /**
     * Return the raw ID of item.
     *
     * @return string
     */
    public function rawId()
    {
        return $this->__raw_id;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->rawId();
    }
}
