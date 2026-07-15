<?php
class HomeController extends Controller
{
    public function index(): void
    {
        if (Auth::check()) {
            $role = Auth::role();
            if ($role === 'admin') {
                $this->redirect('/admin/dashboard');
            } elseif ($role === 'buyer') {
                $this->redirect('/buyer/dashboard');
            } elseif ($role === 'worker') {
                $this->redirect('/worker/dashboard');
            }
        }

        $categories = Category::withJobCount();
        $featuredJobs = Job::featured(6);
        $topWorkers = User::topWorkers(6);

        $stats = [
            'active_jobs' => Database::fetch("SELECT COUNT(*) as count FROM jobs WHERE status = 'active'")->count,
            'workers' => Database::fetch("SELECT COUNT(*) as count FROM users WHERE role = 'worker'")->count,
            'buyers' => Database::fetch("SELECT COUNT(*) as count FROM users WHERE role = 'buyer'")->count,
            'completed_jobs' => Database::fetch("SELECT COUNT(*) as count FROM job_applications WHERE status = 'approved'")->count,
            'total_paid' => Database::fetch("SELECT COALESCE(SUM(amount), 0) as total FROM transactions WHERE type = 'payment' AND status = 'completed'")->total,
            'countries' => Database::fetch("SELECT COUNT(DISTINCT country) as count FROM user_profiles WHERE country IS NOT NULL")->count,
        ];

        $faqs = Database::fetchAll("SELECT * FROM faqs WHERE status = 'active' ORDER BY order_column ASC");

        $data = [
            'title' => 'Premium Micro Job Marketplace',
            'meta_description' => 'SpecialGig is a premium micro job marketplace connecting buyers and workers worldwide.',
            'categories' => $categories,
            'featuredJobs' => $featuredJobs,
            'topWorkers' => $topWorkers,
            'stats' => $stats,
            'faqs' => $faqs,
        ];

        $this->render('homepage/index', $data, 'landing');
    }

    public function contact(): void
    {
        if ($this->isPost()) {
            $name = $this->getInput('name');
            $email = $this->getInput('email');
            $subject = $this->getInput('subject');
            $message = $this->getInput('message');

            Database::insert('contacts', [
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message,
            ]);

            Session::setSuccess('Thank you for your message. We will get back to you soon.');
            $this->redirect('/contact');
        }

        $this->render('homepage/contact', ['title' => 'Contact Us'], 'landing');
    }

    public function faq(): void
    {
        $faqs = Database::fetchAll("SELECT * FROM faqs WHERE status = 'active' ORDER BY order_column ASC, id ASC");
        $this->render('homepage/faq', ['title' => 'FAQ', 'faqs' => $faqs], 'landing');
    }

    public function pricing(): void
    {
        $commission = get_setting('commission_rate', 10);
        $this->render('homepage/pricing', ['title' => 'Pricing', 'commission' => $commission], 'landing');
    }

    public function howItWorks(): void
    {
        $this->render('homepage/how-it-works', ['title' => 'How It Works'], 'landing');
    }

    public function page(string $slug): void
    {
        $page = Database::fetch("SELECT * FROM cms_pages WHERE slug = :slug AND status = 'published'", ['slug' => $slug]);
        if (!$page) {
            http_response_code(404);
            $this->render('errors/404', ['title' => 'Page Not Found'], 'landing');
            return;
        }
        $this->render('cms/page', ['title' => $page->title, 'page' => $page], 'landing');
    }

    public function newsletter(): void
    {
        if ($this->isPost()) {
            $email = $this->getInput('email');
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                if (!Database::exists('newsletters', 'email = :email', ['email' => $email])) {
                    Database::insert('newsletters', ['email' => $email]);
                }
                Session::setSuccess('Successfully subscribed to our newsletter!');
            } else {
                Session::setError('Please provide a valid email address.');
            }
        }
        $this->redirect('/');
    }
}
