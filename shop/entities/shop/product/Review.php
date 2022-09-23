<?php

namespace shop\entities\shop\product;

use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $created_at
 * @property integer $user_id
 * @property integer $vote
 * @property string $text
 * @property bool $active
 */
class Review extends ActiveRecord
{
    public static function create($userId, $vote, $text) : self
    {
        $review = new static();
        $review->user_id = $userId;
        $review->vote = $vote;
        $review->text = $text;
        $review->created_at = time();
        $review->active = false;

        return $review;
    }

    public function edit($vote = null, $text = null)
    {
        $this->vote = $vote;
        $this->text = $text;
    }

    public function activate()
    {
        $this->active = true;
    }

    public function draft()
    {
        $this->active = false;
    }

    public function isActive() : bool
    {
        return $this->active;
    }

    public function getRating() : int
    {
        return $this->vote;
    }

    public function isIdEqualTo($id) : bool
    {
        return $this->id === $id;
    }

    public static function tableName() : string
    {
        return '{{%shop_reviews}}';
    }
}