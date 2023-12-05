<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\FillBalanceException;
use App\Exceptions\NegativeAmountException;
use App\Exceptions\NotEnoughMoneyException;
use App\Exceptions\TransferMoneyException;
use App\Http\Requests\AccountFillRequest;
use App\Http\Requests\AccountSendRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Account;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use App\Services\AccountService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AccountController
{
    /**
     * @param AccountService $accountService
     * @param AccountRepositoryInterface $accountRepository
     */
    public function __construct(
        protected AccountService $accountService,
        protected AccountRepositoryInterface $accountRepository
    ) {
    }

    /**
     * @param AccountFillRequest $request
     * @param Account $account
     * @return JsonResponse
     * @throws FillBalanceException|NegativeAmountException
     */
    public function fill(AccountFillRequest $request, Account $account): JsonResponse
    {
        $transaction = $this->accountService->fillBalance($account, (float)$request->validated('amount'));

        return new JsonResponse([
            'data' => new TransactionResource($transaction)
        ], Response::HTTP_CREATED);
    }

    /**
     * @param AccountSendRequest $request
     * @param Account $sender
     * @return JsonResponse
     * @throws TransferMoneyException
     */
    public function send(AccountSendRequest $request, Account $sender): JsonResponse
    {
        $data = $request->validated();
        $recipient = $this->accountRepository->findOrFail($data['recipient_id']);

        try {
            $transaction = $this->accountService->transfer($sender, $recipient, (float)$data['amount'], $data['title']);
        } catch (NegativeAmountException | NotEnoughMoneyException $e) {
            return new JsonResponse([
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
        return new JsonResponse([
            'data' => new TransactionResource($transaction)
        ], Response::HTTP_CREATED);
    }
}
