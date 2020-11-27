<?php
namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use JPush\Client;
use JPush\PushPayload;

class JPushChannel
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function send($notifiable, Notification $notification)
    {
        $notification->toJpush($notifiable, $this->client->push())->send();
    }

    public function via($notifiable)
    {
        // 开通通知的频道
        return ['database', 'mail', JPushChannel::class];
    }

    public function toJPush($notifiable, PushPayload $payload): PushPayload
    {
        return $payload
            ->setPlatform('all')
            ->addRegistrationId($notifiable->registration_id)
            ->setNotificationAlert(strip_tags($this->reply->content));
    }

















}
