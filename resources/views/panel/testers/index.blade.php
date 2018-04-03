@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">{{ __('Profiles') }}</h1>
    <div class="my-2">
        <form action="" method="get">
            <fieldset class="form-group">
                <div class="input-group w-50">
                    <input type="text" class="form-control" name="s" id="search" placeholder="{{ __('First and/or last name, email address') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fa fa-fw fa-search"></i>
                        </button>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>

    <table class="table table-striped">
        <thead>
            <th>@orderable('full-name', 'Full name')</th>
            <th>@orderable('email', 'Email address')</th>
            <th>{{ __('Amazon countries') }}</th>
            <th>@orderable('created-at', 'Date registration')</th>
            <th></th>
        </thead>
        @forelse($testers as $tester)
        <tr>
            <td>{{ $tester->first_name }} <span class="text-uppercase">{{ $tester->last_name }}</span></td>
            <td>{{ $tester->email }}</td>
            <td>
                @foreach(array_keys($tester->amazon_profiles) as $country)
                <span class="badge-pill badge-secondary text-uppercase badge">{{ $country }}</span>
                @endforeach
            </td>
            <td class="datetime">{{ $tester->created_at }}</td>
            <td>
                <a href="{{ route('panel.testers.view', $tester->tester->id) }}" class="btn btn-outline-primary">
                    <i class="fa fa-fw fa-external-alt-link"></i> {{ __('View') }}
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-muted text-center font-italic">{{ __('There are no profiles in the system.') }}</td>
        </tr>
        @endforelse
    </table>
</div>
@endsection