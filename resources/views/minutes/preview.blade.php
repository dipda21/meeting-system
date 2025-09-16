@extends('layouts.app')

@section('content')
<div class="container">
    <h2><i class="fas fa-eye"></i> Preview Notulen</h2>

    <div class="card p-4 shadow-sm">
        {{-- Notulen --}}
        <h5><i class="fas fa-align-left me-2"></i> Konten Notulen</h5>
        <div class="p-3 mb-4 bg-light border rounded">
            {!! nl2br(e($minute->content)) !!}
        </div>

        {{-- Tambahkan Action Items --}}
        @if(!empty($minute->action_items))
            <h5><i class="fas fa-tasks me-2 text-warning"></i> Tindak Lanjut</h5>
            <div class="p-3 mb-4 bg-light border rounded">
                {!! nl2br(e($minute->action_items)) !!}
            </div>
        @endif

        <div class="text-end">
            <a href="{{ route('meeting-minutes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection
