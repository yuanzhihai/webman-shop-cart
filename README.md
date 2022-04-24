# webman-shop-cart

购物车在电商场景中基本是必须的一个模块


## 安装

```
composer require yzh52521/webman-shop-cart
```

## 用法

### 选择 Storage

您可以更改数据存储在 `config/plugin/yzh52521/shop-cart/app.php` 配置文件.

```php
session

'storage' => \yzh52521\ShopCart\storage\SessionStorage::class,

datadate 

'storage' => \yzh52521\ShopCart\storage\DatabaseStorage::class,  //tp-orm
or
'storage' => \yzh52521\ShopCart\storage\LaravelDatabaseStorage::class, // laravel

cache

'storage' => \yzh52521\ShopCart\storage\CacheStorage::class,
```

如果更改数据存储如果使用数据库存储，则需要创建数据表：

```sql
CREATE TABLE `shopping_cart`
(
    `key`         varchar(255) CHARACTER SET utf8 NOT NULL,
    `__raw_id`    varchar(255) CHARACTER SET utf8 NOT NULL,
    `guard`       varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `user_id`     int                                      DEFAULT NULL,
    `id`          int                             NOT NULL,
    `name`        varchar(255) CHARACTER SET utf8 NOT NULL,
    `qty`         int                             NOT NULL,
    `price`       decimal(8, 2)                   NOT NULL,
    `total`       decimal(8, 2)                   NOT NULL,
    `__model`     varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `type`        varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `status`      varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `attributes`  text CHARACTER SET utf8,
    `create_time` timestamp                       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `update_time` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`key`, `__raw_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 添加到购物车

add item

```php
Item | null ShopCart::add(
                    string | int $id,
                    string $name,
                    int $quantity,
                    int | float $price
                    [, array $attributes = []]
                 );
```

**example:**

```php
$row = ShopCart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
// Item:
//    id       => 37
//    name     => 'Item name'
//    qty      => 5
//    price    => 100.00
//    color    => 'red'
//    size     => 'M'
//    total    => 500.00
//    __raw_id => '8a48aa7c8e5202841ddaf767bb4d10da'
$rawId = $row->rawId();// get __raw_id
$row->qty; // 5
...
```

### 更新购物车

Update the specified item。

```php
Item ShopCart::update(string $rawId, int $quantity);
Item ShopCart::update(string $rawId, array $arrtibutes);
```

**example:**

```php
ShopCart::update('8a48aa7c8e5202841ddaf767bb4d10da', ['name' => 'New item name');
// or only update quantity
ShopCart::update('8a48aa7c8e5202841ddaf767bb4d10da', 5);
```

### 获取购物车中所有商品

Get all the items.

```php
Collection ShopCart::all();
```

**example:**

```php
$items = ShopCart::all();
```

### 获取一个商品

Get the specified item.

```php
Item ShopCart::get(string $rawId);
```

**example:**

```php
$item = ShopCart::get('8a48aa7c8e5202841ddaf767bb4d10da');
```

### 删除商品

Remove the specified item by raw ID.

```php
boolean ShopCart::remove(string $rawId);
```

**example:**

```php
ShopCart::remove('8a48aa7c8e5202841ddaf767bb4d10da');
```

### 清理购物车

Clean Shopping Cart.

```php
boolean ShopCart::destroy();
boolean ShopCart::clean(); // alias of destroy();
```

**example:**

```php
ShopCart::destroy();// or Cart::clean();
```

### 购物车总价格

Returns the total of all items.

```php
int | float ShopCart::total(); // alias of totalPrice();
int | float ShopCart::totalPrice();
```

**example:**

```php
$total = ShopCart::total();
// or
$total = ShopCart::totalPrice();
```

### 购物车商品个数

`Return the number of rows`.

```php
int ShopCart::countRows();
```

**example:**

```php
ShopCart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
ShopCart::add(37, 'Item name', 1, 100.00, ['color' => 'red', 'size' => 'M']);
ShopCart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
ShopCart::add(127, 'foobar', 15, 100.00, ['color' => 'green', 'size' => 'S']);
$rows = ShopCart::countRows(); // 2
```

### 购物车商品数量

Returns the quantity of all items

```php
int ShopCart::count($totalItems = true);
```

`$totalItems` : When `false`,will return the number of rows.

**example:**

```php
ShopCart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
ShopCart::add(37, 'Item name', 1, 100.00, ['color' => 'red', 'size' => 'M']);
ShopCart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
$count = ShopCart::count(); // 11 (5+1+5)
```

### 搜索商品

Search items by property.

```php
Collection ShopCart::search(array $conditions);
```

**example:**

```php
$items = ShopCart::search(['color' => 'red']);
$items = ShopCart::search(['name' => 'Item name']);
$items = ShopCart::search(['qty' => 10]);
```

### 检查购物车是否为空

```php
bool ShopCart::isEmpty();
```

### 指定关联的商品模型

Specifies the associated model of item.

```php
Cart ShopCart::associate(string $modelName);
```

**example:**

session
```php
ShopCart::associate('app\model\Goods');
$item = ShopCart::get('8a48aa7c8e5202841ddaf767bb4d10da');
$item->goods->name; // $item->goods is instanceof 'app\model\Goods'
```
database/cache
```php
ShopCart::associate('app\model\Goods');
ShopCart::name('web.1'); //The cart name like cart.{guard}.{user_id}： cart.api.1
$item = ShopCart::get('8a48aa7c8e5202841ddaf767bb4d10da');
$item->goods->name; // $item->goods is instanceof 'app\model\Goods'
```



# 购物车商品

properties of `yzh52521\ShopCart\Item`:

- `id`       - 商品ID
- `name`     - 商品名称
- `qty`      - 商品数量
- `price`    - 商品单价
- `total`    - 商品总价.
- `__raw_id` - 唯一ID.
- `__model`  - 模型关联的名称.
- ...自定义属性.

方法：

- `rawId()` - Return the raw ID of item.

## 事件

| 　事件名          | 参数                    |
|------------------|-----------------------|
| `cart.adding`    | ($attributes, $cart); |
| `cart.added`     | ($attributes, $cart); |
| `cart.updating`  | ($row, $cart);        |
| `cart.updated`   | ($row, $cart);        |
| `cart.removing`  | ($row, $cart);        |
| `cart.removed`   | ($row, $cart);        |
| `cart.destroying`| ($cart);              |
| `cart.destroyed` | ($cart);              |

您可以轻松处理这些事件，例如：
```php

Event::listen('cart.adding', function($attributes, $cart){
    // code
});
```
# License

MIT