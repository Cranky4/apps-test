<?php
// DIC configuration

    $container = $app->getContainer();

// view renderer
    $container['renderer'] = function ($c) {
        $settings = $c->get('settings')['renderer'];

        return new Slim\Views\PhpRenderer($settings['template_path']);
    };

    /**
     * @param $container
     *
     * @return \App\Controller\MainController
     */
    $container['MainController'] = function ($container) {
        return new \App\Controller\MainController($container);
    };

    /**
     * @param \Slim\Container $container
     *
     * @return \App\Service\OrderMailer
     */
    $container['mailer'] = function ($container) {
        $mailTo = $container->get('settings')['mailTo'];

        return new \App\Service\OrderMailer($mailTo);
    };

    // monolog
    //$container['logger'] = function ($c) {
    //    $settings = $c->get('settings')['logger'];
    //    $logger = new Monolog\Logger($settings['name']);
    //    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    //    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    //    return $logger;
    //};