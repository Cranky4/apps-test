<?php

    /**
     * Created by PhpStorm.
     * User: cranky4
     * Date: 24/04/2017
     * Time: 20:33
     */

    namespace App\Controller;

    use App\Service\OrderMailer;
    use Psr\Container\ContainerInterface;
    use Slim\Http\Response;
    use Slim\Views\PhpRenderer;

    /**
     * Class MainController
     * @package App\Controller
     */
    class MainController
    {
        /**
         * @var ContainerInterface
         */
        protected $container;
        /**
         * @var OrderMailer
         */
        protected $mailer;
        /**
         * @var PhpRenderer
         */
        protected $renderer;

        /**
         * AuthMiddleware constructor.
         *
         * @param ContainerInterface $container
         * @param PhpRenderer        $renderer
         */
        public function __construct(ContainerInterface $container, PhpRenderer $renderer)
        {
            $this->container = $container;
            $this->mailer = $container->get('mailer');

            $this->renderer = $renderer;
        }

        /**
         * @param \Slim\Http\Request  $request
         * @param \Slim\Http\Response $response
         * @param                     $args
         *
         * @return mixed
         */
        public function options($request, $response, $args)
        {
            return $response;
        }

        /**
         * @param \Slim\Http\Request  $request
         * @param \Slim\Http\Response $response
         *
         * @return mixed
         */
        public function home($request, $response)
        {
            $name = $request->getParsedBodyParam('name');
            $phone = $request->getParsedBodyParam('phone');

            if (!$name || !$phone) {
                return self::buildJsonResponse($response, 400, ['status' => 'error', 'message' => 'Bad input']);
            }

            $this->mailer->subject = "Заказ с мобильного приложения";
            $act = $request->getParam('act');

            if ($act == 'order') {
                $message = OrderMailer::composeOrderMessage($request->getParsedBody());
            } elseif ($act == 'callback') {
                $message = OrderMailer::composeCallbackMessage($request->getParsedBody());
            } else {
                return self::buildJsonResponse($response, 400, ['status' => 'error', 'message' => 'Bad request']);
            }

            if (!$this->mailer->send($message)) {
                return self::buildJsonResponse($response, 500, [
                    "status" => "error",
                    "error"  => "Message could not be sent..",
                ]);
            }

            return self::buildJsonResponse($response, 201, ['status' => 'ok']);
        }

        /**
         * @param Response $response
         * @param int      $code
         * @param   mixed  $bodyData
         *
         * @return Response
         */
        private static function buildJsonResponse($response, $code, $bodyData)
        {
            $body = $response->getBody();
            $body->write(json_encode($bodyData));

            return $response->withStatus($code)->withHeader('Content-Type',
                'application/json; charset=utf-8')->withHeader('Access-Control-Allow-Origin', '*')->withBody($body);
        }

        /**
         * @param $request
         * @param $response
         *
         * @return \Psr\Http\Message\ResponseInterface
         */
        public function docs($request, $response)
        {
            return $this->renderer->render($response, 'api-doc.phtml');
        }
    }
