@extends('layouts.app')

@section('title', 'Edit Meeting Minutes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Edit Meeting Minutes</h4>
                    <a href="{{ route('meeting-minutes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
                
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('meeting-minutes.update', $meetingMinute->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="meeting_id" class="form-label">Meeting <span class="text-danger">*</span></label>
                                    <select name="meeting_id" id="meeting_id" class="form-select" required>
                                        <option value="">Select Meeting</option>
                                        @foreach($meetings as $meeting)
                                            <option value="{{ $meeting->id }}" 
                                                {{ old('meeting_id', $meetingMinute->meeting_id) == $meeting->id ? 'selected' : '' }}>
                                                {{ $meeting->title }}
                                                @if(isset($meeting->meeting_date))
                                                    - {{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d/m/Y') }}
                                                @elseif(isset($meeting->scheduled_at))
                                                    - {{ \Carbon\Carbon::parse($meeting->scheduled_at)->format('d/m/Y') }}
                                                @else
                                                    - {{ $meeting->created_at->format('d/m/Y') }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="file" class="form-label">Upload New File (Optional)</label>
                                    <input type="file" name="file" id="file" class="form-control" accept=".pdf,.doc,.docx">
                                    <small class="form-text text-muted">
                                        Leave empty to keep current file. Accepted formats: PDF, DOC, DOCX (Max: 10MB)
                                    </small>
                                </div>

                                @if($meetingMinute->file_path)
                                    <div class="mb-3">
                                        <label class="form-label">Current File</label>
                                        <div class="p-2 bg-light rounded">
                                            <i class="fas fa-file-pdf text-danger"></i>
                                            <a href="{{ asset('storage/' . $meetingMinute->file_path) }}" target="_blank" class="text-decoration-none ms-2">
                                                {{ basename($meetingMinute->file_path) }}
                                            </a>
                                            <small class="text-muted d-block">
                                                Uploaded: {{ $meetingMinute->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                @endif
                                @if(Auth::user()->role === 'admin')
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="draft" {{ old('status', $meetingMinute->status ?? 'draft') == 'draft' ? 'selected' : '' }}>
                                            Draft
                                        </option>
                                        <option value="approved" {{ old('status', $meetingMinute->status) == 'approved' ? 'selected' : '' }}>
                                            Approved
                                        </option>
                                        <option value="rejected" {{ old('status', $meetingMinute->status) == 'rejected' ? 'selected' : '' }}>
                                            Rejected
                                        </option>
                                    </select>
                                </div>
                            </div>
                            @endif

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                                    <textarea name="content" id="content" rows="15" class="form-control" required placeholder="Enter meeting minutes content...">{{ old('content', $meetingMinute->content) }}</textarea>
                                    <small class="form-text text-muted">
                                        Content will be auto-extracted from uploaded file, but you can edit it manually.
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Additional Notes</label>
                                    <textarea name="notes" id="notes" rows="4" class="form-control" placeholder="Any additional notes or comments...">{{ old('notes', $meetingMinute->notes ?? '') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_confidential" id="is_confidential" class="form-check-input" value="1"
                                            {{ old('is_confidential', $meetingMinute->is_confidential ?? false) ? 'checked' : '' }}>
                                        <label for="is_confidential" class="form-check-label">
                                            Mark as Confidential
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i>
                                            Last updated: {{ $meetingMinute->updated_at->format('d/m/Y H:i') }}
                                            @if($meetingMinute->updated_by)
                                                by {{ $meetingMinute->updatedBy->name ?? 'System' }}
                                            @endif
                                        </small>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-secondary me-2" onclick="window.history.back()">
                                            Cancel
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Meeting Minutes
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Content Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="previewContent" style="white-space: pre-wrap; font-family: monospace;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.card {
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    border: none;
}

.form-label {
    font-weight: 600;
    color: #333;
}

.text-danger {
    color: #dc3545 !important;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.alert {
    border-radius: 8px;
}

.btn {
    border-radius: 6px;
}

.form-control, .form-select {
    border-radius: 6px;
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
}

.modal-dialog {
    max-width: 800px;
}

#previewContent {
    max-height: 400px;
    overflow-y: auto;
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 6px;
    border: 1px solid #dee2e6;
}

.file-info {
    margin-top: 5px;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // File upload preview
    $('#file').change(function() {
        const file = this.files[0];
        if (file) {
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            if (fileSize > 10) {
                alert('File size exceeds 10MB limit!');
                $(this).val('');
                return;
            }
            
            // Show file info
            const fileName = file.name;
            const fileInfo = `<small class="text-success d-block mt-1">
                <i class="fas fa-check-circle"></i> Selected: ${fileName} (${fileSize} MB)
            </small>`;
            
            // Remove existing file info
            $(this).siblings('.file-info').remove();
            $(this).after(`<div class="file-info">${fileInfo}</div>`);
        }
    });

    // Content preview functionality
    function addPreviewButton() {
        if ($('#previewBtn').length === 0) {
            const previewBtn = `<button type="button" id="previewBtn" class="btn btn-outline-info btn-sm mt-2">
                <i class="fas fa-eye"></i> Preview Content
            </button>`;
            $('#content').after(previewBtn);
        }
    }

    // Add preview button
    addPreviewButton();

    // Preview button click
    $(document).on('click', '#previewBtn', function() {
        const content = $('#content').val();
        if (content.trim() === '') {
            alert('No content to preview!');
            return;
        }
        
        $('#previewContent').text(content);
        $('#previewModal').modal('show');
    });

    // Auto-resize textarea
    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }

    $('#content, #notes').on('input', function() {
        autoResize(this);
    });

    // Form validation
    $('form').submit(function(e) {
        const content = $('#content').val().trim();
        const meetingId = $('#meeting_id').val();
        
        if (!meetingId) {
            e.preventDefault();
            alert('Please select a meeting!');
            $('#meeting_id').focus();
            return false;
        }
        
        if (!content) {
            e.preventDefault();
            alert('Content cannot be empty!');
            $('#content').focus();
            return false;
        }
        
        // Show loading state
        $(this).find('button[type="submit"]').prop('disabled', true).html(
            '<i class="fas fa-spinner fa-spin"></i> Updating...'
        );
    });

    // Character counter for content
    function updateCharCounter() {
        const content = $('#content').val();
        const charCount = content.length;
        const wordCount = content.trim() === '' ? 0 : content.trim().split(/\s+/).length;
        
        // Remove existing counter
        $('#content').siblings('.char-counter').remove();
        
        // Add new counter
        const counter = `<small class="char-counter text-muted d-block mt-1">
            Characters: ${charCount} | Words: ${wordCount}
        </small>`;
        $('#content').after(counter);
    }

    // Initial counter
    updateCharCounter();
    
    // Update counter on input
    $('#content').on('input', function() {
        updateCharCounter();
    });

    // Unsaved changes warning
    let formChanged = false;
    
    $('form input, form textarea, form select').change(function() {
        formChanged = true;
    });

    $(window).on('beforeunload', function() {
        if (formChanged) {
            return 'You have unsaved changes. Are you sure you want to leave?';
        }
    });

    $('form').submit(function() {
        formChanged = false;
    });
});
</script>
@endpush