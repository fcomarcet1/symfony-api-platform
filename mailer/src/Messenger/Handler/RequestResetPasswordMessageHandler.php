<?php

namespace Mailer\Messenger\Handler;

use Mailer\Messenger\Message\RequestResetPasswordMessage;
use Mailer\Service\Mailer\ClientRoute;
use Mailer\Service\Mailer\MailerService;
use Mailer\Templating\TwigTemplate;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RequestResetPasswordMessageHandler implements MessageHandlerInterface
{
    private MailerService $mailerService;
    private string $host; // frontend URL

    public function __construct(MailerService $mailerService, string $host)
    {
        $this->mailerService = $mailerService;
        $this->host = $host;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(RequestResetPasswordMessage $message): void
    {

        // create payload for send message
        // http://localhost:3000/reset_password?uid=hdfud-6dgd7-d8dbsk-8dbhd89&rpt=6dg7djndg6d9ndbdjdnm
        $payload = [
            'url' => \sprintf(
                '%s%s?uid=%s&rpt=%s',
                $this->host,
                ClientRoute::RESET_PASSWORD,
                $message->getId(),
                $message->getResetTokenPassword()
            ),
        ];

        // send email
        $this->mailerService->sendEmail($message->getEmail(), TwigTemplate::RESET_PASSWORD, $payload);
    }
}
