<?php

namespace App\EventListener;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class RequestListener
{
    private $security;
    
    private $router;
    
    public function __construct(Security $security, RouterInterface $router)
    {
        $this->security = $security;
        $this->router = $router;
    }
    
    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMainRequest()) {
            // don't do anything if it's not the master request
            return;
        }
        
        try {
            if ($this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
                // Get user object
                $user = $this->security->getUser();
                
                // Check user active flag
                if ($user != null && !$user->isActive()) {
                    // Logout user
                    $event->setResponse(new RedirectResponse($this->router->generate('app_logout')));
                }
            }
        } catch (AuthenticationCredentialsNotFoundException $ex) {
        }
    }
}
