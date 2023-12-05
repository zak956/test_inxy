<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Exceptions\FillBalanceException;
use App\Exceptions\NegativeAmountException;
use App\Exceptions\NotEnoughMoneyException;
use App\Exceptions\TransferMoneyException;
use App\Models\Account;
use App\Repositories\AccountRepository;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\TransactionRepository;
use App\Services\AccountService;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class AccountServiceTest extends TestCase
{
    /**
     * @param float $amount
     * @return void
     *
     * @dataProvider getDataForFillBalance
     * @throws FillBalanceException
     */
    public function testFillBalance(float $amount): void
    {
        $initialBalance = 5.00;

        $accountRepository = $this->getAccountRepositoryMock();
        $accountRepository->expects($this->exactly($amount > 0 ? 1 : 0))
            ->method('save')
            ->willReturn(true);
        $accountRepository->expects($this->never())->method('findOrFail');

        $transactionRepository = $this->getTransactionRepositoryMock();
        $transactionRepository->expects($this->exactly($amount > 0 ? 1 : 0))
            ->method('save')
            ->willReturn(true);

        $service = new AccountService($accountRepository, $transactionRepository);
        $account = new Account(['balance' => $initialBalance]);

        if ($amount <= 0) {
            $this->expectException(NegativeAmountException::class);
        }
        $transaction = $service->fillBalance($account, $amount);

        self::assertEquals($amount, $transaction->amount);
        self::assertEquals($initialBalance + $amount, $account->balance);
    }

    /**
     * @param float $senderBalance
     * @param float $recipientBalance
     * @param float $amount
     * @param string $title
     * @param string|null $expectedException
     * @return void
     * @throws NegativeAmountException
     * @throws NotEnoughMoneyException
     * @throws TransferMoneyException
     *
     * @dataProvider getDataForTransfer
     */
    public function testTransfer(
        float $senderBalance,
        float $recipientBalance,
        float $amount,
        string $title,
        ?string $expectedException = null
    ): void {
        $accountRepository = $this->getAccountRepositoryMock();
        $accountRepository->expects($this->exactly(($amount > 0 && $senderBalance >= $amount) ? 2 : 0))
            ->method('save')
            ->willReturn(true);
        $accountRepository->expects($this->never())->method('findOrFail');

        $transactionRepository = $this->getTransactionRepositoryMock();
        $transactionRepository->expects($this->exactly(($amount > 0 && $senderBalance >= $amount) ? 1 : 0))
            ->method('save')
            ->willReturn(true);

        $service = new AccountService($accountRepository, $transactionRepository);
        $sender = new Account(['balance' => $senderBalance]);
        $recipient = new Account(['balance' => $recipientBalance]);

        if ($expectedException) {
            $this->expectException($expectedException);
        }

        $transaction = $service->transfer($sender, $recipient, $amount, $title);

        self::assertEquals($amount, $transaction->amount);
        self::assertEquals($senderBalance - $amount, $sender->balance);
        self::assertEquals($recipientBalance + $amount, $recipient->balance);
    }

    /**
     * @return array
     */
    public static function getDataForTransfer(): array
    {
        return [
            [
                10.00,
                10.00,
                5.00,
                'Test'
            ],
            [
                10.00,
                0.00,
                5.00,
                'Test'
            ],
            [
                0.00,
                10.00,
                5.00,
                'Test',
                NotEnoughMoneyException::class
            ],
            [
                10.00,
                10.00,
                0.00,
                'Test',
                NegativeAmountException::class
            ],
            [
                10.00,
                10.00,
                -5.00,
                'Test',
                NegativeAmountException::class
            ],
        ];
    }

    /**
     * @return array
     */
    public static function getDataForFillBalance(): array
    {
        return [
            [
                10.50
            ],
            [
                0
            ],
            [
                -20
            ]
        ];
    }

    /**
     * @return MockObject&AccountRepositoryInterface
     */
    protected function getAccountRepositoryMock(): AccountRepositoryInterface&MockObject
    {
        return $this->getMockBuilder(AccountRepository::class)
            ->onlyMethods(['save', 'findOrFail'])
            ->getMock();
    }

    /**
     * @return MockObject&TransactionRepositoryInterface
     */
    protected function getTransactionRepositoryMock(): TransactionRepositoryInterface&MockObject
    {
        return $this->getMockBuilder(TransactionRepository::class)
            ->onlyMethods(['save'])
            ->getMock();
    }
}
