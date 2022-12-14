<?php

namespace shop\forms\manage\shop\order;

use shop\entities\shop\order\Order;
use shop\forms\CompositeForm;

/**
 * @property DeliveryForm $delivery
 * @property CustomerForm $customer
 */
class OrderEditForm extends CompositeForm
{
    public $note;

    public function __construct(Order $order, $config = [])
    {
        $this->note = $order->note;

        $this->delivery = new DeliveryForm($order);
        $this->customer = new CustomerForm($order);

        parent::__construct($config);
    }

    public function rules() : array
    {
        return [
            ['note', 'string'],
        ];
    }

    protected function internalForms(): array
    {
        return ['delivery', 'customer'];
    }
}