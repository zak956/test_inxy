<?php

namespace App\Repositories\Interfaces;

use App\Models\Transaction;

interface TransactionRepositoryInterface
{
    /**
     * @param Transaction $transaction
     * @return bool
     */
    public function save(Transaction $transaction): bool;
}
