{{-- resources/views/minutes/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex align-items-center mb-4">
        <div class="bg-success rounded-circle p-3 me-3" style="width: 60px; height: 60px;">
            <i class="fas fa-plus text-white fs-4"></i>
        </div>
        <div>
            <h2 class="mb-1 fw-bold text-dark">Buat Notulen Baru</h2>
            <p class="text-muted mb-0">Upload dan kelola notulen rapat Bank Sulselbar</p>
        </div>
        <div class="ms-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('meeting-minutes.index') }}" class="text-decoration-none">
                            <i class="fas fa-file-alt me-1"></i>Notulen
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Buat Baru</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Form Section -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-edit text-primary me-2"></i>
                        Informasi Notulen
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('meeting-minutes.store') }}" method="POST" enctype="multipart/form-data" id="minutesForm">
                        @csrf
                        
                        <!-- Meeting Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar-alt text-primary me-2"></i>
                                Pilih Rapat
                            </label>
                            <select name="meeting_id" class="form-select form-select-lg border-0 shadow-sm @error('meeting_id') is-invalid @enderror" 
                                    style="border-radius: 15px;">
                                <option value="">-- Pilih Rapat --</option>
                                @foreach($meetings as $meeting)
                                    <option value="{{ $meeting->id }}" {{ old('meeting_id') == $meeting->id ? 'selected' : '' }}>
                                        {{ $meeting->title }} - {{ \Carbon\Carbon::parse($meeting->date)->format('d M Y') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('meeting_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Pilih rapat yang akan dibuatkan notulennya
                            </small>
                        </div>

                      

                        <!-- Content/Summary -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-align-left text-info me-2"></i>
                                Konten Notulen
                            </label>
                            <textarea name="content" class="form-control border-0 shadow-sm @error('content') is-invalid @enderror" 
                                      rows="6" placeholder="Masukkan konten atau ringkasan notulen rapat..." 
                                      style="border-radius: 15px; resize: vertical;">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="d-flex justify-content-between mt-2">
                                <small class="form-text text-muted">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Jelaskan poin-poin penting dari rapat
                                </small>
                                <small class="text-muted char-counter">0/1000</small>
                            </div>
                        </div>

                        <!-- Action Items -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-tasks text-warning me-2"></i>
                                Tindak Lanjut
                            </label>
                            <textarea name="action_items" class="form-control border-0 shadow-sm @error('action_items') is-invalid @enderror" 
                                      rows="4" placeholder="Masukkan tindak lanjut atau action items..." 
                                      style="border-radius: 15px; resize: vertical;">{{ old('action_items') }}</textarea>
                            @error('action_items')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Daftar tugas atau tindak lanjut yang perlu dilakukan
                            </small>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('meeting-minutes.index') }}" class="btn btn-light px-4 py-2" style="border-radius: 25px;">
                                <i class="fas fa-arrow-left me-2"></i>
                                Kembali
                            </a>
                            <div>
                                <button type="button" class="btn btn-outline-primary px-4 py-2 me-2" style="border-radius: 25px;" 
                                        onclick="saveDraft()">
                                    <i class="fas fa-save me-2"></i>
                                    Simpan Draft
                                </button>
                                <button type="submit" class="btn btn-success px-4 py-2" style="border-radius: 25px;">
                                    <i class="fas fa-upload me-2"></i>
                                    Upload Notulen
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <!-- Upload Guidelines -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i>
                        Panduan Upload
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3" style="width: 40px; height: 40px;">
                            <i class="fas fa-file-pdf text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Format File</h6>
                            <small class="text-muted">PDF, DOC, DOCX</small>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3" style="width: 40px; height: 40px;">
                            <i class="fas fa-weight-hanging text-success"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Ukuran File</h6>
                            <small class="text-muted">Maksimal 10MB</small>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3" style="width: 40px; height: 40px;">
                            <i class="fas fa-shield-alt text-info"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Keamanan</h6>
                            <small class="text-muted">File dienkripsi otomatis</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-clock me-2"></i>
                        Aktivitas Terbaru
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item d-flex mb-3">
                            <div class="bg-success rounded-circle p-1 me-3" style="width: 8px; height: 8px; margin-top: 8px;"></div>
                            <div>
                                <small class="text-muted">2 jam lalu</small>
                                <p class="mb-0 small">Notulen rapat koordinasi telah diupload</p>
                            </div>
                        </div>
                        <div class="timeline-item d-flex mb-3">
                            <div class="bg-primary rounded-circle p-1 me-3" style="width: 8px; height: 8px; margin-top: 8px;"></div>
                            <div>
                                <small class="text-muted">1 hari lalu</small>
                                <p class="mb-0 small">Rapat evaluasi bulanan selesai</p>
                            </div>
                        </div>
                        <div class="timeline-item d-flex">
                            <div class="bg-warning rounded-circle p-1 me-3" style="width: 8px; height: 8px; margin-top: 8px;"></div>
                            <div>
                                <small class="text-muted">3 hari lalu</small>
                                <p class="mb-0 small">Draft notulen perlu review</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.upload-area {
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover {
    border-color: #007bff !important;
    background-color: #f8f9ff;
}

.upload-area.dragover {
    border-color: #007bff !important;
    background-color: #e3f2fd;
    transform: scale(1.02);
}

.timeline {
    position: relative;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 4px;
    top: 0;
    bottom: 0;
    width: 1px;
    background: #dee2e6;
}

.char-counter {
    font-size: 0.75rem;
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // File upload functionality
    const fileInput = document.getElementById('fileInput');
    const uploadArea = document.querySelector('.upload-area');
    const uploadContent = document.querySelector('.upload-content');
    const fileInfo = document.querySelector('.file-info');
    const fileName = document.querySelector('.file-name');
    const fileSize = document.querySelector('.file-size');

    // Character counter
    const contentTextarea = document.querySelector('textarea[name="content"]');
    const charCounter = document.querySelector('.char-counter');

    if (contentTextarea && charCounter) {
        contentTextarea.addEventListener('input', function() {
            const length = this.value.length;
            charCounter.textContent = `${length}/1000`;
            
            if (length > 1000) {
                charCounter.classList.add('text-danger');
            } else {
                charCounter.classList.remove('text-danger');
            }
        });
    }

    // File input change
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            displayFileInfo(file);
        }
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            displayFileInfo(files[0]);
        }
    });

    function displayFileInfo(file) {
        const fileExtension = file.name.split('.').pop().toLowerCase();
        const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
        
        fileName.textContent = file.name;
        fileSize.textContent = `${fileSizeMB} MB`;
        
        uploadContent.classList.add('d-none');
        fileInfo.classList.remove('d-none');
        
        // Change icon based on file type
        const fileIcon = fileInfo.querySelector('i');
        if (fileExtension === 'pdf') {
            fileIcon.className = 'fas fa-file-pdf fa-2x text-danger mb-2';
        } else if (fileExtension === 'doc' || fileExtension === 'docx') {
            fileIcon.className = 'fas fa-file-word fa-2x text-primary mb-2';
        }
    }

    // Form validation
    document.getElementById('minutesForm').addEventListener('submit', function(e) {
        let isValid = true;
        const requiredFields = ['meeting_id', 'content'];
        
        requiredFields.forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang diperlukan!');
        }
    });
});

function saveDraft() {
    // Add draft functionality here
    const form = document.getElementById('minutesForm');
    const draftInput = document.createElement('input');
    draftInput.type = 'hidden';
    draftInput.name = 'save_as_draft';
    draftInput.value = '1';
    form.appendChild(draftInput);
    form.submit();
}
</script>
@endsection