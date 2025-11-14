<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEmailFinishPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request;
    protected $user;

    public function __construct($request, $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

    public function handle()
    {
        try {
            $mailService = app()->make(\App\Services\MailService::class);
            $userService = app()->make(\App\Services\UserService::class);

            $sendEmailDownload = $mailService->sendEmailFinishPayment($this->request, $this->user, $userService);
            if (is_string($sendEmailDownload)) {
                Log::warning("Email sending failed for {$this->user->user_email}: $sendEmailDownload");
            } else {
                Log::info("Email sent successfully to {$this->user->user_email} (order_id: {$this->request['order_id']})");
            }
        } catch (\Throwable $e) {
            Log::error("Exception in SendEmailFinishPayment Job for {$this->user->user_email}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
