<?php

namespace App\EventListener;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent as EventAuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
  private $em;

  public function __construct(EntityManagerInterface $em) {
    $this->em = $em;
  }

  /**
   * @param AuthenticationSuccessEvent $event
   */
  public function onAuthenticationSuccessResponse(EventAuthenticationSuccessEvent $event)
  {
    $data = $event->getData();
    $user = $event->getUser();

    if (!$user instanceof User) {
      return;
    }

    $user->setLastConnection(new DateTime());
    $this->em->flush();

    $data['data'] = [
      'roles' => $user->getRoles()
    ];

    $event->setData($data);
  }
}
