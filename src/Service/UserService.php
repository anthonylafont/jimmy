<?php

namespace App\Service;

use App\Entity\User;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\NotificationService;
use App\Service\SecurityService;

class UserService
{

    /** @var EntityManagerInterface $em */
    protected EntityManagerInterface $em;

    /** @var UserPasswordEncoderInterface $authenticationUtils */
    protected UserPasswordEncoderInterface $passwordEncoder;

    /** @var NotificationService $notificationService */
    protected NotificationService $notificationService;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $passwordEncoder,
        NotificationService $notificationService
    ) {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->notificationService = $notificationService;
    }

    /**
     * Génère MDP + token + validity
     * Persiste l'utilisateur
     * Envoie un email pour modifier le mdp
     *
     * @param User $user
     * @return void
     */
    public function handleUserCreation(User $user): void
    {
        $randomPassword = SecurityService::generateRandomString(SecurityService::RANDOM_PASSWORD_LENGTH);
        $passwordToken = SecurityService::generateRandomString(SecurityService::PASSWORD_TOKEN_LENGTH);
        $passwordTokenValidity = new DateTime();
        $passwordTokenValidity->add(new DateInterval(SecurityService::PASSWORD_TOKEN_VALIDITY));

        $user->setPassword($this->passwordEncoder->encodePassword($user, $randomPassword));
        $user->setPasswordTokenValidity($passwordTokenValidity);
        $user->setPasswordToken($passwordToken);
        $this->em->persist($user);
        //$this->em->flush();

        $this->notificationService->sendMail($user, NotificationService::TEMPLATE_USER_CREATION, $passwordToken, 'coucou');
    }
}
