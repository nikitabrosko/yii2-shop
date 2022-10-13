<?php

namespace shop\cart;

use shop\cart\cost\calculator\CalculatorInterface;
use shop\cart\cost\Cost;
use shop\cart\storage\StorageInterface;
use shop\exceptions\NotFoundException;

class Cart
{
    /**
     * @var CartItem[]
     */
    private $items;
    private $storage;
    private $calculator;

    public function __construct(StorageInterface $storage, CalculatorInterface $calculator)
    {
        $this->storage = $storage;
        $this->calculator = $calculator;
        $this->loadItems();
    }

    public function getItems() : array
    {
        $this->loadItems();

        return $this->items;
    }

    public function getAmount() : int
    {
        $this->loadItems();

        return count($this->items);
    }

    public function getCost() : Cost
    {
        $this->loadItems();

        return $this->calculator->getCost($this->items);
    }

    public function getWeight() : int
    {
        $this->loadItems();

        return array_sum(array_map(function (CartItem $item) {
            return $item->getWeight();
        }, $this->items));
    }

    public function add(CartItem $cartItem)
    {
        $this->loadItems();

        foreach ($this->items as $i => $item) {
            if ($item->getId() == $cartItem->getId()) {
                $item->changeQuantity($item->getQuantity() + $cartItem->getQuantity());
                $this->saveItems();

                return;
            }
        }

        $this->items[] = $cartItem;

        $this->saveItems();
    }

    public function set($id, $quantity)
    {
        $this->loadItems();

        foreach ($this->items as $i => $item) {
            if ($item->getId() == $id) {
                $this->items[$i]->changeQuantity($quantity);

                $this->saveItems();

                return;
            }
        }

        throw new NotFoundException('Item not found.');
    }

    public function remove($id)
    {
        $this->loadItems();

        foreach ($this->items as $i => $item) {
            if ($item->getId() == $id) {
                unset($this->items[$i]);
            }
        }

        $this->saveItems();
    }

    public function clear()
    {
        $this->items = [];

        $this->saveItems();
    }

    private function loadItems()
    {
        if ($this->items === null) {
            $this->items = $this->storage->load();
        }
    }

    private function saveItems()
    {
        $this->storage->save($this->items);
    }
}