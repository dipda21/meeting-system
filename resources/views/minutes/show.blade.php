{{-- resources/views/minutes/show.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <h2><i class="fas fa-file-alt"></i> Detail Notulen Rapat</h2>

        <ul class="list-group mb-3">
            <li class="list-group-item">
                <strong>Judul Meeting:</strong> {{ optional($meetingMinute->meeting)->title ?? '-' }}

            </li>

            <li class="list-group-item">
                <strong>Tanggal:</strong>
                {{ $meetingMinute->created_at ? $meetingMinute->created_at->format('d M Y') : '-' }}
            </li>

            <li class="list-group-item">
                <strong>Notulen:</strong>
                <div class="mt-2 p-3 bg-light border rounded">
                    {!! nl2br(e($meetingMinute->content)) !!}
                </div>
            </li>

         @if(!empty($meetingMinute->action_items))
            <h5><i class="fas fa-tasks me-2 text-warning"></i> Tindak Lanjut</h5>
            <div class="p-3 mb-4 bg-light border rounded">
                {!! nl2br(e($meetingMinute->action_items)) !!}
            </div>
        @endif
            <li class="list-group-item">
                <strong>Dibuat oleh:</strong> {{ optional( $meetingMinute->creator)->name ?? '-' }}
            </li>
        </ul>

        {{-- Hanya tombol download dan back --}}

        <a href="{{ route('meeting-minutes.download', ['id' => $meetingMinute->id]) }}" class="btn btn-success">
            <i class="fas fa-download"></i> Download PDF
        </a>


        <a href="{{ route('meeting-minutes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    </div>
@endsection
