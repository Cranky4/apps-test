<?php
// Routes

    use App\Middleware\AuthMiddleware;
    use App\Middleware\CorsMiddleware;

    $app->group('/api', function () {
        /** @var Slim\App $this */
        $this->options('/{routes:.+}', 'MainController:options');

        /** @var Slim\App $this */
        $this->post('', 'MainController:home');

    })->add(CorsMiddleware::class)->add(AuthMiddleware::class);

    $app->get('/[{name}]', function ($request, $response, $args) {
        // Sample log message
        $this->logger->info("Slim-Skeleton '/' route");

        // Render index view
        return $this->renderer->render($response, 'index.phtml', $args);
    });
