<?php

namespace shop\forms;

use yii\base\Model;
use yii\helpers\ArrayHelper;

abstract class CompositeForm extends Model
{
    private $forms = [];

    abstract protected function internalForms() : array;

    public function load($data, $formName = null) : bool
    {
        $result = parent::load($data, $formName);

        foreach ($this->forms as $name => $form) {
            $result = is_array($form)
                ? Model::loadMultiple($form, $data, $formName == '' ? $name : null)
                : $form->load($data, $formName == '' ? null : $name) && $result;
        }

        return $result;
    }

    public function validate($attributeNames = null, $clearErrors = true) : bool
    {
        $parentNames = $attributeNames !== null
            ? array_filter($attributeNames, 'is_string')
            : null;

        $result = parent::validate($parentNames, $clearErrors);

        foreach ($this->forms as $name => $form) {
            if (is_array($form)) {
                $result = Model::validateMultiple($form) && $result;
            } else {
                $innerNames = $attributeNames !== null
                    ? ArrayHelper::getValue($attributeNames, $name)
                    : null;

                $result = $form->validate($innerNames ?: null, $clearErrors) && $result;
            }
        }

        return $result;
    }

    public function __get($name)
    {
        return $this->forms[$name] ?? parent::__get($name);
    }

    public function __set($name, $value)
    {
        in_array($name, $this->internalForms(), true)
            ? $this->forms[$name] = $value
            : parent::__set($name, $value);
    }

    public function __isset($name)
    {
        return isset($this->forms[$name]) || parent::__isset($name);
    }
}