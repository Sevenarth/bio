@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-3">Home</h1>
        <div class="row">
            <div class="col-sm-4 mb-3 mb-sm-0">
                <h2>{{ __('Go to:') }}</h2>
                <div class="list-group">
                    <a href="{{ route('panel.testers.index') }}" class="list-group-item list-group-item-action">
                        <h3 class="my-0">{{ __('Profiles') }}</h3>
                    </a>
                    <a href="{{ route('panel.forms.index') }}" class="list-group-item list-group-item-action">
                        <h3 class="my-0">{{ __('Posts') }}</h3>
                    </a>
                    <a href="{{ route('panel.categories.index') }}" class="list-group-item list-group-item-action">
                        <h3 class="my-0">{{ __('Categories') }}</h3>
                    </a>
                </div>
            </div>
            <div class="col-sm-4 mb-3 mb-sm-0">
                <div class="list-group">
                    <div class="list-group-item">{{ __('There are :X testers registered.', ['X' => $stats['testers']]) }}</div>
                    <div class="list-group-item">{{ __('A total of :X profiles registered.', ['X' => $stats['profiles']]) }}</div>
                    <div class="list-group-item">
                        {{ __('There are :X posts. Of which:', ['X' => $stats['posts']]) }}
                        <ul class="my-2">
                            <li>{{ __(':X are currently active', ['X' => $stats['active_posts']]) }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 mb-3 mb-sm-0">
                <div class="card">
                    <div class="card-header">
                        {{ __('Last profiles registered') }}
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse($last_profiles as $profile)
                        <a href="{{ route('panel.testers.view', $profile->id) }}" class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{ $profile->first_name }} {{ $profile->last_name }}</h5>
                                <small class="relative-time">{{ $profile->created_at->toIso8601String() }}</small>
                            </div>
                            <p class="mb-1">
                                {{ $profile->tester->email }}
                                @foreach(array_keys($profile->amazon_profiles) as $country)
                                <span class="badge badge-primary badge-pill text-uppercase">{{ $country }}</span>
                                @endforeach
                            </p>
                            <small>{{ $profile->form->title }}</small>
                        </a>
                        @empty
                        <div class="list-group-item text-muted font-italic text-center">{{ __('There are no profiles in the system.') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection