<?php

use function DI\get;
use Slim\Views\Twig;
use Cart\Models\Product;
use Slim\Views\TwigExtension;
use Interop\Container\ContainerInterface;

use Cart\Support\Storage\SessionStorage;
use Cart\Support\Storage\Contracts\StorageInterface;

use Cart\Basket\Basket;

use Cart\Support\Extensions\AppTwigExtensions;

return [

    StorageInterface::class => function(ContainerInterface $c)
    {
        return new SessionStorage;
    },
    
    Twig::class => function(ContainerInterface $c)
    {
        $twig = new Twig(__DIR__ . '/../resources/views', [
            'cache' => false
        ]);

        $twig->addExtension(new TwigExtension(
            $c->get('router'),
            $c->get('request')->getUri()
        ));
        $twig->addExtension(new AppTwigExtensions);

        $twig->getEnvironment()->addGlobal('basket', $c->get(Basket::class));

        return $twig;
    },

    Product::class => function (ContainerInterface $c)
    {
        return new Product;
    },

    Basket::class => function (ContainerInterface $c)
    {
        return new Basket(
            $c->get(SessionStorage::class),
            $c->get(Product::class)
        );
    },
];