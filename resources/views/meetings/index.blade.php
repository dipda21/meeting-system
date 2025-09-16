{{-- resources/views/meetings/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-wrapper me-3">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div>
                        <h2 class="page-title mb-1">Manajemen Rapat</h2>
                        <p class="page-subtitle mb-0">Kelola semua rapat Bank Sulselbar</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('meetings.create') }}" class="btn btn-create">
                    <i class="fas fa-plus me-2"></i> Buat Rapat Baru
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-primary">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ $meetings->count() }}</h3>
                        <p>Total Rapat</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-success">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ $meetings->where('meeting_date', '>=', now()->startOfDay())->count() }}</h3>
                        <p>Rapat Mendatang</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-warning">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ $meetings->sum(function ($meeting) {return $meeting->participants->count();}) }}</h3>
                        <p>Total Peserta</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-info">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ $meetings->where('meeting_date', now()->format('Y-m-d'))->count() }}</h3>
                        <p>Rapat Hari Ini</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="card search-card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="search-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="searchInput" class="form-control search-input"
                                placeholder="Cari rapat berdasarkan judul, lokasi, atau agenda...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select id="statusFilter" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="upcoming">Mendatang</option>
                            <option value="today">Hari Ini</option>
                            <option value="past">Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                            <i class="fas fa-undo me-2"></i>Reset Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAMBAHKAN FILTER TABS DI SINI --}}
        <div class="mb-4">
            <ul class="nav nav-pills approval-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ request('filter') != 'pending' && request('filter') != 'approved' && request('filter') != 'rejected' ? 'active' : '' }}"
                        href="{{ route('meetings.index') }}">
                        <i class="fas fa-list me-2"></i>All Meetings
                    </a>
                </li>
                @if (auth()->user()->is_admin)
                    <li class="nav-item">
                        <a class="nav-link {{ request('filter') == 'pending' ? 'active' : '' }}"
                            href="{{ route('meetings.index', ['filter' => 'pending']) }}">
                            <i class="fas fa-hourglass-half me-2"></i>Pending Approval
                            <span
                                class="badge bg-warning ms-2">{{ $meetings->where('approval_status', 'pending')->count() }}</span>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link {{ request('filter') == 'approved' ? 'active' : '' }}"
                        href="{{ route('meetings.index', ['filter' => 'approved']) }}">
                        <i class="fas fa-check-circle me-2"></i>Approved
                        <span
                            class="badge bg-success ms-2">{{ $meetings->where('approval_status', 'approved')->count() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('filter') == 'rejected' ? 'active' : '' }}"
                        href="{{ route('meetings.index', ['filter' => 'rejected']) }}">
                        <i class="fas fa-times-circle me-2"></i>Rejected
                        <span
                            class="badge bg-danger ms-2">{{ $meetings->where('approval_status', 'rejected')->count() }}</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Meetings Table -->
        <div class="card table-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Daftar Rapat
                    </h5>
                    <div class="table-actions">
                        <form action="{{ route('meetings.export') }}" method="GET" style="display: inline;">
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-download me-2"></i>Export
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="meetingsTable">
                        <thead class="table-header">
                            <tr>
                                <th class="sortable" data-sort="title">
                                    <i class="fas fa-tag me-2"></i>Judul Rapat
                                    <i class="fas fa-sort sort-icon"></i>
                                </th>
                                <th class="sortable" data-sort="date">
                                    <i class="fas fa-calendar me-2"></i>Tanggal
                                    <i class="fas fa-sort sort-icon"></i>
                                </th>
                                <th>
                                    <i class="fas fa-clock me-2"></i>Waktu
                                </th>
                                <th>
                                    <i class="fas fa-map-marker-alt me-2"></i>Lokasi
                                </th>
                                <th>
                                    <i class="fas fa-clipboard-list me-2"></i>Agenda
                                </th>
                                <th class="sortable" data-sort="participants">
                                    <i class="fas fa-users me-2"></i>Peserta
                                    <i class="fas fa-sort sort-icon"></i>
                                </th>
                                <th>
                                    <i class="fas fa-user-tie me-2"></i>Dibuat Oleh
                                </th>

                                <th>
                                    <i class="fas fa-check-double me-2"></i>Status Approval
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-cogs me-2"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($meetings as $meeting)
                                <tr class="meeting-row" data-meeting-id="{{ $meeting->id }}">
                                    <td class="fw-semibold">
                                        <div class="meeting-title">
                                            {{ $meeting->title }}
                                            @if ($meeting->meeting_date >= now()->startOfDay())
                                                <span class="badge badge-upcoming ms-2">Mendatang</span>
                                            @elseif($meeting->meeting_date == now()->format('Y-m-d'))
                                                <span class="badge badge-today ms-2">Hari Ini</span>
                                            @else
                                                <span class="badge badge-past ms-2">Selesai</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="date-wrapper">
                                            <strong>{{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y') }}</strong>
                                            <small
                                                class="text-muted d-block">{{ \Carbon\Carbon::parse($meeting->meeting_date)->diffForHumans() }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="time-wrapper">
                                            <i class="fas fa-play text-success me-1"></i>{{ $meeting->start_time }}
                                            <br>
                                            <i class="fas fa-stop text-danger me-1"></i>{{ $meeting->end_time }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="location-wrapper">
                                            <i class="fas fa-building text-primary me-2"></i>
                                            {{ $meeting->location }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="agenda-wrapper">
                                            {{ Str::limit($meeting->agenda, 50) }}
                                            @if (strlen($meeting->agenda) > 50)
                                                <a href="#" class="text-primary ms-1" data-bs-toggle="tooltip"
                                                    title="{{ $meeting->agenda }}">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="participants-wrapper">
                                            <span class="participants-count">
                                                <i class="fas fa-users text-info me-1"></i>
                                                <strong>{{ $meeting->participants->count() }}</strong> orang
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="creator-wrapper">
                                            <i class="fas fa-user-circle text-secondary me-2"></i>
                                            {{ $meeting->creator->name }}
                                        </div>
                                    </td>

                                    <td>
                                        <div class="creator-wrapper">
                                            <i class="fas fa-user-circle text-secondary me-2"></i>
                                            {{ $meeting->creator->name }}
                                        </div>
                                    </td>
                                   
                                    <td>
                                        @if($meeting->approval_status == 'pending')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-hourglass-half me-1"></i>Pending
                                            </span>
                                        @elseif($meeting->approval_status == 'approved')  
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Approved
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i>Rejected
                                            </span>
                                        @endif
                                    </td>
                                    {{-- SAMPAI SINI --}}
                                    <td class="text-center">
                                        <div class="action-buttons">
                                    <td class="text-center">
                                        <div class="action-buttons">
                                            <a href="{{ route('meetings.show', $meeting->id) }}"
                                                class="btn btn-sm btn-info me-1" data-bs-toggle="tooltip"
                                                title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('meetings.edit', $meeting->id) }}"
                                                class="btn btn-sm btn-warning me-1" data-bs-toggle="tooltip"
                                                title="Edit Rapat">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger"
                                                onclick="deleteMeeting({{ $meeting->id }})" data-bs-toggle="tooltip"
                                                title="Hapus Rapat">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $meeting->id }}"
                                                action="{{ route('meetings.destroy', $meeting->id) }}" method="POST"
                                                class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                    @if (auth()->user()->is_admin && $meeting->approval_status == 'pending')
                                        <button class="btn btn-sm btn-success me-1"
                                            onclick="approveMeeting({{ $meeting->id }})" data-bs-toggle="tooltip"
                                            title="Approve Meeting">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger me-1"
                                            onclick="showRejectModal({{ $meeting->id }})" data-bs-toggle="tooltip"
                                            title="Reject Meeting">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">Belum Ada Rapat</h5>
                                            <p class="text-muted">Mulai dengan membuat rapat pertama Anda</p>
                                            <a href="{{ route('meetings.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Buat Rapat Sekarang
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if ($meetings->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $meetings->links() }}
            </div>
        @endif
    </div>

    <style>
        /* Custom Styles for Meetings Index */
        :root {
            --primary-color: #1B365D;
            --secondary-color: #C41E3A;
            --accent-color: #D4AF37;
            --success-color: #16A085;
            --warning-color: #F39C12;
            --danger-color: #E74C3C;
            --info-color: #3498DB;
            --light-bg: #F8FAFC;
            --white: #FFFFFF;
            --text-dark: #2C3E50;
            --text-light: #7F8C8D;
            --border-color: #E9ECEF;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 8px 25px rgba(0, 0, 0, 0.15);
            --border-radius: 12px;
            --transition: all 0.3s ease;
        }

        /* Header Styles */
        .icon-wrapper {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: var(--shadow-md);
        }

        .page-title {
            color: var(--text-dark);
            font-weight: 700;
            margin: 0;
        }

        .page-subtitle {
            color: var(--text-light);
            font-weight: 500;
        }

        .btn-create {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: var(--transition);
            box-shadow: var(--shadow-md);
        }

        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: white;
        }

        /* Statistics Cards */
        .stat-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            margin-right: 1rem;
        }

        .stat-icon.bg-primary {
            background: var(--primary-color);
        }

        .stat-icon.bg-success {
            background: var(--success-color);
        }

        .stat-icon.bg-warning {
            background: var(--warning-color);
        }

        .stat-icon.bg-info {
            background: var(--info-color);
        }

        .stat-content h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }

        .stat-content p {
            color: var(--text-light);
            margin: 0;
            font-weight: 500;
        }

        /* Search Card */
        .search-card {
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
        }

        .search-wrapper {
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .search-input {
            padding-left: 45px;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(27, 54, 93, 0.25);
        }

        /* Table Card */
        .table-card {
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--light-bg), #ffffff);
            border-bottom: 2px solid var(--primary-color);
            padding: 1.25rem;
        }

        .card-title {
            color: var(--text-dark);
            font-weight: 600;
        }

        .table-header {
            background: var(--primary-color);
            color: white;
        }

        .table-header th {
            border: none;
            padding: 1rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .sortable {
            cursor: pointer;
            user-select: none;
            position: relative;
        }

        .sortable:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sort-icon {
            font-size: 0.8rem;
            margin-left: 0.5rem;
            opacity: 0.6;
        }

        /* Table Rows */
        .meeting-row {
            transition: var(--transition);
        }

        .meeting-row:hover {
            background-color: rgba(27, 54, 93, 0.03);
        }

        .meeting-row td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }

        /* Badges */
        .badge {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
        }

        .badge-upcoming {
            background: var(--success-color);
            color: white;
        }

        .badge-today {
            background: var(--warning-color);
            color: white;
        }

        .badge-past {
            background: var(--text-light);
            color: white;
        }

        /* Content Wrappers */
        .meeting-title {
            font-weight: 600;
            color: var(--text-dark);
        }

        .date-wrapper strong {
            color: var(--text-dark);
        }

        .time-wrapper {
            font-size: 0.9rem;
        }

        .location-wrapper,
        .creator-wrapper {
            display: flex;
            align-items: center;
        }

        .agenda-wrapper {
            max-width: 200px;
        }

        .participants-count {
            display: flex;
            align-items: center;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 0.25rem;
        }

        .btn-sm {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            transition: var(--transition);
        }

        .btn-info {
            background: var(--info-color);
            border-color: var(--info-color);
        }

        .btn-warning {
            background: var(--warning-color);
            border-color: var(--warning-color);
        }

        .btn-danger {
            background: var(--danger-color);
            border-color: var(--danger-color);
        }

        .btn-sm:hover {
            transform: translateY(-1px);
        }

        /* Empty State */
        .empty-state {
            padding: 3rem 2rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 1rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-sm {
                margin-bottom: 0.25rem;
            }

            .table-responsive {
                font-size: 0.875rem;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .meeting-row {
            animation: fadeInUp 0.5s ease-out;
        }

        /* Approval Tabs Styling */
        .approval-tabs {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 0.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }

        .approval-tabs .nav-link {
            color: var(--text-light);
            font-weight: 600;
            border: none;
            border-radius: 8px;
            margin: 0 0.25rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
        }

        .approval-tabs .nav-link:hover {
            background-color: rgba(27, 54, 93, 0.1);
            color: var(--primary-color);
        }

        .approval-tabs .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: var(--shadow-md);
        }

        .approval-tabs .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const exportBtn = document.getElementById('exportButton');

            if (exportBtn) {
                exportBtn.addEventListener('click', function() {
                    // Tampilkan spinner loading
                    exportBtn.disabled = true;
                    exportBtn.innerHTML =
                        `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Exporting...`;

                    // Buka file Excel di tab baru
                    const exportWindow = window.open("{{ route('meetings.export') }}", '_blank');

                    // Monitor apakah tab sudah tertutup
                    const interval = setInterval(() => {
                        if (exportWindow.closed) {
                            clearInterval(interval);
                            exportBtn.disabled = false;
                            exportBtn.innerHTML = `<i class="fas fa-download me-2"></i>Export`;
                        }
                    }, 1000);
                });
            }
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const table = document.getElementById('meetingsTable');
            const rows = table.querySelectorAll('tbody tr:not(.empty-state)');

            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value;
                let visibleRows = 0;

                rows.forEach(row => {
                    const title = row.querySelector('.meeting-title')?.textContent.toLowerCase() || '';
                    const location = row.querySelector('.location-wrapper')?.textContent.toLowerCase() ||
                        '';
                    const agenda = row.querySelector('.agenda-wrapper')?.textContent.toLowerCase() || '';
                    const badge = row.querySelector('.badge');

                    const matchesSearch = title.includes(searchTerm) ||
                        location.includes(searchTerm) ||
                        agenda.includes(searchTerm);

                    let matchesStatus = true;
                    if (statusValue && badge) {
                        const badgeClass = badge.className;
                        matchesStatus = badgeClass.includes('badge-' + statusValue);
                    }

                    if (matchesSearch && matchesStatus) {
                        row.style.display = '';
                        visibleRows++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Show/hide empty state
                const emptyState = table.querySelector('.empty-state');
                if (emptyState) {
                    emptyState.style.display = visibleRows === 0 ? '' : 'none';
                }
            }

            searchInput.addEventListener('input', filterTable);
            statusFilter.addEventListener('change', filterTable);

            // Sort functionality
            document.querySelectorAll('.sortable').forEach(header => {
                header.addEventListener('click', function() {
                    const sortBy = this.dataset.sort;
                    sortTable(sortBy);
                });
            });
        });

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = '';

            // Show all rows
            const rows = document.querySelectorAll('#meetingsTable tbody tr');
            rows.forEach(row => {
                row.style.display = '';
            });
        }

        function deleteMeeting(meetingId) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus rapat ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#E74C3C',
                cancelButtonColor: '#7F8C8D',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + meetingId).submit();
                }
            });
        }

        function exportData() {
            // Implementation for data export
            Swal.fire({
                title: 'Export Data',
                text: 'Fitur export akan segera tersedia',
                icon: 'info',
                confirmButtonColor: '#1B365D'
            });
        }

        function sortTable(column) {
            // Basic sort implementation
            const table = document.getElementById('meetingsTable');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr:not(.empty-state)'));

            rows.sort((a, b) => {
                let aText = '',
                    bText = '';

                switch (column) {
                    case 'title':
                        aText = a.querySelector('.meeting-title')?.textContent || '';
                        bText = b.querySelector('.meeting-title')?.textContent || '';
                        break;
                    case 'date':
                        aText = a.cells[1]?.textContent || '';
                        bText = b.cells[1]?.textContent || '';
                        break;
                    case 'participants':
                        aText = a.querySelector('.participants-count strong')?.textContent || '0';
                        bText = b.querySelector('.participants-count strong')?.textContent || '0';
                        return parseInt(aText) - parseInt(bText);
                }

                return aText.localeCompare(bText);
            });

            rows.forEach(row => tbody.appendChild(row));
        }

        function approveMeeting(meetingId) {
            Swal.fire({
                title: 'Approve Meeting?',
                text: 'Meeting akan disetujui dan undangan WhatsApp akan dikirim',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16A085',
                cancelButtonColor: '#7F8C8D',
                confirmButtonText: 'Ya, Approve!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit approve form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/meetings/${meetingId}/approve`;
                    form.innerHTML = '@csrf';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function showRejectModal(meetingId) {
            Swal.fire({
                title: 'Reject Meeting',
                input: 'textarea',
                inputLabel: 'Alasan penolakan (opsional)',
                inputPlaceholder: 'Masukkan alasan penolakan...',
                showCancelButton: true,
                confirmButtonText: 'Reject',
                confirmButtonColor: '#E74C3C',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit reject form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/meetings/${meetingId}/reject`;
                    form.innerHTML = `
                @csrf
                <input type="hidden" name="rejection_reason" value="${result.value || ''}">
            `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>



    <!-- Add SweetAlert2 for better alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
