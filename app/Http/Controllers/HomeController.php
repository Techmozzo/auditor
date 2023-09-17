<?php

namespace App\Http\Controllers;

use App\Models\Auditor;
use App\Models\ConfirmationRequest;

class HomeController extends Controller
{
    /**
     * HomeController constructor.
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $auditor = auth()->user();
        if (!$auditor->hasRole('admin')) {
            $confirmation_requests  = ConfirmationRequest::where([['auditor_id', $auditor->id]])->orderBy('confirmation_status', 'ASC')->get();
            $number_of_pending_requests = ConfirmationRequest::where([['auditor_id', $auditor->id]])->count();
            return view('home.auditor', compact('auditor', 'confirmation_requests', 'number_of_pending_requests'));
        }
        $number_of_auditors = Auditor::where('company_id', $auditor->company_id)->count();
        $pending_requests = ConfirmationRequest::where([['confirmation_status', 0], ['company_id', $auditor->company_id]])->latest()->get();
        return view('home.admin', compact('number_of_auditors', 'auditor', 'pending_requests'));
    }
}
