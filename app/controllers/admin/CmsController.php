<?php
class CmsController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    public function index(): void
    {
        $pages = Database::fetchAll("SELECT * FROM cms_pages ORDER BY id ASC");
        $this->renderAdmin('admin/cms/index', ['title' => 'CMS Pages', 'pages' => $pages]);
    }

    public function edit(int $id): void
    {
        $page = Database::fetch("SELECT * FROM cms_pages WHERE id = :id", ['id' => $id]);
        if (!$page) {
            Session::setError('Page not found.');
            $this->redirect('/admin/cms');
        }

        if ($this->isPost()) {
            Database::update('cms_pages', [
                'title' => $this->getInput('title'),
                'content' => $this->getInput('content'),
                'meta_title' => $this->getInput('meta_title'),
                'meta_description' => $this->getInput('meta_description'),
                'status' => $this->getInput('status', 'draft'),
            ], 'id = :id', ['id' => $id]);

            Session::setSuccess('Page updated.');
            $this->redirect('/admin/cms');
        }

        $this->renderAdmin('admin/cms/edit', ['title' => 'Edit: ' . $page->title, 'page' => $page]);
    }

    public function faqs(): void
    {
        if ($this->isPost()) {
            Database::insert('faqs', [
                'question' => $this->getInput('question'),
                'answer' => $this->getInput('answer'),
                'category' => $this->getInput('category'),
            ]);
            Session::setSuccess('FAQ created.');
            $this->redirect('/admin/cms/faqs');
        }

        $faqs = Database::fetchAll("SELECT * FROM faqs ORDER BY order_column ASC, id ASC");
        $this->renderAdmin('admin/cms/faqs', ['title' => 'FAQs', 'faqs' => $faqs]);
    }

    public function deleteFaq(int $id): void
    {
        Database::delete('faqs', 'id = :id', ['id' => $id]);
        Session::setSuccess('FAQ deleted.');
        $this->redirect('/admin/cms/faqs');
    }

    public function announcements(): void
    {
        if ($this->isPost()) {
            Database::insert('announcements', [
                'title' => $this->getInput('title'),
                'message' => $this->getInput('message'),
                'type' => $this->getInput('type', 'info'),
                'starts_at' => $this->getInput('starts_at'),
                'expires_at' => $this->getInput('expires_at'),
            ]);
            Session::setSuccess('Announcement created.');
            $this->redirect('/admin/cms/announcements');
        }

        $announcements = Database::fetchAll("SELECT * FROM announcements ORDER BY created_at DESC");
        $this->renderAdmin('admin/cms/announcements', ['title' => 'Announcements', 'announcements' => $announcements]);
    }

    public function contacts(): void
    {
        $contacts = Database::fetchAll("SELECT * FROM contacts ORDER BY created_at DESC");
        $this->renderAdmin('admin/cms/contacts', ['title' => 'Contact Messages', 'contacts' => $contacts]);
    }

    public function readContact(int $id): void
    {
        Database::query("UPDATE contacts SET is_read = 1 WHERE id = :id", ['id' => $id]);
        $this->redirect('/admin/cms/contacts');
    }
}
