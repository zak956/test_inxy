<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\FillBalanceException;
use App\Exceptions\NegativeAmountException;
use App\Exceptions\NotEnoughMoneyException;
use App\Exceptions\TransferMoneyException;
use App\Models\Account;
use App\Models\Transaction;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class AccountService
{
    /**
     * @param AccountRepositoryInterface $accountRepository
     * @param TransactionRepositoryInterface $transactionRepository
     */
    public function __construct(
        protected AccountRepositoryInterface $accountRepository,
        protected TransactionRepositoryInterface $transactionRepository
    ) {
    }

    /**
     * @param Account $account
     * @param float $amount
     * @return Transaction
     * @throws FillBalanceException
     * @throws NegativeAmountException
     */
    public function fillBalance(Account $account, float $amount): Transaction
    {
        if ($amount <= 0) {
            throw new NegativeAmountException('Amount should be positive.');
        }

        try {
            DB::beginTransaction();

            $account->balance += $amount;
            $this->accountRepository->save($account);

            $transaction = new Transaction([
                'sender_id' => null,
                'recipient_id' => $account->id,
                'amount' => $amount,
                'title' => 'Filling balance.'
            ]);
            $this->transactionRepository->save($transaction);

            DB::commit();

            return $transaction;
        } catch (Exception $e) {
            throw new FillBalanceException($e->getMessage());
        }
    }

    /**
     * @param Account $sender
     * @param Account $recipient
     * @param float $amount
     * @param string $title
     * @return Transaction
     * @throws TransferMoneyException
     * @throws NegativeAmountException
     * @throws NotEnoughMoneyException
     */
    public function transfer(
        Account $sender,
        Account $recipient,
        float $amount,
        string $title = 'Money transfer.'
    ): Transaction {
        if ($amount <= 0) {
            throw new NegativeAmountException('Amount should be positive.');
        }

        try {
            DB::beginTransaction();

            if ($sender->balance - $amount < 0) {
                DB::rollBack();
                throw new NotEnoughMoneyException('Not enough money.');
            }
            $sender->balance -= $amount;
            $this->accountRepository->save($sender);

            $recipient->balance += $amount;
            $this->accountRepository->save($recipient);

            $transaction = new Transaction([
                'sender_id' => $sender->id,
                'recipient_id' => $recipient->id,
                'amount' => $amount,
                'title' => $title
            ]);
            $this->transactionRepository->save($transaction);

            DB::commit();

            return $transaction;
        } catch (NotEnoughMoneyException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new TransferMoneyException($e->getMessage());
        }
    }
}
