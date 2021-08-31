<?php
declare(strict_types=1);

namespace Mailer\Messenger\Handler;

use Mailer\Messenger\Message\GroupRequestMessage;
use Mailer\Service\Mailer\ClientRoute;
use Mailer\Service\Mailer\MailerService;
use Mailer\Templating\TwigTemplate;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GroupRequestMessageHandler implements MessageHandlerInterface
{
    private MailerService $mailerService;
    private string $host;

    public function __construct(MailerService $mailerService, string $host)
    {
        $this->mailerService = $mailerService;
        $this->host = $host;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(GroupRequestMessage $message): void
    {
        /**
         * Create payload for send message
         */ http://localhost:3000/activate_account?token=8g9sg098bsf&uid=5fs3v-sd54sa-aalkjf
        $payload = [
            'requesterName' => $message->getRequesterName(),
            'groupName' =>$message->getGroupName(),
            'url' => \sprintf(
                '%s%s?&groupId=%s&userId=%d&token=%s',
                $this->host,
                ClientRoute::GROUP_REQUEST,
                $message->getGroupId(),
                $message->getUserId(),
                $message->getToken()
            ),
        ];

        // send email
        $this->mailerService->sendEmail(
            $message->getReceiverEmail(),
            TwigTemplate::GROUP_REQUEST,
            $payload
        );

    }

}