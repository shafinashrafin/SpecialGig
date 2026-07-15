<?php
class Wallet extends Model
{
    protected string $table = 'wallets';
    protected array $fillable = [
        'user_id', 'balance', 'pending_balance', 'referral_earnings',
        'bonus_earnings', 'total_deposited', 'total_withdrawn', 'total_earned'
    ];

    public static function getOrCreate(int $userId): object
    {
        $wallet = Database::fetch("SELECT * FROM wallets WHERE user_id = :id", ['id' => $userId]);
        if (!$wallet) {
            $id = Database::insert('wallets', ['user_id' => $userId]);
            return Database::fetch("SELECT * FROM wallets WHERE id = :id", ['id' => $id]);
        }
        return $wallet;
    }

    public static function addBalance(int $userId, float $amount, string $type, string $description = '', string $reference = ''): bool
    {
        $wallet = self::getOrCreate($userId);
        $balanceBefore = $wallet->balance;

        Database::getInstance()->beginTransaction();
        try {
            Database::query(
                "UPDATE wallets SET balance = balance + :amount, total_earned = total_earned + :amount2 WHERE user_id = :user_id",
                ['amount' => $amount, 'amount2' => $amount, 'user_id' => $userId]
            );

            Database::insert('transactions', [
                'wallet_id' => $wallet->id,
                'user_id' => $userId,
                'type' => $type,
                'amount' => $amount,
                'fee' => 0,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceBefore + $amount,
                'status' => 'completed',
                'description' => $description,
                'reference' => $reference ?: generate_reference(),
            ]);

            Database::getInstance()->commit();
            return true;
        } catch (\Exception $e) {
            Database::getInstance()->rollBack();
            return false;
        }
    }

    public static function deductBalance(int $userId, float $amount, string $type, string $description = '', string $reference = ''): bool
    {
        $wallet = self::getOrCreate($userId);
        if ($wallet->balance < $amount) return false;

        $balanceBefore = $wallet->balance;

        Database::getInstance()->beginTransaction();
        try {
            Database::query(
                "UPDATE wallets SET balance = balance - :amount WHERE user_id = :user_id",
                ['amount' => $amount, 'user_id' => $userId]
            );

            Database::insert('transactions', [
                'wallet_id' => $wallet->id,
                'user_id' => $userId,
                'type' => $type,
                'amount' => -$amount,
                'fee' => 0,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceBefore - $amount,
                'status' => 'completed',
                'description' => $description,
                'reference' => $reference ?: generate_reference(),
            ]);

            Database::getInstance()->commit();
            return true;
        } catch (\Exception $e) {
            Database::getInstance()->rollBack();
            return false;
        }
    }

    public static function transactions(int $userId, int $limit = 20, int $offset = 0): array
    {
        return Database::fetchAll(
            "SELECT * FROM transactions WHERE user_id = :id ORDER BY created_at DESC LIMIT {$limit} OFFSET {$offset}",
            ['id' => $userId]
        );
    }

    public static function totalBalance(): float
    {
        return (float) Database::fetch("SELECT COALESCE(SUM(balance), 0) as total FROM wallets")->total;
    }

    public static function totalPending(): float
    {
        return (float) Database::fetch("SELECT COALESCE(SUM(pending_balance), 0) as total FROM wallets")->total;
    }
}
