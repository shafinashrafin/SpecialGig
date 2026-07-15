<?php
class Controller
{
    protected function view(string $view, array $data = []): void
    {
        $viewFile = VIEWS_PATH . '/' . $view . '.php';
        if (file_exists($viewFile)) {
            extract($data);
            require_once $viewFile;
        } else {
            throw new \Exception("View '{$view}' not found at: {$viewFile}");
        }
    }

    protected function render(string $view, array $data = [], string $layout = 'default'): void
    {
        $viewContent = $this->getViewContent($view, $data);
        $layoutFile = VIEWS_PATH . '/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            extract($data);
            require_once $layoutFile;
        } else {
            echo $viewContent;
        }
    }

    protected function renderAdmin(string $view, array $data = [], string $layout = 'admin'): void
    {
        $viewContent = $this->getViewContent($view, $data);
        $layoutFile = VIEWS_PATH . '/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            extract($data);
            require_once $layoutFile;
        } else {
            echo $viewContent;
        }
    }

    private function getViewContent(string $view, array $data = []): string
    {
        $viewFile = VIEWS_PATH . '/' . $view . '.php';
        if (file_exists($viewFile)) {
            extract($data);
            ob_start();
            require $viewFile;
            return ob_get_clean();
        }
        return "View '{$view}' not found";
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    protected function back(): void
    {
        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }

    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function validate(array $data, array $rules): array
    {
        $validator = new Validator();
        return $validator->validate($data, $rules);
    }

    protected function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function getInput(string $key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function allInput(): array
    {
        return array_merge($_GET, $_POST);
    }
}
