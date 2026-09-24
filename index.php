<?php

declare(strict_types=1);

class AccountManager
{
    private array $accounts = [];      // [accountId => balance]
    private array $transactions = [];  // list of transaction logs

    public function createAccount(int $accountId, float $initialDeposit): void
    {
        $this->accounts[$accountId] = $initialDeposit;
    }

    public function getBalance(int $accountId): float
    {
        return $this->accounts[$accountId] ?? 0.0;
    }

    // Call this whenever funds need to be withdrawn
    public function debitAccount(int $accountId, float $amount): bool
    {
        if (!isset($this->accounts[$accountId])) {
            return false;
        }

        if ($this->accounts[$accountId] < $amount) {
            return false;
        }

        $this->accounts[$accountId] -= $amount;
        return true;
    }

    // Callers are expected to record the ledger entry in tandem with debit/credit
    public function recordTransaction(int $accountId, string $type, float $amount, string $reason): void
    {
        $this->transactions[] = [
            'account_id' => $accountId,
            'type' => $type, // 'DEBIT' or 'CREDIT'
            'amount' => $amount,
            'reason' => $reason,
            'timestamp' => time(),
        ];
    }

    public function getTransactionHistory(int $accountId): array
    {
        return array_values(array_filter(
            $this->transactions,
            fn($tx) => $tx['account_id'] === $accountId
        ));
    }
}
