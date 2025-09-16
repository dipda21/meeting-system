{{-- resources/views/meetings/edit.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <!-- Header Card -->
                <div class="meeting-header-card mb-4">
                    <div class="meeting-header-content">
                        <div class="meeting-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="meeting-header-text">
                            <h1 class="meeting-title">Edit Meeting</h1>
                            <p class="meeting-subtitle">Update meeting details and configuration</p>
                            @if ($meeting->title)
                                <div class="current-meeting-badge">
                                    <i class="fas fa-calendar-alt me-2"></i>{{ $meeting->title }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="meeting-header-decoration"></div>
                </div>

                <!-- Main Form Card -->
                <div class="meeting-form-card">
                    <form action="{{ route('meetings.update', $meeting->id) }}" method="POST" class="meeting-form">
                        @csrf
                        @method('PUT')

                        <!-- Meeting Basic Information Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <h3 class="section-title">Meeting Information</h3>
                            </div>

                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="title" class="form-label">
                                            <i class="fas fa-heading me-2"></i>Meeting Title
                                            <span class="required">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title', $meeting->title) }}"
                                            placeholder="Enter meeting title..." required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="description" class="form-label">
                                            <i class="fas fa-align-left me-2"></i>Description
                                        </label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                            rows="4" placeholder="Describe the purpose and agenda of the meeting...">{{ old('description', $meeting->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Date & Time Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <h3 class="section-title">Schedule</h3>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="meeting_date" class="form-label">
                                            <i class="fas fa-calendar me-2"></i>Date
                                            <span class="required">*</span>
                                        </label>
                                        {{-- FIXED: Ganti type dari "meeting_date" ke "date" --}}
                                        <input type="date"
                                            class="form-control @error('meeting_date') is-invalid @enderror"
                                            id="meeting_date" name="meeting_date"
                                            value="{{ old('meeting_date', $meeting->meeting_date ? (\Carbon\Carbon::parse($meeting->meeting_date)->format('Y-m-d')) : '') }}"
                                            required>
                                        @error('meeting_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="start_time" class="form-label">
                                            <i class="fas fa-play me-2"></i>Start Time
                                            <span class="required">*</span>
                                        </label>
                                        <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                                            id="start_time" name="start_time"
                                            value="{{ old('start_time', $meeting->start_time ? (\Carbon\Carbon::parse($meeting->start_time)->format('H:i')) : '') }}"
                                            required>
                                        @error('start_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="end_time" class="form-label">
                                            <i class="fas fa-stop me-2"></i>End Time
                                            <span class="required">*</span>
                                        </label>
                                        <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                                            id="end_time" name="end_time"
                                            value="{{ old('end_time', $meeting->end_time ? (\Carbon\Carbon::parse($meeting->end_time)->format('H:i')) : '') }}"
                                            required>
                                        @error('end_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <h3 class="section-title">Location</h3>
                            </div>

                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="location" class="form-label">
                                            <i class="fas fa-building me-2"></i>Meeting Venue
                                        </label>
                                        <input type="text" class="form-control @error('location') is-invalid @enderror"
                                            id="location" name="location"
                                            value="{{ old('location', $meeting->location) }}"
                                            placeholder="e.g., Conference Room A, Bank Sulselbar HQ, or Virtual Meeting">
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Agenda Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-list-ul"></i>
                                </div>
                                <h3 class="section-title">Agenda</h3>
                            </div>

                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="agenda" class="form-label">
                                            <i class="fas fa-tasks me-2"></i>Meeting Agenda
                                        </label>
                                        <textarea class="form-control @error('agenda') is-invalid @enderror" id="agenda" name="agenda" rows="6"
                                            placeholder="1. Opening remarks&#10;2. Review of previous meeting minutes&#10;3. Financial updates&#10;4. New business items&#10;5. Q&A session&#10;6. Next steps and closing">{{ old('agenda', $meeting->agenda) }}</textarea>
                                        @error('agenda')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Participants Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h3 class="section-title">Participants</h3>
                                <div class="section-badge">
                                    <span class="participant-count">{{ $meeting->participants->count() }} Selected</span>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-user-friends me-2"></i>Update Meeting Participants
                                        </label>
                                        <div class="participants-grid">
                                            @if (isset($users) && $users->count() > 0)
                                                @foreach ($users as $user)
                                                    @php
                                                        $isSelected =
                                                            $meeting->participants->contains('user_id', $user->id) ||
                                                            in_array($user->id, old('participants', []));
                                                    @endphp
                                                    <div class="participant-card">
                                                        <input type="checkbox" class="participant-checkbox"
                                                            id="participant_{{ $user->id }}" name="participants[]"
                                                            value="{{ $user->id }}"
                                                            {{ $isSelected ? 'checked' : '' }}>
                                                        <label for="participant_{{ $user->id }}"
                                                            class="participant-label">
                                                            <div class="participant-avatar">
                                                                <i class="fas fa-user"></i>
                                                            </div>
                                                            <div class="participant-info">
                                                                <div class="participant-name">{{ $user->name }}</div>
                                                                <div class="participant-email">{{ $user->email }}</div>
                                                                @if ($meeting->participants->contains('user_id', $user->id))
                                                                    <div class="participant-status">
                                                                        <i class="fas fa-check-circle text-success"></i>
                                                                        <small class="text-success">Current
                                                                            participant</small>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="participant-checkmark">
                                                                <i class="fas fa-check"></i>
                                                            </div>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="no-users-message">
                                                    <i class="fas fa-info-circle"></i>
                                                    <p>No users available to invite to the meeting.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- FIXED: Hapus section Meeting Status yang duplikat dan salah --}}
                        {{-- Meeting Approval Status Section - HANYA untuk Admin --}}
                        @php
                            $isAdmin = Auth::user()->hasRole('admin') || Auth::user()->role === 'admin';
                        @endphp

                        @if ($isAdmin)
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h3 class="section-title">Meeting Approval Status</h3>
                                    <div class="section-badge">
                                        <span class="badge badge-{{ $meeting->approval_status === 'approved' ? 'success' : ($meeting->approval_status === 'rejected' ? 'danger' : 'warning') }}">
                                            Current: {{ ucfirst($meeting->approval_status) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="approval_status" class="form-label">
                                                <i class="fas fa-flag me-2"></i>Approval Status
                                                <span class="required">*</span>
                                            </label>
                                            {{-- FIXED: Nama field harus "approval_status", bukan "status" --}}
                                            <select class="form-control @error('approval_status') is-invalid @enderror"
                                                id="approval_status" name="approval_status" required>
                                                <option value="pending"
                                                    {{ old('approval_status', $meeting->approval_status) == 'pending' ? 'selected' : '' }}>
                                                    Pending Approval</option>
                                                <option value="approved"
                                                    {{ old('approval_status', $meeting->approval_status) == 'approved' ? 'selected' : '' }}>
                                                    Approved</option>
                                                <option value="rejected"
                                                    {{ old('approval_status', $meeting->approval_status) == 'rejected' ? 'selected' : '' }}>
                                                    Rejected</option>
                                            </select>
                                            @error('approval_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Only approved meetings will send WhatsApp invitations to participants.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Show approval details if exists --}}
                                    @if($meeting->approved_at)
                                    <div class="col-md-6">
                                        <div class="approval-details">
                                            <h6 class="text-muted mb-2">
                                                <i class="fas fa-info me-2"></i>Approval Details
                                            </h6>
                                            <div class="approval-info">
                                                <small class="text-muted d-block">
                                                    <strong>Approved by:</strong> {{ $meeting->approver->name ?? 'N/A' }}
                                                </small>
                                                <small class="text-muted d-block">
                                                    <strong>Approved at:</strong> {{ $meeting->approved_at->format('d M Y H:i') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="action-buttons-left">
                                    <a href="{{ route('meetings.index') }}" class="btn btn-outline-secondary btn-lg">
                                        <i class="fas fa-arrow-left me-2"></i>Back to Meetings
                                    </a>
                                    <a href="{{ route('meetings.show', $meeting->id) }}"
                                        class="btn btn-outline-info btn-lg ms-2">
                                        <i class="fas fa-eye me-2"></i>View Details
                                    </a>
                                </div>
                                <div class="action-buttons-right">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save me-2"></i>Update Meeting
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Meeting Edit Form Styles - Extends Create Form Styles */
        .meeting-header-card {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #A91E47 100%);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-medium);
            color: white;
            position: relative;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .meeting-header-content {
            padding: 2.5rem;
            display: flex;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .meeting-header-decoration {
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .meeting-icon {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 1.5rem;
            margin-right: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .meeting-icon i {
            font-size: 2.5rem;
            color: var(--accent-color);
        }

        .meeting-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .meeting-subtitle {
            font-size: 1.1rem;
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
        }

        .current-meeting-badge {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            padding: 0.5rem 1rem;
            margin-top: 1rem;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            backdrop-filter: blur(10px);
        }

        .meeting-form-card {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-medium);
            overflow: hidden;
        }

        .meeting-form {
            padding: 0;
        }

        .form-section {
            padding: 2.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            justify-content: space-between;
        }

        .section-icon {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            color: white;
            border-radius: 12px;
            padding: 1rem;
            margin-right: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(196, 30, 58, 0.2);
        }

        .section-icon i {
            font-size: 1.25rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }

        .section-badge {
            margin-left: auto;
        }

        .participant-count {
            background: var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        /* Badge styles for approval status */
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .badge-success {
            background: #28a745;
            color: white;
        }

        .badge-warning {
            background: #ffc107;
            color: #212529;
        }

        .badge-danger {
            background: #dc3545;
            color: white;
        }

        .approval-details {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
        }

        .approval-info small {
            margin-bottom: 0.25rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
            font-size: 1rem;
            display: flex;
            align-items: center;
        }

        .required {
            color: var(--danger-color);
            margin-left: 0.25rem;
        }

        .form-control {
            border: 2px solid #E9ECEF;
            border-radius: var(--border-radius);
            padding: 1rem 1.25rem;
            font-size: 1rem;
            transition: var(--transition);
            background: var(--white);
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(196, 30, 58, 0.15);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
        }

        .invalid-feedback {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .form-text {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }

        /* Participants Grid */
        .participants-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .participant-card {
            position: relative;
        }

        .participant-checkbox {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .participant-label {
            display: flex;
            align-items: center;
            padding: 1.25rem;
            background: var(--white);
            border: 2px solid #E9ECEF;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .participant-label:hover {
            border-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-light);
        }

        .participant-checkbox:checked+.participant-label {
            border-color: var(--secondary-color);
            background: linear-gradient(135deg, rgba(196, 30, 58, 0.05) 0%, rgba(196, 30, 58, 0.02) 100%);
        }

        .participant-avatar {
            background: var(--secondary-color);
            color: white;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .participant-info {
            flex: 1;
        }

        .participant-name {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .participant-email {
            color: var(--text-light);
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .participant-status {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .participant-checkmark {
            color: var(--secondary-color);
            font-size: 1.25rem;
            opacity: 0;
            transition: var(--transition);
        }

        .participant-checkbox:checked+.participant-label .participant-checkmark {
            opacity: 1;
        }

        .no-users-message {
            text-align: center;
            padding: 3rem;
            color: var(--text-light);
        }

        .no-users-message i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Form Actions */
        .form-actions {
            padding: 2.5rem;
            background: linear-gradient(135deg, #F8FAFC 0%, rgba(196, 30, 58, 0.02) 100%);
        }

        .action-buttons-left,
        .action-buttons-right {
            display: flex;
            gap: 0.5rem;
        }

        .btn {
            border-radius: var(--border-radius);
            padding: 1rem 2rem;
            font-weight: 600;
            transition: var(--transition);
            border: none;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #A91E47 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(196, 30, 58, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(196, 30, 58, 0.4);
            background: linear-gradient(135deg, #A91E47 0%, var(--secondary-color) 100%);
        }

        .btn-outline-secondary {
            border: 2px solid #E9ECEF;
            color: var(--text-dark);
            background: var(--white);
        }

        .btn-outline-secondary:hover {
            border-color: var(--secondary-color);
            color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-light);
        }

        .btn-outline-info {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: var(--white);
        }

        .btn-outline-info:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-light);
        }

        /* Changed field indicator */
        .form-control.changed {
            border-left: 4px solid #ffc107;
            background: rgba(255, 193, 7, 0.05);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .meeting-header-content {
                padding: 2rem;
                flex-direction: column;
                text-align: center;
            }

            .meeting-icon {
                margin-right: 0;
                margin-bottom: 1rem;
            }

            .meeting-title {
                font-size: 2rem;
            }

            .form-section {
                padding: 2rem 1.5rem;
            }

            .section-header {
                flex-direction: column;
                text-align: center;
                align-items: center;
            }

            .section-icon {
                margin-right: 0;
                margin-bottom: 1rem;
            }

            .section-badge {
                margin-left: 0;
                margin-top: 1rem;
            }

            .participants-grid {
                grid-template-columns: 1fr;
            }

            .form-actions .d-flex {
                flex-direction: column;
                gap: 1rem;
            }

            .action-buttons-left,
            .action-buttons-right {
                width: 100%;
                justify-content: center;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Animation for form loading */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .meeting-form-card {
            animation: slideInUp 0.6s ease-out;
        }

        .form-section {
            animation: slideInUp 0.6s ease-out;
            animation-fill-mode: both;
        }

        .form-section:nth-child(1) {
            animation-delay: 0.1s;
        }

        .form-section:nth-child(2) {
            animation-delay: 0.2s;
        }

        .form-section:nth-child(3) {
            animation-delay: 0.3s;
        }

        .form-section:nth-child(4) {
            animation-delay: 0.4s;
        }

        .form-section:nth-child(5) {
            animation-delay: 0.5s;
        }

        .form-section:nth-child(6) {
            animation-delay: 0.6s;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation enhancement
            const form = document.querySelector('.meeting-form');
            const requiredFields = form.querySelectorAll('[required]');

            form.addEventListener('submit', function(e) {
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                // Validate time logic
                const startTimeInput = document.getElementById('start_time');
                const endTimeInput = document.getElementById('end_time');
                const startTime = startTimeInput.value;
                const endTime = endTimeInput.value;

                if (startTime && endTime && startTime >= endTime) {
                    endTimeInput.classList.add('is-invalid');
                    alert('End time must be after start time.');
                    isValid = false;
                    e.preventDefault();
                }

                if (!isValid) {
                    e.preventDefault();
                    // Scroll to first invalid field
                    const firstInvalid = form.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstInvalid.focus();
                    }
                }
            });

            // Real-time validation
            requiredFields.forEach(field => {
                field.addEventListener('blur', function() {
                    if (this.value.trim()) {
                        this.classList.remove('is-invalid');
                    }
                });
            });

            // Participant selection counter
            const participantCheckboxes = document.querySelectorAll('.participant-checkbox');
            const participantCountElement = document.querySelector('.participant-count');

            const updateParticipantCount = () => {
                const checkedCount = document.querySelectorAll('.participant-checkbox:checked').length;
                if (participantCountElement) {
                    participantCountElement.textContent = `${checkedCount} Selected`;
                }
            };

            participantCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateParticipantCount);
            });

            // Initialize participant count
            updateParticipantCount();

            // Highlight changes
            const originalValues = {};
            const formInputs = form.querySelectorAll('input, textarea, select');

            formInputs.forEach(input => {
                originalValues[input.name] = input.value;
            });

            // Add change detection
            formInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.value !== originalValues[this.name]) {
                        this.classList.add('changed');
                    } else {
                        this.classList.remove('changed');
                    }
                });
            });
        });
    </script>
@endsection