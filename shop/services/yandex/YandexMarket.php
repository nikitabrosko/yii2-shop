<?php

namespace shop\services\yandex;

use shop\entities\shop\DeliveryMethod;
use shop\readModels\shop\CategoryReadRepository;
use shop\readModels\shop\DeliveryMethodReadRepository;
use shop\readModels\shop\ProductReadRepository;
use yii\helpers\Html;

class YandexMarket
{
    private $shop;
    private $categories;
    private $products;
    private $deliveryMethods;

    public function __construct(ShopInfo $shop,
                                CategoryReadRepository $categories,
                                ProductReadRepository $products,
                                DeliveryMethodReadRepository $deliveryMethods)
    {
        $this->shop = $shop;
        $this->categories = $categories;
        $this->products = $products;
        $this->deliveryMethods = $deliveryMethods;
    }

    public function generate(callable $productUrlGenerator) : string
    {
        ob_start();

        $writer = new \XMLWriter();
        $writer->openURI('php://output');

        $writer->startDocument('1.0', 'UTF-8');
        $writer->startDTD('yml_catalog SYSTEM "shops.dtd"');
        $writer->endDTD();

        // yml_catalog start
        $writer->startElement('yml_catalog');
        $writer->writeAttribute('date', date('Y-m-d H:i'));

        // shop start
        $writer->startElement('shop');
        $writer->writeElement('name', Html::encode($this->shop->name));
        $writer->writeElement('company', Html::encode($this->shop->company));
        $writer->writeElement('url', Html::encode($this->shop->url));

        // currencies start
        $writer->startElement('currencies');

        // currency start
        $writer->startElement('currency');
        $writer->writeAttribute('id', 'RUR');
        $writer->writeAttribute('rate', 1);

        // currency end
        $writer->endElement();

        // currencies end
        $writer->endElement();

        // categories start
        $writer->startElement('categories');

        foreach ($this->categories->getAll() as $category) {
            // category start
            $writer->startElement('category');

            $writer->writeAttribute('id', $category->id);

            if (($parent = $category->parent) && !$parent->isRoot()) {
                $writer->writeAttribute('parentId', $parent->id);
            }

            $writer->writeRaw(Html::encode($category->name));

            // category end
            $writer->endElement();
        }

        // categories end
        $writer->endElement();

        // offers start
        $writer->startElement('offers');

        $deliveries = $this->deliveryMethods->getAll();

        foreach ($this->products->getAllIterator() as $product) {
            // offer start
            $writer->startElement('offer');

            $writer->writeAttribute('id', $product->id);
            $writer->writeAttribute('type', 'vendor.model');
            $writer->writeAttribute('available', $product->isAvailable() ? 'true' : 'false');

            $writer->writeElement('url', Html::encode($productUrlGenerator($product)));
            $writer->writeElement('price', $product->price_new);
            $writer->writeElement('currencyId', 'RUR');
            $writer->writeElement('categoryId', $product->category_id);

            $available = array_filter($deliveries, function (DeliveryMethod $method) use ($product) {
                return $method->isAvailableForWeight($product->weight);
            });

            if ($available) {
                $writer->writeElement('delivery', 'true');
                $writer->writeElement('local_delivery_cost', max(array_map(function (DeliveryMethod $method) {
                    return $method->cost;
                }, $available)));
            } else {
                $writer->writeElement('delivery', 'false');
            }

            $writer->writeElement('vendor', Html::encode($product->brand->name));
            $writer->writeElement('model', Html::encode($product->code));
            $writer->writeElement('description', Html::encode(strip_tags($product->description)));

            foreach ($product->values as $value) {
                if (!empty($value->value)) {
                    $writer->startElement('param');
                    $writer->writeAttribute('name', $value->characteristic->name);
                    $writer->text($value->value);
                    $writer->endElement();
                }
            }

            // offer end
            $writer->endElement();
        }

        // offers end
        $writer->endElement();

        // shop end
        $writer->fullEndElement();

        // yml_catalog end
        $writer->fullEndElement();

        $writer->endDocument();

        return ob_get_clean();
    }
}