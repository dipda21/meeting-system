{{-- resources/views/users/partials/form.blade.php --}}
<div class="mb-3">
    <label>Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}">
</div>
<div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}">
</div>

<div class="mb-3">
    <label>No WhatsApp</label>
    <input type="text" name="phone_number" class="form-control" 
           value="{{ old('phone_number', $user->phone_number ?? '') }}"
           placeholder="Contoh: 6281234567890">
</div>


<div class="mb-3">
    <label>Role</label>
    <select name="role" class="form-control">
        <option value="admin" {{ (old('role', $user->role ?? '') == 'admin') ? 'selected' : '' }}>Admin</option>
        <option value="pegawai" {{ (old('role', $user->role ?? '') == 'pegawai') ? 'selected' : '' }}>Pegawai</option>
    </select>
</div>

<div class="mb-3">
    <label>Department</label>
    <input type="text" name="department" class="form-control" value="{{ old('department', $user->department ?? '') }}">
</div>

<div class="mb-3">
    <label>Position</label>
    <input type="text" name="position" class="form-control" value="{{ old('position', $user->position ?? '') }}">
</div>

@if(!isset($user))
<div class="mb-3">
    <label>Password</label>
    <input type="password" name="password" class="form-control">
</div>
<div class="mb-3">
    <label>Confirm Password</label>
    <input type="password" name="password_confirmation" class="form-control">
</div>
@endif
<div class="mb-3">
    <button type="submit" class="btn btn-success">
        <i class="fas fa-save"></i> {{ $buttonText }}
    </button>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
</div>