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

class ConfirmationRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $auditor, $recipient, $confirmation_request;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct(Auditor $auditor, Signatory $recipient, ConfirmationRequest $confirmation_request)
    {
        $this->recipient = $recipient;
        $this->auditor = $auditor;
        $this->confirmation_request = $confirmation_request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subject = 'Audit Confirmation Request';
        $heading = 'Bank Circularization from '.$this->auditor->company->name;
        $body = $this->messageBody();
        Mail::to($this->recipient['email'])->send(new SendMail($this->recipient['name'], $subject, $heading, $body));
    }

    private  function messageBody()
    {
        $url = "http://127.0.0.1:8000/confirmation-requests/". encrypt_helper($this->confirmation_request->id) . "/signatories/". encrypt_helper($this->recipient->id);
        return "This is an audit confirmation request from " . $this->auditor->company->name . "
                    <br/><br/>Please click on the button below see request Details .
                    <br/><br/><b><a href=".$url.">View Request</a></b><br />
                    <br/><br/>Please use the OTP below to continue the process.
                    <br/><br/><b>".$this->recipient->token."</b>
                    <br/><br/>If you did not request such action, no further action is required.
                    <br/><br/>Reach out to Ea-Auditor Support if you have any complaints or enquiries.
                    <br/><br/>Thanks";
    }

    // <br/><br/><b><a href=" . config('app.url') . "/confirmation-requests/" . encrypt_helper($this->confirmation_request->id) . "/client-view" . ">View Request</a></b><br />

}
