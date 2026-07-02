<?php

namespace App\Services;

use App\Models\OwnerNotification;
use App\Models\User;

class OwnerNotificationService
{
    public const OWNER_EMAIL = 'pshamugia@gmail.com';

    public static function notify(string $type, ?User $actor, string $title, string $message, ?string $url = null): void
    {
        if (! $actor || strtolower($actor->email) === self::OWNER_EMAIL) {
            return;
        }

        OwnerNotification::create([
            'recipient_email' => self::OWNER_EMAIL,
            'actor_id' => $actor->id,
            'actor_name' => $actor->name,
            'actor_email' => $actor->email,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'url' => $url,
        ]);
    }
}
