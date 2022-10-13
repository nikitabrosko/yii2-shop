<?php

namespace shop\services\manage\shop;

use shop\entities\shop\DeliveryMethod;
use shop\entities\shop\order\CustomerData;
use shop\entities\shop\order\DeliveryData;
use shop\entities\shop\order\Order;
use shop\exceptions\NotFoundException;
use shop\forms\manage\shop\order\OrderEditForm;

class OrderManageService
{
    public function edit($id, OrderEditForm $form): void
    {
        $order = $this->getOrder($id);

        $order->edit(
            new CustomerData(
                $form->customer->phone,
                $form->customer->name
            ),
            $form->note
        );

        $order->setDeliveryInfo(
            $this->getDeliveryMethod($form->delivery->method),
            new DeliveryData(
                $form->delivery->index,
                $form->delivery->address
            )
        );

        $order->save();
    }

    public function remove($id): void
    {
        $order = $this->getOrder($id);
        $order->delete();
    }

    private function getOrder($id) : Order
    {
        if (!$order = Order::findOne(['id' => $id])) {
            throw new NotFoundException('Order not found.');
        }

        return $order;
    }

    private function getDeliveryMethod($id) : DeliveryMethod
    {
        if (!$deliveryMethod = DeliveryMethod::findOne(['id' => $id])) {
            throw new NotFoundException('DeliveryMethod not found.');
        }

        return $deliveryMethod;
    }
}