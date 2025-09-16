<?php

namespace App\Http\Controllers;

use App\Exports\MeetingMinutesExport;
use Maatwebsite\Excel\Facades\Excel;

class MeetingMinuteExportController extends Controller
{
    public function export()
    {
        return Excel::download(new MeetingMinutesExport, 'notulen_rapat.xlsx');
    }
}
