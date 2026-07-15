<?php
class App
{
    private string $controller = 'HomeController';
    private string $method = 'index';
    private array $params = [];

    public function run(): void
    {
        $url = $this->parseUrl();

        $adminPrefix = ADMIN_PREFIX;

        if (!empty($url[0]) && $url[0] === $adminPrefix) {
            array_shift($url);
            $controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'DashboardController';
            $controllerFile = APP_PATH . '/controllers/admin/' . $controllerName . '.php';
            $controllerClass = $controllerName;
        } else {
            $controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : $this->controller;
            $controllerFile = APP_PATH . '/controllers/' . $controllerName . '.php';
            $controllerClass = $controllerName;
        }

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $this->controller = $controllerClass;
            array_shift($url);
        } else {
            $controllerFile = APP_PATH . '/controllers/' . $this->controller . '.php';
            require_once $controllerFile;
        }

        if (!class_exists($this->controller)) {
            http_response_code(404);
            $this->loadErrorView('404');
            return;
        }

        $this->controller = new $this->controller();

        if (!empty($url[0])) {
            $method = $url[0];
            if (method_exists($this->controller, $method)) {
                $this->method = $method;
                array_shift($url);
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl(): array
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }

    private function loadErrorView(string $code): void
    {
        $viewFile = VIEWS_PATH . "/errors/{$code}.php";
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            echo "<h1>Error {$code}</h1>";
        }
    }
}
