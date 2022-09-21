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

        return $user->save(false) ? $user : null;
    }
}