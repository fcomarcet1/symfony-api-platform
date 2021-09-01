<?php

declare(strict_types=1);

namespace Mailer\Service\Mailer;

use Mailer\Templating\TwigTemplate;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;


class MailerService
{

    // Subject for email
    private const TEMPLATE_SUBJECT_MAP = [
        TwigTemplate::USER_REGISTER => 'Bienvenid@ a my Finance App!.',
        TwigTemplate::RESET_PASSWORD => 'Restablecer contraseña en My finance App.',
        TwigTemplate::GROUP_REQUEST => 'Invitación a grupo desde My finance App.',
    ];

    private MailerInterface $mailer;
    private Environment $engine;
    private LoggerInterface $logger;
    private string $mailerDefaultSender;

    public function __construct(
        MailerInterface $mailer,
        Environment $engine,
        LoggerInterface $logger,
        string $mailerDefaultSender
    ) {
        $this->mailer = $mailer;
        $this->engine = $engine;
        $this->logger = $logger;
        $this->mailerDefaultSender = $mailerDefaultSender;
    }

    /**
     * @throws \Exception
     */
    // param array $payload tendra los  valores del template necesarios
    public function sendEmail(string $receiver, string $template, array $payload)
    {
        // Create email with subject data and template
        $email = (new Email())
            ->from($this->mailerDefaultSender)
            ->to($receiver)
            ->subject(self::TEMPLATE_SUBJECT_MAP[$template])
            ->html($this->engine->render($template, $payload));

        try {
            // send mail
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error(\sprintf('Error sending email: %s', $e->getMessage()));
        }
    }

}