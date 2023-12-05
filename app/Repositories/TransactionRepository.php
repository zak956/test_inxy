<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Transaction;
use App\Repositories\Interfaces\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function save(Transaction $transaction): bool
    {
        return $transaction->save();
    }
}
