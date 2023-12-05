<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Account;
use App\Repositories\Interfaces\AccountRepositoryInterface;

class AccountRepository implements AccountRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function save(Account $account): bool
    {
        return $account->save();
    }

    /**
     * @inheritDoc
     */
    public function findOrFail(int $id): Account
    {
        /** @var Account */
        return Account::query()->findOrFail($id);
    }
}
