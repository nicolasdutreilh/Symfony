<?php
// src/OC/PlatformBundle/Bigbrother/CensorshipListener.php

namespace OC\PlatformBundle\Bigbrother;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CensorshipListener implements EventSubscriberInterface
{
  protected $processor;
  protected $listUsers = array();

  public function __construct(CensorshipProcessor $processor, $listUsers)
  {
    $this->processor = $processor;
    $this->listUsers = $listUsers;
  }

  // La méthode de l'interface que l'on doit implémenter, à définir en static
  static public function getSubscribedEvents()
  {
    // On retourne un tableau « nom de l'évènement » => « méthode à exécuter »
    return array(
      'oc_platform.bigbrother.post_message' => 'processMessage');
  }

  public function processMessage(MessagePostEvent $event)
  {
    // On active la surveillance si l'auteur du message est dans la liste
    if (in_array($event->getUser()->getId(), $this->listUsers)) {
      // On envoie un e-mail à l'administrateur
      $this->processor->notifyEmail($event->getMessage(), $event->getUser());

      // On censure le message
      $message = $this->processor->censorMessage($event->getMessage());
      // On enregistre le message censuré dans l'event
      $event->setMessage($message);
    }
  }
}