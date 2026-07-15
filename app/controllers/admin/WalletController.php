<?php
class WalletController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    public function deposits(): void
    {
        $status = $_GET['status'] ?? '';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $where = "1=1";
        $params = [];
        if ($status) {
            $where .= " AND d.status = :status";
            $params['status'] = $status;
        }

        $total = Database::fetch("SELECT COUNT(*) as count FROM deposits d WHERE {$where}", $params);
        $deposits = Database::fetchAll(
            "SELECT d.*, u.username, up.full_name
             FROM deposits d
             LEFT JOIN users u ON d.user_id = u.id
             LEFT JOIN user_profiles up ON u.id = up.user_id
             WHERE {$where}
             ORDER BY d.created_at DESC LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        $this->renderAdmin('admin/wallet/deposits', [
            'title' => 'Deposit Management',
            'deposits' => $deposits,
            'total' => $total->count ?? 0,
            'page' => $page,
            'lastPage' => ceil(($total->count ?? 0) / $perPage),
            'currentStatus' => $status,
        ]);
    }

    public function approveDeposit(int $id): void
    {
        $deposit = Database::fetch("SELECT * FROM deposits WHERE id = :id AND status = 'pending'", ['id' => $id]);
        if ($deposit) {
            Database::getInstance()->beginTransaction();
            try {
                Database::query("UPDATE deposits SET status = 'completed', approved_by = :admin, approved_at = NOW() WHERE id = :id", [
                    'admin' => Auth::id(), 'id' => $id
                ]);
                Wallet::addBalance($deposit->user_id, $deposit->amount, 'deposit', 'Deposit via ' . $deposit->payment_method, 'DEP-' . $id);
                Database::query("UPDATE wallets SET total_deposited = total_deposited + :amount WHERE user_id = :uid", [
                    'amount' => $deposit->amount, 'uid' => $deposit->user_id
                ]);
                Notification::send($deposit->user_id, 'success', 'Deposit Approved', 'Your deposit of $' . $deposit->amount . ' has been approved.');
                Database::getInstance()->commit();
                Session::setSuccess('Deposit approved.');
            } catch (\Exception $e) {
                Database::getInstance()->rollBack();
                Session::setError('Failed to approve deposit.');
            }
        }
        $this->redirect('/admin/wallet/deposits');
    }

    public function rejectDeposit(int $id): void
    {
        $reason = $this->getInput('reason', 'Payment verification failed.');
        Database::query("UPDATE deposits SET status = 'failed', admin_note = :note WHERE id = :id AND status = 'pending'", [
            'note' => $reason, 'id' => $id
        ]);
        Session::setSuccess('Deposit rejected.');
        $this->redirect('/admin/wallet/deposits');
    }

    public function withdrawals(): void
    {
        $status = $_GET['status'] ?? '';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $where = "1=1";
        $params = [];
        if ($status) {
            $where .= " AND w.status = :status";
            $params['status'] = $status;
        }

        $total = Database::fetch("SELECT COUNT(*) as count FROM withdrawals w WHERE {$where}", $params);
        $withdrawals = Database::fetchAll(
            "SELECT w.*, u.username, up.full_name
             FROM withdrawals w
             LEFT JOIN users u ON w.user_id = u.id
             LEFT JOIN user_profiles up ON u.id = up.user_id
             WHERE {$where}
             ORDER BY w.created_at DESC LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        $this->renderAdmin('admin/wallet/withdrawals', [
            'title' => 'Withdrawal Management',
            'withdrawals' => $withdrawals,
            'total' => $total->count ?? 0,
            'page' => $page,
            'lastPage' => ceil(($total->count ?? 0) / $perPage),
            'currentStatus' => $status,
        ]);
    }

    public function approveWithdrawal(int $id): void
    {
        $withdrawal = Database::fetch("SELECT * FROM withdrawals WHERE id = :id AND status = 'pending'", ['id' => $id]);
        if ($withdrawal) {
            Database::getInstance()->beginTransaction();
            try {
                Database::query("UPDATE withdrawals SET status = 'completed', approved_by = :admin, approved_at = NOW() WHERE id = :id", [
                    'admin' => Auth::id(), 'id' => $id
                ]);
                Database::query("UPDATE wallets SET pending_balance = pending_balance - :amount, total_withdrawn = total_withdrawn + :amount2 WHERE user_id = :uid", [
                    'amount' => $withdrawal->amount, 'amount2' => $withdrawal->amount, 'uid' => $withdrawal->user_id
                ]);
                Database::insert('transactions', [
                    'wallet_id' => $withdrawal->wallet_id,
                    'user_id' => $withdrawal->user_id,
                    'type' => 'withdrawal',
                    'amount' => -$withdrawal->amount,
                    'fee' => $withdrawal->fee,
                    'balance_before' => 0,
                    'balance_after' => 0,
                    'status' => 'completed',
                    'description' => 'Withdrawal via ' . $withdrawal->payment_method,
                    'reference' => 'WD-' . $id,
                ]);
                Notification::send($withdrawal->user_id, 'success', 'Withdrawal Approved', 'Your withdrawal of $' . $withdrawal->amount . ' has been approved.');
                Database::getInstance()->commit();
                Session::setSuccess('Withdrawal approved.');
            } catch (\Exception $e) {
                Database::getInstance()->rollBack();
                Session::setError('Failed to approve withdrawal.');
            }
        }
        $this->redirect('/admin/wallet/withdrawals');
    }

    public function rejectWithdrawal(int $id): void
    {
        $reason = $this->getInput('reason', 'Withdrawal request could not be processed.');
        $withdrawal = Database::fetch("SELECT * FROM withdrawals WHERE id = :id AND status = 'pending'", ['id' => $id]);
        if ($withdrawal) {
            Database::getInstance()->beginTransaction();
            try {
                Database::query("UPDATE withdrawals SET status = 'failed', admin_note = :note WHERE id = :id", [
                    'note' => $reason, 'id' => $id
                ]);
                Database::query("UPDATE wallets SET balance = balance + :amount, pending_balance = pending_balance - :amount2 WHERE user_id = :uid", [
                    'amount' => $withdrawal->amount, 'amount2' => $withdrawal->amount, 'uid' => $withdrawal->user_id
                ]);
                Notification::send($withdrawal->user_id, 'error', 'Withdrawal Rejected', 'Your withdrawal of $' . $withdrawal->amount . ' was rejected. Reason: ' . $reason);
                Database::getInstance()->commit();
                Session::setSuccess('Withdrawal rejected and funds returned.');
            } catch (\Exception $e) {
                Database::getInstance()->rollBack();
                Session::setError('Failed to reject withdrawal.');
            }
        }
        $this->redirect('/admin/wallet/withdrawals');
    }
}
