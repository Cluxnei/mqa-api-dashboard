<?php

namespace App\Events;

use App\Services\AuthService;
use Exception;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserEmailVerification implements ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $email;

    public string $code;

    /**
     * Create a new event instance.
     *
     * @param string $email
     * @throws Exception
     */
    public function __construct(string $email)
    {
        $this->email = $email;
        $this->code = AuthService::generateVerificationCode();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
