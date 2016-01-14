<?php
// src/OC/PlatformBundle/Beta/BetaListener.php

namespace OC\PlatformBundle\Beta;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class BetaListener
{
  // L'argument de la méthode est un FilterResponseEvent
  public function processBeta(FilterResponseEvent $event)
  {
    // On teste si la requête est bien la requête principale (et non une sous-requête)
    if (!$event->isMasterRequest()) {
      return;
    }

    // On récupère la réponse que le gestionnaire a insérée dans l'évènement
    $response = $event->getResponse();

    // Ici on modifie comme on veut la réponse…

    // Puis on insère la réponse modifiée dans l'évènement
    $event->setResponse($response);

  }
}