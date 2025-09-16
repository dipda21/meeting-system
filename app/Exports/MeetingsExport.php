<?php

namespace App\Exports;

use App\Models\Meeting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MeetingsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Meeting::with('creator')->get()->map(function ($meeting) {
            return [
                'ID' => $meeting->id,
                'Judul' => $meeting->title,
                'Tanggal' => optional($meeting->meeting_date)->format('d-m-Y'),
                'Waktu Mulai' => $meeting->start_time,
                'Waktu Selesai' => $meeting->end_time,
                'Lokasi' => $meeting->location,
                'Agenda' => $meeting->agenda,
                'Status' => $meeting->status,
                'Dibuat Oleh' => optional($meeting->creator)->name ?? 'Admin',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID', 'Judul', 'Tanggal', 'Waktu Mulai', 'Waktu Selesai',
            'Lokasi', 'Agenda', 'Status', 'Dibuat Oleh'
        ];
    }
}
