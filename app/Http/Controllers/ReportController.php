<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function report1($pid)
    {
        $payment = Payment::find($pid);
        $pdf = App::make('dompdf.wrapper');

        $print = "";
        $print .= "<h2 align='center'>Payment Receipt</h2>";
        $print .= "<hr>";

        $print .= "<table>";
        $print .= "<tr><td>Receipt No:</td><td><b>$pid</b></td></tr>";
        $print .= "<tr><td>Date:</td><td><b>" . $payment->paid_date . "</b></td></tr>";
        $print .= "<tr><td>Enrollment No:</td><td><b>" . ($payment->enrollment->enroll_no ?? 'N/A') . "</b></td></tr>";
        $print .= "<tr><td>Student Name:</td><td><b>" . ($payment->enrollment->student->name ?? 'N/A') . "</b></td></tr>";
        $print .= "</table>";

        $print .= "<br><hr><br>";

        $print .= "<table border='1' width='100%' cellpadding='5'>";
        $print .= "<tr>";
        $print .= "<th>Batch</th>";
        $print .= "<th>Amount</th>";
        $print .= "</tr>";
        $print .= "<tr>";
        $print .= "<td>" . ($payment->enrollment->batch->name ?? 'N/A') . "</td>";
        $print .= "<td>" . number_format($payment->amount, 2) . "</td>";
        $print .= "</tr>";
        $print .= "</table>";

        $print .= "<br><hr><br>";

        $printedBy = Auth::check() ? Auth::user()->name : 'Guest';
        $print .= "<table>";
        $print .= "<tr><td>Printed By:</td><td><b>$printedBy</b></td></tr>";
        $print .= "<tr><td>Printed Date:</td><td><b>" . date('Y-m-d') . "</b></td></tr>";
        $print .= "</table>";

        $pdf->loadHTML($print);
        return $pdf->stream("receipt_$pid.pdf");
    }
}
?>
