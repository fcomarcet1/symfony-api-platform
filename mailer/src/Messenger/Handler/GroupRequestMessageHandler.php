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
         */ http://localhost:3000/group_request?&groupId=8a762539-d76b-4ff3-ad5e-0b70506a3a88&userId=0&token=918c270550542ef932e0c796d24594d2e1268d4b
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