<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserSubscriber implements EventSubscriber
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::preUpdate,
            Events::prePersist,
        ];
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->encodePassword($args);
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->encodePassword($args);
    }

    private function encodePassword(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof User) {
            return;
        }

        $entity->setPassword($this->encoder->encodePassword($entity, $entity->getPassword()));
    }
}