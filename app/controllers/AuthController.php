<?php
class AuthController extends Controller
{
    public function __construct()
    {
        if ($this->isGet() && !in_array($_GET['url'] ?? '', ['logout'])) {
            Auth::guest();
        }
    }

    public function login(): void
    {
        if ($this->isPost()) {
            $email = $this->getInput('email');
            $password = $this->getInput('password');
            $remember = $this->getInput('remember') === 'on';

            if (empty($email) || empty($password)) {
                Session::setError('Please fill in all fields.');
                $this->redirect('/login');
            }

            if (Auth::attempt($email, $password, $remember)) {
                Session::setSuccess('Welcome back!');
                $this->redirect('/dashboard');
            }

            Session::setError('Invalid email or password.');
            $this->redirect('/login');
        }

        $this->render('auth/login', ['title' => 'Login'], 'landing');
    }

    public function register(): void
    {
        if ($this->isPost()) {
            $username = $this->getInput('username');
            $email = $this->getInput('email');
            $password = $this->getInput('password');
            $confirmPassword = $this->getInput('password_confirmation');
            $role = $this->getInput('role');
            $agree = $this->getInput('agree');

            if (!in_array($role, ['buyer', 'worker'])) {
                Session::setError('Invalid role selected.');
                $this->redirect('/register');
            }

            if (empty($username) || empty($email) || empty($password)) {
                Session::setError('Please fill in all fields.');
                $this->redirect('/register');
            }

            if ($password !== $confirmPassword) {
                Session::setError('Passwords do not match.');
                $this->redirect('/register');
            }

            if (strlen($password) < 6) {
                Session::setError('Password must be at least 6 characters.');
                $this->redirect('/register');
            }

            if (Database::exists('users', 'email = :email', ['email' => $email])) {
                Session::setError('Email already registered.');
                $this->redirect('/register');
            }

            if (Database::exists('users', 'username = :username', ['username' => $username])) {
                Session::setError('Username already taken.');
                $this->redirect('/register');
            }

            $referralCode = strtoupper(random_string(8));
            $referredBy = null;
            if (!empty($_COOKIE['ref'])) {
                $referrer = Database::fetch("SELECT id FROM users WHERE referral_code = :code", ['code' => $_COOKIE['ref']]);
                if ($referrer) {
                    $referredBy = $referrer->id;
                }
            }

            $userId = Database::insert('users', [
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => $role,
                'referral_code' => $referralCode,
                'referred_by' => $referredBy,
            ]);

            Database::insert('user_profiles', ['user_id' => $userId]);
            Wallet::getOrCreate($userId);

            if ($referredBy) {
                Database::insert('referrals', [
                    'referrer_id' => $referredBy,
                    'referred_id' => $userId,
                ]);
                $bonus = (float) get_setting('referral_bonus', 1);
                if ($bonus > 0) {
                    Wallet::addBalance($referredBy, $bonus, 'referral', 'Referral bonus for referring ' . $username);
                }
                $newUserBonus = (float) get_setting('new_user_bonus', 0);
                if ($newUserBonus > 0) {
                    Wallet::addBalance($userId, $newUserBonus, 'bonus', 'Welcome bonus');
                }
            }

            Session::setSuccess('Registration successful! Please login.');
            $this->redirect('/login');
        }

        $ref = $_GET['ref'] ?? null;
        $this->render('auth/register', ['title' => 'Register', 'ref' => $ref], 'landing');
    }

    public function logout(): void
    {
        Auth::logout();
        Session::setSuccess('You have been logged out.');
        $this->redirect('/');
    }

    public function dashboard(): void
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $role = Auth::role();
        match ($role) {
            'admin' => $this->redirect('/admin/dashboard'),
            'buyer' => $this->redirect('/buyer/dashboard'),
            'worker' => $this->redirect('/worker/dashboard'),
            default => $this->redirect('/'),
        };
    }

    public function verify(): void
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
        $this->render('auth/verify-email', ['title' => 'Verify Email'], 'landing');
    }
}
