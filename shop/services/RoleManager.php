<?php

namespace shop\services;

use shop\exceptions\NotFoundException;
use yii\rbac\ManagerInterface;

class RoleManager
{
    private $manager;

    public function __construct(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function assign($userId, $name)
    {
        if (!$role = $this->manager->getRole($name)) {
            throw new NotFoundException('Role "' . $name . '" not found.');
        }

        $this->manager->revokeAll($userId);

        $this->manager->assign($role, $userId);
    }
}