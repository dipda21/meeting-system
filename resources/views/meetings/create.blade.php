{{-- resources/views/meetings/create.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <!-- Header Card -->
                <div class="meeting-header-card mb-4">
                    <div class="meeting-header-content">
                        <div class="meeting-icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="meeting-header-text">
                            <h1 class="meeting-title">Create New Meeting</h1>
                            <p class="meeting-subtitle">Schedule and organize your professional meeting</p>
                        </div>
                    </div>
                    <div class="meeting-header-decoration"></div>
                </div>

                <!-- Main Form Card -->
                <div class="meeting-form-card">
                    <form action="{{ route('meetings.store') }}" method="POST" class="meeting-form">
                        @csrf

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
                                            id="title" name="title" value="{{ old('title') }}"
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
                                            rows="4" placeholder="Describe the purpose and agenda of the meeting...">{{ old('description') }}</textarea>
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
                                        <label for="date" class="form-label">
                                            <i class="fas fa-calendar me-2"></i>Date
                                            <span class="required">*</span>
                                        </label>
                                        <input type="date"
                                            class="form-control @error('meeting_date') is-invalid @enderror"
                                            id="meeting_date" name="meeting_date" value="{{ old('meeting_date') }}"
                                            min="{{ date('Y-m-d') }}" required>
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
                                            id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                                        @error('start_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="meeting_date" class="form-label">
                                            <i class="fas fa-calendar me-2"></i>Date
                                            <span class="required">*</span>
                                        </label>
                                        <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                                            id="end_time" name="end_time" value="{{ old('end_time') }}" required>
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
                                            id="location" name="location" value="{{ old('location') }}"
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
                                            placeholder="1. Opening remarks&#10;2. Review of previous meeting minutes&#10;3. Financial updates&#10;4. New business items&#10;5. Q&A session&#10;6. Next steps and closing">{{ old('agenda') }}</textarea>
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
                            </div>

                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-user-friends me-2"></i>Select Meeting Participants
                                        </label>
                                        <div class="participants-grid">
                                            @if (isset($users) && $users->count() > 0)
                                                @foreach ($users as $user)
                                                    <div class="participant-card">
                                                        <input type="checkbox" class="participant-checkbox"
                                                            id="participant_{{ $user->id }}" name="participants[]"
                                                            value="{{ $user->id }}"
                                                            {{ in_array($user->id, old('participants', [])) ? 'checked' : '' }}>
                                                        <label for="participant_{{ $user->id }}"
                                                            class="participant-label">
                                                            <div class="participant-avatar">
                                                                <i class="fas fa-user"></i>
                                                            </div>
                                                            <div class="participant-info">
                                                                <div class="participant-name">{{ $user->name }}</div>
                                                                <div class="participant-email">{{ $user->email }}</div>
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

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('meetings.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-arrow-left me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-calendar-plus me-2"></i>Create Meeting
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Meeting Creation Form Styles */
        .meeting-header-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
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
        }

        .section-icon {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 12px;
            padding: 1rem;
            margin-right: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(27, 54, 93, 0.2);
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
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(27, 54, 93, 0.15);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
        }

        .invalid-feedback {
            font-size: 0.875rem;
            font-weight: 500;
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
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-light);
        }

        .participant-checkbox:checked+.participant-label {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, rgba(27, 54, 93, 0.05) 0%, rgba(27, 54, 93, 0.02) 100%);
        }

        .participant-avatar {
            background: var(--primary-color);
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
        }

        .participant-checkmark {
            color: var(--primary-color);
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
            background: linear-gradient(135deg, #F8FAFC 0%, rgba(27, 54, 93, 0.02) 100%);
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
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(27, 54, 93, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(27, 54, 93, 0.4);
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-color) 100%);
        }

        .btn-outline-secondary {
            border: 2px solid #E9ECEF;
            color: var(--text-dark);
            background: var(--white);
        }

        .btn-outline-secondary:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-light);
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
            }

            .section-icon {
                margin-right: 0;
                margin-bottom: 1rem;
            }

            .participants-grid {
                grid-template-columns: 1fr;
            }

            .form-actions .d-flex {
                flex-direction: column;
                gap: 1rem;
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-calculate end time based on start time (default 1 hour duration)
            const startTimeInput = document.getElementById('start_time');
            const endTimeInput = document.getElementById('end_time');

            startTimeInput.addEventListener('change', function() {
                if (this.value && !endTimeInput.value) {
                    const startTime = new Date('2000-01-01 ' + this.value);
                    startTime.setHours(startTime.getHours() + 1);
                    const endTime = startTime.toTimeString().slice(0, 5);
                    endTimeInput.value = endTime;
                }
            });

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
            const updateParticipantCount = () => {
                const checkedCount = document.querySelectorAll('.participant-checkbox:checked').length;
                // You can add a counter display here if needed
            };

            participantCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateParticipantCount);
            });
        });
    </script>
@endsection
