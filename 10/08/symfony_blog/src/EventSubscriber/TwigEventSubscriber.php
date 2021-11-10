<?php

namespace App\EventSubscriber;

use App\Repository\WebsiteMenuRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $websiteMenuRepository;

    public function __construct( Environment $twig, WebsiteMenuRepository $websiteMenuRepository )
    {
        $this->twig                  = $twig;
        $this->websiteMenuRepository = $websiteMenuRepository;
    }

    public function onKernelController( ControllerEvent $event )
    {
        $this->twig->addGlobal( 'websiteMenu', $this->websiteMenuRepository->findAll() );
    }

    public static function getSubscribedEvents()
    {
        return
        [
            'kernel.controller' => 'onKernelController',
        ];
    }
}
