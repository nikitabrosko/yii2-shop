<?php

namespace shop\entities\behaviors;

use shop\entities\Meta;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\Json;

class MetaBehavior extends Behavior
{
    public $attribute = 'meta';
    public $json_attribute = 'meta_json';

    public function events() : array
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'onAfterFind',
            ActiveRecord::EVENT_BEFORE_INSERT => 'onBeforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'onBeforeSave',
        ];
    }

    public function onAfterFind(Event $event)
    {
        $object = $event->sender;

        $meta = Json::decode($object->getAttribute($this->json_attribute));
        $object->{$this->attribute} = new Meta($meta['title'], $meta['description'], $meta['keywords']);
    }

    public function onBeforeSave(Event $event)
    {
        $object = $event->sender;

        $object->setAttribute($this->json_attribute, Json::encode([
            'title' => $object->{$this->attribute}->title,
            'description' => $object->{$this->attribute}->description,
            'keywords' => $object->{$this->attribute}->keywords,
        ]));
    }
}