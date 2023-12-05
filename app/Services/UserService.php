<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserService
{
    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(protected readonly UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return $this->userRepository->create($data);
    }

    /**
     * @param User $user
     * @param array $data
     * @return User
     */
    public function update(User $user, array $data): User
    {
        foreach ($data as $key => $value) {
            $user->$key = $value;
        }

        return $this->userRepository->save($user);
    }
}
