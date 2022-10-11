<?php

namespace shop\cart\cost\calculator;

use shop\cart\cost\Cost;
use shop\cart\cost\Discount as DiscountCart;
use shop\entities\shop\Discount as DiscountEntity;

class DynamicCost implements CalculatorInterface
{
    private $cost;

    public function __construct(CalculatorInterface $cost)
    {
        $this->cost = $cost;
    }

    public function getCost(array $items): Cost
    {
        /** @var DiscountEntity[] $discounts */
        $discounts = DiscountEntity::find()->active()->orderBy('sort')->all();

        $cost = $this->cost->getCost($items);

        foreach ($discounts as $discount) {
            if ($discount->isEnabled()) {
                $cost = $cost->withDiscount(new DiscountCart($cost->getOrigin() * ($discount->percent / 100), $discount->name));
            }
        }

        return $cost;
    }
}