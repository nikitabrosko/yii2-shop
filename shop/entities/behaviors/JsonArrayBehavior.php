<?php

namespace shop\entities\behaviors;

use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\Json;

class JsonArrayBehavior extends Behavior
{
    public $attribute = 'array';
    public $json_attribute = 'array_json';

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
        ${$this->attribute} = Json::decode($object->getAttribute($this->json_attribute));

        $object->{$this->attribute} = ${$this->attribute};
    }

    public function onBeforeSave(Event $event)
    {
        $object = $event->sender;

        $object->setAttribute($this->json_attribute, Json::encode($object->{$this->attribute}));
    }
}