<?php

namespace shop\services\shop;

use shop\cart\Cart;
use shop\cart\CartItem;
use shop\entities\shop\DeliveryMethod;
use shop\entities\shop\order\CustomerData;
use shop\entities\shop\order\DeliveryData;
use shop\entities\shop\order\Order;
use shop\entities\shop\order\OrderItem;
use shop\entities\user\User;
use shop\exceptions\NotFoundException;
use shop\forms\shop\order\OrderForm;
use shop\services\manage\TransactionManager;

class OrderService
{
    private $cart;
    private $transaction;

    public function __construct(Cart $cart, TransactionManager $transaction)
    {
        $this->cart = $cart;
        $this->transaction = $transaction;
    }

    public function checkout($userId, OrderForm $form): Order
    {
        $user = $this->getUser($userId);

        $products = [];

        $items = array_map(function (CartItem $item) use ($products) {
            $product = $item->getProduct();
            $product->checkout($item->getModificationId(), $item->getQuantity());
            $products[] = $product;

            return OrderItem::create(
                $product,
                $item->getModificationId(),
                $item->getPrice(),
                $item->getQuantity()
            );
        }, $this->cart->getItems());

        $order = Order::create(
            $user->id,

            new CustomerData(
                $form->customer->phone,
                $form->customer->name
            ),

            $items,
            $this->cart->getCost()->getTotal(),
            $form->note
        );

        $order->setDeliveryInfo(
            $this->getDeliveryMethod($form->delivery->method),

            new DeliveryData(
                $form->delivery->index,
                $form->delivery->address
            )
        );

        $this->transaction->wrap(function () use ($order, $products) {
            $order->save();

            foreach ($products as $product) {
                $product->save();
            }

            $this->cart->clear();
        });

        return $order;
    }

    private function getUser($id) : User
    {
        if (!$user = User::findOne(['id' => $id])) {
            throw new NotFoundException('User not found.');
        }

        return $user;
    }

    private function getDeliveryMethod($id) : DeliveryMethod
    {
        if (!$deliveryMethod = DeliveryMethod::findOne(['id' => $id])) {
            throw new NotFoundException('DeliveryMethod not found.');
        }

        return $deliveryMethod;
    }
}