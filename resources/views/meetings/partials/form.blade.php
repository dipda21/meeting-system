{{-- resources/views/meetings/partials/form.blade.php --}}
<div class="mb-3">
    <label for="title">Title <span class="text-danger">*</span></label>
    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
           value="{{ old('title', $meeting->title ?? '') }}" required>
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="description">Description</label>
    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $meeting->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="meeting_date">Date <span class="text-danger">*</span></label>
        <input type="date" name="meeting_date" id="meeting_date" 
               class="form-control @error('meeting_date') is-invalid @enderror" 
               value="{{ old('meeting_date', isset($meeting->meeting_date) ? $meeting->meeting_date->format('Y-m-d') : '') }}" 
               required>
        @error('meeting_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="start_time">Start Time <span class="text-danger">*</span></label>
        <input type="time" name="start_time" id="start_time" 
               class="form-control @error('start_time') is-invalid @enderror" 
               value="{{ old('start_time', isset($meeting->start_time) ? $meeting->start_time->format('H:i') : '') }}" 
               required>
        @error('start_time')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="end_time">End Time <span class="text-danger">*</span></label>
        <input type="time" name="end_time" id="end_time" 
               class="form-control @error('end_time') is-invalid @enderror" 
               value="{{ old('end_time', isset($meeting->end_time) ? $meeting->end_time->format('H:i') : '') }}" 
               required>
        @error('end_time')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label for="location">Location</label>
    <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" 
           value="{{ old('location', $meeting->location ?? '') }}">
    @error('location')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="agenda">Agenda</label>
    <textarea name="agenda" id="agenda" class="form-control @error('agenda') is-invalid @enderror">{{ old('agenda', $meeting->agenda ?? '') }}</textarea>
    @error('agenda')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="participants">Participants</label>
    <select name="participants[]" id="participants" multiple class="form-control @error('participants') is-invalid @enderror">
        @foreach($users as $user)
            <option value="{{ $user->id }}" {{ in_array($user->id, old('participants', $selectedParticipants ?? [])) ? 'selected' : '' }}>
                {{ $user->name }} ({{ $user->email }})
            </option>
        @endforeach
    </select>
    @error('participants')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple participants</small>
</div>

<div class="mb-3">
    <button class="btn btn-success" type="submit">
        <i class="fas fa-save"></i> {{ $buttonText }}
    </button>
    <a href="{{ route('meetings.index') }}" class="btn btn-secondary">Cancel</a>
</div>