<?php

namespace shop\entities\user;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use shop\exceptions\NotFoundException;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * user model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 *
 * @property Network[] $networks
 * @property WishlistItem[] $wishlistItems
 */
class User extends ActiveRecord implements IdentityInterface
{
    public const STATUS_DELETED = 0;
    public const STATUS_INACTIVE = 9;
    public const STATUS_ACTIVE = 10;

    public static function create(string $username, string $email, string $password): self
    {
        $user = new self();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword(!empty($password) ? $password : Yii::$app->security->generateRandomString());
        $user->status = self::STATUS_ACTIVE;
        $user->generateAuthKey();
        return $user;
    }

    public static function signup(string $username, string $email, string $password) : self
    {
        $user = new self();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        return $user;
    }

    public static function signupByNetwork($network, $identity) : self
    {
        $user = new self();
        $user->status = self::STATUS_ACTIVE;
        $user->generateAuthKey();
        $user->networks = [Network::create($network, $identity)];

        return $user;
    }

    public function edit(string $username, string $email)
    {
        $this->username = $username;
        $this->email = $email;
        $this->updated_at = time();
    }

    public function attachNetwork($network, $identity): void
    {
        $networks = $this->getNetworks()->all();

        foreach ($networks as $current) {
            if ($current->isFor($network, $identity)) {
                throw new \DomainException('Network is already attached.');
            }
        }

        $networks[] = Network::create($network, $identity);
        $this->networks = $networks;
    }

    public function addToWishList($productId)
    {
        $items = $this->wishlistItems;

        foreach ($items as $item) {
            if ($item->isForProduct($productId)) {
                throw new \DomainException('Item is already added.');
            }
        }

        $items[] = WishlistItem::create($productId);

        $this->wishlistItems = $items;
    }

    public function removeFromWishList($productId)
    {
        $items = $this->wishlistItems;

        foreach ($items as $i => $item) {
            if ($item->isForProduct($productId)) {
                unset($items[$i]);
                $this->wishlistItems = $items;

                return;
            }
        }

        throw new NotFoundException('Item is not found.');
    }

    public function isActive() : bool
    {
        return $this->status == self::isActive();
    }

    public function getNetworks() : ActiveQuery
    {
        return $this->hasMany(Network::class, ['user_id' => 'id']);
    }

    public function getWishlistItems() : ActiveQuery
    {
        return $this->hasMany(WishlistItem::class, ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['networks', 'wishlistItems'],
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @return ActiveRecord|null
     */
    public static function findByNetworkIdentity($network, $identity) : ?self
    {
        return User::find()
            ->joinWith('networks n')
            ->andWhere(['n.network' => $network, 'n.identity' => $identity])
            ->one();
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
