@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg border-0" style="border-radius: 15px; backdrop-filter: blur(10px);">
                    <!-- Header dengan Logo Bank -->
                    <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); border-radius: 15px 15px 0 0; border: none;">
                        <div class="mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; backdrop-filter: blur(10px);">
                                <i class="fas fa-university" style="font-size: 24px; color: white;"></i>
                            </div>
                        </div>
                        <h3 class="text-white mb-1 fw-bold">Bank Sulselbar</h3>
                        <p class="text-white-50 mb-0 small">Meeting Management System</p>
                    </div>

                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold text-dark mb-1">Welcome Back</h4>
                            <p class="text-muted small">Please sign in to your account</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Field -->
                            <div class="mb-3">
                                <label for="email" class="form-label text-dark fw-semibold">
                                    <i class="fas fa-envelope me-2 text-primary"></i>Email Address
                                </label>
                                <input id="email" 
                                       type="email" 
                                       class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       autocomplete="email" 
                                       autofocus
                                       style="border-radius: 10px; border: 2px solid #e5e7eb; padding: 12px 16px;"
                                       placeholder="Enter your email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div class="mb-3">
                                <label for="password" class="form-label text-dark fw-semibold">
                                    <i class="fas fa-lock me-2 text-primary"></i>Password
                                </label>
                                <div class="position-relative">
                                    <input id="password" 
                                           type="password" 
                                           class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                           name="password" 
                                           required 
                                           autocomplete="current-password"
                                           style="border-radius: 10px; border: 2px solid #e5e7eb; padding: 12px 16px; padding-right: 45px;"
                                           placeholder="Enter your password">
                                    <button type="button" 
                                            class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0" 
                                            style="border: none; background: none; color: #6b7280;"
                                            onclick="togglePassword()">
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </button>
                                </div>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="remember" 
                                           id="remember" 
                                           {{ old('remember') ? 'checked' : '' }}
                                           style="border-radius: 4px;">
                                    <label class="form-check-label text-dark small" for="remember">
                                        Remember Me
                                    </label>
                                </div>

                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none small text-primary fw-semibold" 
                                       href="{{ route('password.request') }}">
                                        Forgot Password?
                                    </a>
                                @endif
                            </div>

                            <!-- Login Button -->
                            <div class="d-grid mb-3">
                                <button type="submit" 
                                        class="btn btn-lg fw-semibold"
                                        style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); 
                                               border: none; 
                                               border-radius: 10px; 
                                               color: white; 
                                               padding: 12px 0;
                                               transition: all 0.3s ease;"
                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(79, 70, 229, 0.3)'"
                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                </button>
                            </div>
                        </form>

                        <!-- Footer Info -->
                        <div class="text-center mt-4 pt-3" style="border-top: 1px solid #e5e7eb;">
                            <p class="text-muted small mb-0">
                                <i class="fas fa-shield-alt me-1"></i>
                                Secure banking portal for authorized personnel only
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="text-center mt-3">
                    <p class="text-white-50 small">
                        <i class="fas fa-info-circle me-1"></i>
                        For technical support, contact IT Department
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styling untuk form focus states */
.form-control:focus {
    border-color: #4f46e5 !important;
    box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25) !important;
}

.form-check-input:checked {
    background-color: #4f46e5;
    border-color: #4f46e5;
}

/* Hover effects */
.card {
    transition: all 0.3s ease;
}

/* Background pattern */
body {
    background-image: 
        radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 0%, transparent 50%);
}

/* Animation untuk loading state */
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.btn:disabled {
    animation: pulse 1.5s infinite;
}
</style>

<script>
function togglePassword() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Auto-focus pada email field saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('email').focus();
});

// Form validation enhancement
document.querySelector('form').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Signing In...';
});
</script>

@endsection