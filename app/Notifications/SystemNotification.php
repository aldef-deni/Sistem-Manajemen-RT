<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification
{
    use Queueable;

    /**
     * @param  array<string, scalar|null>  $routeParams
     * @param  array<string, scalar|null>  $context
     */
    public function __construct(
        private readonly string $category,
        private readonly string $title,
        private readonly string $message,
        private readonly string $routeName,
        private readonly array $routeParams = [],
        private readonly string $tone = 'blue',
        private readonly array $context = [],
    ) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /** @return array<string, mixed> */
    public function toDatabase(object $notifiable): array
    {
        return array_merge([
            'category' => $this->category,
            'title' => $this->title,
            'message' => $this->message,
            'route_name' => $this->routeName,
            'route_params' => $this->routeParams,
            'tone' => $this->tone,
        ], $this->context);
    }
}
