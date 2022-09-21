<?php

namespace shop\services\auth;

use shop\entities\user\User;

class NetworkService
{
    public function auth($network, $identity) : ?User
    {
        if ($user = User::findByNetworkIdentity($network, $identity)) {
            return $user;
        }

        $user = User::signupByNetwork($network, $identity);

        if (!$user->save()) {
            throw new \DomainException('Sorry, we are unable to sign you up.');
        }

        foreach ($user->networks as $network) {
            $network->user_id = $user->getId();

            $network->save();
        }

        return $user;
    }

    public function attach($id, $network, $identity)
    {
        if (User::findByNetworkIdentity($network, $identity)) {
            throw new \DomainException('Network is already attached.');
        }

        $user = User::findOne([
            'id' => $id,
        ]);

        $user->attachNetwork($network, $identity);

        if (!$user->save()) {
            throw new \DomainException('Sorry, we are unable to sign you up.');
        }

        foreach ($user->networks as $network) {
            $network->user_id = $user->getId();

            $network->save();
        }
    }
}