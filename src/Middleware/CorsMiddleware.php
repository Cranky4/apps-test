<?php

    /**
     * Created by PhpStorm.
     * User: cranky4
     * Date: 24/04/2017
     * Time: 19:12
     */

    namespace App\Middleware;

    use Slim\Http\Response;

    /**
     * Class AuthMiddleware
     * @package src\class
     */
    class CorsMiddleware
    {
        /**
         * Example middleware invokable class
         *
         * @param  \Psr\Http\Message\ServerRequestInterface $request PSR7 request
         * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
         * @param  callable                                 $next Next middleware
         *
         * @return \Psr\Http\Message\ResponseInterface
         */
        public function __invoke($request, $response, $next)
        {
            /** @var Response $response */
            $response = $next($request, $response);

            return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers',
                    'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        }
    }