@extends('layouts.sidebar')

@section('title', 'Mon Profil')
@section('page-title', 'Mon Profil')

@section('content')
<div class="space-y-lg max-w-3xl">
    @if (session('status'))
        <div class="p-md bg-success-container border border-success text-success text-sm rounded-lg">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-xl">
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-xl">
        @include('profile.partials.update-password-form')
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-xl">
        @include('profile.partials.delete-user-form')
    </div>
</div>
@endsection
