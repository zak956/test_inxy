<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User;

    /**
     * @param int $id
     * @return User
     */
    public function find(int $id): User;

    /**
     * @param User $user
     * @return User
     */
    public function save(User $user): User;
}
