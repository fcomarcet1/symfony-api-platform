<?php

namespace App\Api\Listener\PreWriteListener;

use Symfony\Component\HttpKernel\Event\ViewEvent;

interface PreWriteListener
{
    public function onKernelView(ViewEvent $event): void;
}