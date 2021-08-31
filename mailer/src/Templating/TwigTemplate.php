<?php

declare(strict_types=1);

namespace Mailer\Templating;

abstract class TwigTemplate
{
    public const USER_REGISTER = 'user/register.twig';
    public const RESET_PASSWORD = 'user/request_reset_password.twig';
    public const GROUP_REQUEST = 'group/group_request.twig';
}