<?php

namespace App\Repositories\Interfaces;

use App\Models\Account;

interface AccountRepositoryInterface
{
    /**
     * @param Account $account
     * @return bool
     */
    public function save(Account $account): bool;

    /**
     * @param int $id
     * @return Account
     */
    public function findOrFail(int $id): Account;
}
