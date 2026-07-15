<?php
class JobController extends Controller
{
    public function browse(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $query = $_GET['q'] ?? '';
        $categoryId = !empty($_GET['category']) ? (int) $_GET['category'] : null;
        $country = $_GET['country'] ?? '';

        $categories = Category::active();
        $result = Job::search($query, $categoryId, $country, $page, 12);

        $data = [
            'title' => 'Browse Jobs',
            'meta_description' => 'Browse available micro jobs and start earning.',
            'categories' => $categories,
            'jobs' => $result['data'],
            'total' => $result['total'],
            'page' => $result['page'],
            'lastPage' => $result['lastPage'],
            'query' => $query,
            'selectedCategory' => $categoryId,
            'selectedCountry' => $country,
        ];

        $this->render('jobs/browse', $data, 'default');
    }

    public function view(string $slug): void
    {
        $job = Database::fetch(
            "SELECT j.*, c.name as category_name, c.slug as category_slug,
                    u.username, up.full_name, up.avatar, up.country
             FROM jobs j
             LEFT JOIN categories c ON j.category_id = c.id
             LEFT JOIN users u ON j.user_id = u.id
             LEFT JOIN user_profiles up ON u.id = up.user_id
             WHERE j.slug = :slug",
            ['slug' => $slug]
        );

        if (!$job) {
            http_response_code(404);
            $this->render('errors/404', ['title' => 'Job Not Found'], 'default');
            return;
        }

        $files = Database::fetchAll("SELECT * FROM job_files WHERE job_id = :id", ['id' => $job->id]);
        $reviews = Database::fetchAll(
            "SELECT r.*, u.username, up.full_name, up.avatar
             FROM reviews r
             LEFT JOIN users u ON r.from_user_id = u.id
             LEFT JOIN user_profiles up ON u.id = up.user_id
             WHERE r.job_id = :id ORDER BY r.created_at DESC LIMIT 10",
            ['id' => $job->id]
        );

        $buyerRating = Review::averageRating($job->user_id);
        $buyerJobs = Database::fetch(
            "SELECT COUNT(*) as total, COALESCE(AVG(rating), 0) as avg_rating
             FROM reviews WHERE to_user_id = :id",
            ['id' => $job->user_id]
        );

        $data = [
            'title' => $job->title,
            'meta_description' => truncate($job->description, 160),
            'job' => $job,
            'files' => $files,
            'reviews' => $reviews,
            'buyerRating' => $buyerRating,
            'buyerJobs' => $buyerJobs,
        ];

        $this->render('jobs/view', $data, 'default');
    }

    public function accept(int $jobId): void
    {
        Auth::requireRole('worker');

        $job = Database::fetch("SELECT * FROM jobs WHERE id = :id AND status = 'active' AND available_slots > filled_slots", ['id' => $jobId]);
        if (!$job) {
            Session::setError('Job not available.');
            $this->redirect('/jobs/browse');
        }

        $existing = Database::fetch(
            "SELECT id FROM job_applications WHERE job_id = :job_id AND worker_id = :worker_id",
            ['job_id' => $jobId, 'worker_id' => Auth::id()]
        );
        if ($existing) {
            Session::setError('You have already accepted this job.');
            $this->redirect('/jobs/' . $job->slug);
        }

        Database::getInstance()->beginTransaction();
        try {
            Database::insert('job_applications', [
                'job_id' => $jobId,
                'worker_id' => Auth::id(),
                'status' => 'accepted',
            ]);

            Database::query(
                "UPDATE jobs SET filled_slots = filled_slots + 1 WHERE id = :id",
                ['id' => $jobId]
            );

            Notification::send($job->user_id, 'info', 'Job Accepted', 'A worker has accepted your job: ' . $job->title, '/buyer/applications');

            Database::getInstance()->commit();
            Session::setSuccess('Job accepted successfully! Complete the task and submit your proof.');
        } catch (\Exception $e) {
            Database::getInstance()->rollBack();
            Session::setError('Failed to accept job. Please try again.');
        }

        $this->redirect('/jobs/' . $job->slug);
    }
}
