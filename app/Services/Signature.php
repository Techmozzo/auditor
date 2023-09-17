<?php

namespace App\Services;

use App\Models\ConfirmationRequest;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class Signature
{
    public static function generatePdf(ConfirmationRequest $confirmation_request): array
    {
        $years = getYearsInStringFormat(getYearsInRange($confirmation_request->opening_period, $confirmation_request->closing_period));
        $period = getPeriodDayAndMonth($confirmation_request->opening_period) .' '. $years;
        $data = ['company' => $confirmation_request->company, 'signatories' => $confirmation_request->signatory, 'period' => $period];
        $pdf = FacadePdf::loadView('signature.index', $data);
        $file_name = strtolower(str_replace(' ', '_', $confirmation_request->name)).'_'.time().'.pdf';
        $pdfPath = public_path('store/confirmation_requests/' . $file_name);
        file_put_contents($pdfPath, $pdf->output());
        return ['name' => $file_name, 'path' => $pdfPath, 'extension' => 'pdf' ];
    }
}
