<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotificationService
{
    /** @var MailerInterface $mailer */
    private MailerInterface $mailer;

    /** @var TraslatorInterface $translator */
    private TranslatorInterface $translator;

    /** @var string $hostBackend */
    private string $hostBackend;

    /** @var string $senderEmail */
    private string $senderEmail;


    // Mails templates
    public const TEMPLATE_USER_CREATION = [
        'path' => 'email/user_creation.html.twig',
        'subject' => 'mail_user_creation_subject',
        'link' => 'password-initialization'
    ];

    function __construct(
        MailerInterface $mailer,
        TranslatorInterface $translator,
        string $hostBackend,
        string $senderEmail,
    ) {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->hostBackend = $hostBackend;
        $this->senderEmail = $senderEmail;
    }

    /**
     * Envoie un email à un utilisateur en fonction du template choisi
     *
     * @param User $user
     * @return void
     */
    public function sendMail(User $user, array $template, string $path = null): void
    {
        if (isset($template['link'])) {
            $link = 'https://' . $this->hostBackend . '/' . $template['link'] . '/' . $path;
        }
        
        $subject = $this->translator->trans($template['subject']);
        $email = (new TemplatedEmail())
            ->to($user->getEmail())
            ->subject($this->translator->trans($subject))
            ->htmlTemplate($template['path'])
            ->context([
                "link" => $link ?? null
            ])
            ->from($this->senderEmail);
        $this->mailer->send($email);
    }
}
