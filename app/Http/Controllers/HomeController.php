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
            $confirmation_request  = ConfirmationRequest::where([['auditor_id', $auditor->id]])->orderBy('confirmation_status', 'ASC')->get();
            return view('home.auditor', compact('auditor', 'confirmation_request'));
        }
        $number_of_auditors = Auditor::where('company_id', $auditor->company_id)->count();
        $pending_requests = ConfirmationRequest::where([['confirmation_status', 0], ['company_id', $auditor->company_id]])->latest()->get();
        return view('home.admin', compact('number_of_auditors', 'pending_requests', 'auditor'));
    }
}
