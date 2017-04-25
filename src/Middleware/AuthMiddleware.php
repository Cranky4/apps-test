<?php

    /**
     * Created by PhpStorm.
     * User: cranky4
     * Date: 24/04/2017
     * Time: 19:12
     */

    namespace App\Middleware;

    use Psr\Container\ContainerInterface;
    use Psr\Http\Message\RequestInterface;

    /**
     * Class AuthMiddleware
     * @package src\class
     */
    class AuthMiddleware
    {
        /**
         * @var ContainerInterface
         */
        protected $container;

        /**
         * AuthMiddleware constructor.
         *
         * @param ContainerInterface $container
         */
        public function __construct(ContainerInterface $container)
        {
            $this->container = $container;
        }

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
            if (!$this->auth($request)) {
                return $response->withStatus(401); //unauthorized
            }

            return $next($request, $response);
        }

        /**
         * @param RequestInterface $request
         *
         * @return false|string of token
         */
        private function auth($request)
        {
            if (!$request->hasHeader('Authorization')) {
                return false;
            }

            $tokenHeader = $request->getHeaderLine('Authorization');

            $tokenPieces = explode(' ', $tokenHeader);
            if (count($tokenPieces) !== 2 && $tokenPieces[0] != 'Bearer') {
                return false;
            }
            $token = $tokenPieces[1];

            return $this->container->get('settings')['authToken'] == $token;
        }
    }