<?php

namespace App\Exports;

use App\Models\MeetingMinute;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MeetingMinutesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return MeetingMinute::with('creator')->get()->map(function ($minute) {
            return [
                'ID' => $minute->id,
                'Tanggal' => optional($minute->meeting->meeting_date)->format('d-m-Y'),
                'Isi Notulen' => $minute->content,
                'Tindak Lanjut' => is_array($minute->action_items) ? implode(', ', $minute->action_items) : $minute->action_items,
                'Dibuat Oleh' => optional($minute->creator)->name ?? 'Admin',
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Tanggal Rapat', 'Isi Notulen', 'Tindak Lanjut', 'Dibuat Oleh'];
    }
}
