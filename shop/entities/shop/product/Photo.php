<?php

namespace shop\entities\shop\product;

use shop\services\WaterMarker;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * @property integer $id
 * @property string $file
 * @property integer $sort
 *
 * @mixin ImageUploadBehavior
 */
class Photo extends ActiveRecord
{
    public static function create(UploadedFile $file) : self
    {
        $photo = new static();
        $photo->file = $file;

        return $photo;
    }

    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    public function isIdEqualTo($id) : bool
    {
        return $this->id == $id;
    }

    public static function tableName() : string
    {
        return '{{%shop_photos}}';
    }

    public function behaviors() : array
    {
        return [
            [
                'class' => ImageUploadBehavior::class,
                'attribute' => 'file',
                'createThumbsOnRequest' => true,
                'filePath' => '@staticRoot/origin/products/[[attribute_product_id]]/[[id]].[[extension]]',
                'fileUrl' => '@static/origin/products/[[attribute_product_id]]/[[id]].[[extension]]',
                'thumbPath' => '@staticRoot/cache/products/[[attribute_product_id]]/[[profile]]_[[id]].[[extension]]',
                'thumbUrl' => '@static/cache/products/[[attribute_product_id]]/[[profile]]_[[id]].[[extension]]',
                'thumbs' => [
                    'admin' => ['width' => 100, 'height' => 70],
                    'thumb' => ['width' => 640, 'height' => 480],
                    'catalog_list' => ['width' => 250, 'height' => 250],
                    'catalog_product_main' => ['processor' => [new WaterMarker(750, 1000, '@frontend/web/img/logo.png'), 'process']],
                    'catalog_product_additional' => ['width' => 66, 'height' => 66],
                    'catalog_origin' => ['processor' => [new WaterMarker(1024, 768, '@frontend/web/img/logo.png'), 'process']],
                    'wishlist' => ['width' => 100, 'height' => 100],
                ],
            ],
        ];
    }
}