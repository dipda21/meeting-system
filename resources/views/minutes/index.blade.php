{{-- resources/views/minutes/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex align-items-center mb-4">
            <div class="bg-primary rounded-circle p-3 me-3" style="width: 60px; height: 60px;">
                <i class="fas fa-file-alt text-white fs-4"></i>
            </div>
            <div>
                <h2 class="mb-1 fw-bold text-dark">Notulen Rapat</h2>
                <p class="text-muted mb-0">Kelola semua notulen Bank Sulselbar</p>
            </div>
            <div class="ms-auto">
                <a href="{{ route('meeting-minutes.create') }}" class="btn btn-primary px-4 py-2 rounded-pill">
                    <i class="fas fa-plus me-2"></i>Buat Notulen Baru
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-primary rounded-circle p-3 me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold">{{ $totalMinutes ?? '1' }}</h3>
                            <p class="text-muted mb-0 small">Total Notulen</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-success rounded-circle p-3 me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold">{{ $upcomingMinutes ?? '1' }}</h3>
                            <p class="text-muted mb-0 small">Notulen Mendatang</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-warning rounded-circle p-3 me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold">{{ $totalParticipants ?? '1' }}</h3>
                            <p class="text-muted mb-0 small">Total Peserta</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-info rounded-circle p-3 me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold">{{ $todayMinutes ?? '0' }}</h3>
                            <p class="text-muted mb-0 small">Notulen Hari Ini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="row mb-4">
            <div class="col-md-8">
                <form method="GET" action="{{ route('meeting-minutes.index') }}" class="position-relative">
                    <i class="fas fa-search position-absolute text-muted" style="left: 15px; top: 12px;"></i>
                    <input type="text" name="search" class="form-control ps-5 py-2 border-0 shadow-sm"
                        placeholder="Cari notulen berdasarkan judul, lokasi, atau agenda..." value="{{ request('search') }}"
                        style="border-radius: 25px;">
                </form>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select py-2 border-0 shadow-sm" style="border-radius: 25px;">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="final" {{ request('status') == 'final' ? 'selected' : '' }}>Final</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100 py-2" style="border-radius: 25px;">
                    <i class="fas fa-redo me-2"></i>Reset Filter
                </button>
            </div>
        </div>

        <!-- Table Section -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-list me-2"></i>Daftar Notulen</h5>
                <form action="{{ route('meeting-minutes.export') }}" method="GET" style="display:inline;">
                  
                </form>

            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 ps-4 py-3">
                                    <i class="fas fa-tag me-2"></i>Judul Notulen
                                </th>
                                <th class="border-0 py-3">
                                    <i class="fas fa-calendar me-2"></i>Tanggal
                                </th>
                                <th class="border-0 py-3">
                                    <i class="fas fa-clock me-2"></i>Waktu
                                </th>
                                <th class="border-0 py-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>Konten
                                </th>
                                <th class="border-0 py-3">
                                    <i class="fas fa-clipboard-list me-2"></i>Tindak Lanjut
                                </th>
                                <th class="border-0 py-3">
                                    <i class="fas fa-users me-2"></i>Peserta
                                </th>
                                <th class="border-0 py-3">
                                    <i class="fas fa-user-edit me-2"></i>Dibuat Oleh
                                </th>
                                <th class="border-0 py-3 text-center">
                                    <i class="fas fa-cogs me-2"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($minutes as $minute)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                                <i class="fas fa-file-alt text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">Notulen Rapat #{{ $minute->id }}</h6>
                                                <small
                                                    class="text-muted">{{ Str::limit($minute->content ?? 'Konten notulen rapat', 50) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-light text-dark">
                                            {{ $minute->meeting_date ? \Carbon\Carbon::parse($minute->meeting_date)->format('d M Y') : date('d M Y') }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <i class="fas fa-clock text-muted me-1"></i>
                                        {{ $minute->created_at ? $minute->created_at->format('H:i') : date('H:i') }}
                                    </td>
                                    <td class="py-3">
                                        <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                        {{ Str::limit($minute->content ?? 'Konten notulen', 30) }}
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            {{ $minute->action_items ?? 'Tindak lanjut' }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-group">
                                                @for ($i = 0; $i < min(3, $minute->participants_count ?? 5); $i++)
                                                    <div class="avatar avatar-sm">
                                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                                            style="width: 30px; height: 30px; margin-left: -5px;">
                                                            <i class="fas fa-user text-white"
                                                                style="font-size: 12px;"></i>
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                            @if (($minute->participants_count ?? 5) > 3)
                                                <small
                                                    class="text-muted ms-2">+{{ ($minute->participants_count ?? 5) - 3 }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-2"
                                                style="width: 35px; height: 35px;">
                                                <i class="fas fa-user text-white" style="font-size: 14px;"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $minute->creator->name ?? 'Admin' }}</h6>
                                                <small
                                                    class="text-muted">{{ $minute->created_at ? $minute->created_at->format('d M Y') : date('d M Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('meeting-minutes.show', $minute->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('meeting-minutes.edit', $minute->id) }}"
                                                class="btn btn-sm btn-outline-success" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if ($minute->file_path)
                                                <a href="{{ asset('storage/' . $minute->file_path) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-info" title="Unduh File">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif
                                            <form action="{{ route('meeting-minutes.destroy', $minute->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus notulen ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-file-alt fa-3x mb-3 opacity-25"></i>
                                            <h5>Belum ada notulen</h5>
                                            <p>Mulai buat notulen rapat pertama Anda</p>
                                            <a href="{{ route('meeting-minutes.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Buat Notulen
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($minutes instanceof \Illuminate\Pagination\LengthAwarePaginator && $minutes->hasPages())
                <div class="card-footer bg-white border-0">
                    {{ $minutes->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .avatar-group .avatar {
            position: relative;
            z-index: 1;
        }

        .avatar-group .avatar:not(:first-child) {
            margin-left: -10px;
        }

        .avatar-group .avatar:hover {
            z-index: 2;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .btn-group .btn {
            border-radius: 0;
        }

        .btn-group .btn:first-child {
            border-top-left-radius: 0.375rem;
            border-bottom-left-radius: 0.375rem;
        }

        .btn-group .btn:last-child {
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
        }
    </style>
@endsection
