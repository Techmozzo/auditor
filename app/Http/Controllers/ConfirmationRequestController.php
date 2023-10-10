<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmationRequest as RequestsConfirmationRequest;
use App\Http\Requests\ConfirmRequestTokenValidationRequest;
use App\Interfaces\Types;
use App\Jobs\ConfirmationRequestJob;
use App\Jobs\DeclinedConfirmationRequestJob;
use App\Jobs\NudgeBankJob;
use App\Jobs\NudgeSignatoryJob;
use App\Models\Bank;
use App\Models\ConfirmationRequest;
use App\Models\Signatory;
use App\Services\DocuSign;
use App\Services\Otp;
use App\Services\Signature;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ConfirmationRequestController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin')->only(['index', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $confirmation_requests = ConfirmationRequest::where('company_id', auth()->user()->company_id)->latest()->get();
        return view('confirmation_requests.index', compact('confirmation_requests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banks = Bank::select('id', 'name')->get();
        return view('confirmation_requests.create', compact('banks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestsConfirmationRequest $request)
    {
        $auditor = Auth::user();
        try {
            DB::beginTransaction();
            $confirmation_request = ConfirmationRequest::create([
                'name' => $request->name,
                'opening_period' => $request->opening_period,
                'closing_period' => $request->closing_period,
                'auditor_id' => $auditor->id,
                'company_id' => $auditor->company_id,
                'bank_id' => $request->bank,
            ]);

            foreach ($request->account_name as $key => $account) {
                $confirmation_request->account()->create([
                    'name' => $account,
                    'number' => $request->account_number[$key],
                ]);
            }

            foreach ($request->signatory_name as $key => $signatory) {
                $signatory = $confirmation_request->signatory()->create([
                    'name' => $signatory,
                    'email' => $request->signatory_email[$key],
                    'phone' => $request->signatory_phone[$key],
                    'token' => rand(100000, 999999)
                ]);
                // ConfirmationRequestJob::dispatch($auditor, $signatory, $confirmation_request);
            }

            $signature_request = new DocuSign();
            $file = Signature::generatePdf($confirmation_request);

            $envelop =  $signature_request->send([
                ...$file,
                'signatories' => $confirmation_request->signatory,
                'confirmation_request_id' => $confirmation_request->id
            ]);

            $confirmation_request->update([
                'file' => $file['name'],
                'envelop_id' => $envelop['envelop_id'],
                'envelop_url' => $envelop['url']
            ]);

            DB::commit();
        } catch (Throwable $t) {
            DB::rollback();
            Log::error(['Confirmtion Request' => $t->getMessage()]);
            return redirect()->back()->with(['error' => 'Unable to create request at the moment']);
        }
        return redirect()->route('home')->with(['success' => 'Confirmation Request Sent Successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ConfirmationRequest  $confirmationRequest
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $confirmation_request = ConfirmationRequest::find(decrypt_helper($id));
        if (!$confirmation_request) {
            return view('layouts.404')->with(['message' => 'Confirmation Request not found.']);
        }
        $years = getYearsInStringFormat(getYearsInRange($confirmation_request->opening_period, $confirmation_request->closing_period));
        $period = getPeriodDayAndMonth($confirmation_request->opening_period) . ' ' . $years;
        return view('confirmation_requests.show.auditor', compact('confirmation_request', 'period'));
    }

    public function viewRequest($id, $signatory_id)
    {
        $confirmation_request = ConfirmationRequest::find(decrypt_helper($id));
        if (!$confirmation_request) {
            return view('layouts.404')->with(['message' => 'Confirmation Request not found.']);
        }
        $signatory = Signatory::find(decrypt_helper($signatory_id));
        if (!$signatory) {
            return view('layouts.404')->with(['message' => 'Signatory not found.']);
        }
        $years = getYearsInStringFormat(getYearsInRange($confirmation_request->opening_period, $confirmation_request->closing_period));
        $period = getPeriodDayAndMonth($confirmation_request->opening_period) . ' ' . $years;
        return view('confirmation_requests.show.client', compact('confirmation_request', 'period', 'signatory'));
    }

    public function signRequest(ConfirmRequestTokenValidationRequest $request, $id, $signatory_id)
    {
        $confirmation_request = ConfirmationRequest::find(decrypt_helper($id));
        if (!$confirmation_request) {
            return view('layouts.404')->with(['message' => 'Confirmation Request not found.']);
        }
        $signatory = Signatory::find(decrypt_helper($signatory_id));
        if (!$signatory) {
            return view('layouts.404')->with(['message' => 'Signatory not found.']);
        }
        if ($request->isMethod('POST')) {
            if ($request->otp != $signatory->token) {
                return redirect()->back()->with(['error' => 'Invalid Token.']);
            } else {
                if (now()->timestamp > Carbon::parse($signatory->expired_at)->timestamp) {
                    Otp::send(Signatory::class, $signatory, ['email' => $signatory->email, 'confirmation_request_id' => $confirmation_request->id], 1440);
                    return redirect()->back()->with(['success' => 'A new token has been sent to your email.']);
                }
                dd('Proceed to sign now');
            }
        }
        $action = route('request.sign', ['id' => $id, 'signatory' => $signatory_id]);
        return view('layouts.otp_validation', compact('confirmation_request', 'signatory', 'action'));
    }


    public function callback(Request $request)
    {
        $confirmation_request = ConfirmationRequest::where('envelop_id', $request->envelop_id)->first();
        if ($confirmation_request) {
            if ($request->eventType == 'EnvelopeComplete') {
                $confirmation_request->update(['authorization_status' => Types::STATUS['APPROVED']]);
                NudgeBankJob::dispatch($confirmation_request->auditor, $confirmation_request->bank, $confirmation_request);
            } else {
                $signatory = Signatory::where([['confirmation_request_id', $confirmation_request->id], ['email', $request->recipient['email']]])->first();
                if($signatory){
                    if ($request->eventType == 'EnvelopeSigned') {
                        $signatory->update(['status' => Types::STATUS['APPROVED'], 'signed_at' => $request->recipient['signedDateTime']]);
                    } elseif ($request->eventType == 'EnvelopeDeclined') {
                        $signatory->update(['status' => Types::STATUS['DECLINED'], 'signed_at' => $request->recipient['declinedDateTime'], 'comment' => $request->recipient['declinedReason']]);
                        $confirmation_request->update(['authorization_status' => Types::STATUS['DECLINED']]);
                        DeclinedConfirmationRequestJob::dispatch($confirmation_request->auditor, $signatory, $confirmation_request);
                    }
                }
            }
        }
    }

    public function nudgeSignatory($id){
        $confirmation_request = ConfirmationRequest::find(decrypt_helper($id));
        if (!$confirmation_request) {
            return view('layouts.404')->with(['message' => 'Confirmation Request not found.']);
        }
        foreach($confirmation_request->signatory as $signatory){
            NudgeSignatoryJob::dispatch($confirmation_request->auditor, $signatory, $confirmation_request);
        }
        return redirect()->back()->with(['success' => 'Signatory notified successfully']);
    }
}
