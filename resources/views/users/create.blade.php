{{-- resources/views/users/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2><i class="fas fa-user-plus"></i> Add New User</h2>
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        @include('users.partials.form', ['buttonText' => 'Create'])
    </form>
</div>
@endsection