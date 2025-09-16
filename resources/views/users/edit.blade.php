
{{-- resources/views/users/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2><i class="fas fa-user-edit"></i> Edit User</h2>
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('users.partials.form', ['buttonText' => 'Update'])
    </form>
</div>
@endsection