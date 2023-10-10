<?php

namespace App\Jobs;

use App\Mail\SendMail;
use App\Models\Auditor;
use App\Models\ConfirmationRequest;
use App\Models\Signatory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class DeclinedConfirmationRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $recipient, $signatory, $confirmation_request;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct(Auditor $recipient, Signatory $signatory, ConfirmationRequest $confirmation_request)
    {
        $this->signatory = $signatory;
        $this->recipient = $recipient;
        $this->confirmation_request = $confirmation_request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subject = 'Audit Confirmation Request Decline';
        $heading = 'Audit Confirmation Request Decline';
        $body = $this->messageBody();
        Mail::to($this->recipient['email'])->send(new SendMail($this->recipient['name'], $subject, $heading, $body));
    }

    private  function messageBody()
    {
        return "Your audit confirmation request for " . $this->confirmation_request->name . " was declined by " . $this->signatory->name . "
                    <br/><br/>Reach out to Ea-Auditor Support if you have any complaints or enquiries.
                    <br/><br/>Thanks";
    }
}
