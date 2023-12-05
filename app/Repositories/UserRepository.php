<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(array $data): User
    {
        $user = new User($data);
        $user->save();

        $user->account()->create(['balance' => 0.00]);
        $user->save();

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function find($id): User
    {
        /** @var User */
        return User::query()->find($id);
    }

    /**
     * @inheritDoc
     */
    public function save(User $user): User
    {
        $user->save();

        return $user;
    }
}
