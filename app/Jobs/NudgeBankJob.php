<?php

namespace App\Jobs;

use App\Mail\SendMail;
use App\Models\Auditor;
use App\Models\Bank;
use App\Models\Banker;
use App\Models\ConfirmationRequest;
use App\Models\Signatory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NudgeBankJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $auditor, $recipient, $confirmation_request;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct(Auditor $auditor, Bank $recipient, ConfirmationRequest $confirmation_request)
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
        $heading = 'Audit Confirmation Request from '.$this->auditor->company->name.' on behalf of '. $this->confirmation_request->name;
        $body = $this->messageBody();
        Mail::to($this->recipient['email'])->send(new SendMail($this->recipient['name'], $subject, $heading, $body));
    }

    private  function messageBody()
    {
        return "This is an audit confirmation request from " . $this->auditor->company->name . "
                    <br/><br/>Reach out to Ea-Auditor Support if you have any complaints or enquiries.
                    <br/><br/>Thanks";
    }

}
