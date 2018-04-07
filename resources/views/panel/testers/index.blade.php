@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">{{ __('Profiles') }}</h1>
    <div class="my-2 d-flex justify-content-between">
        <form action="" id="filter" class="form-inline" method="get">
            <fieldset class="form-group mr-2" style="width: 400px">
                <div class="input-group" style="width: 400px">
                    <input type="text" class="form-control" name="s" value="{{ request()->query('s') }}" id="search" placeholder="{{ __('First and/or last name, email address') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fa fa-fw fa-search"></i>
                        </button>
                    </div>
                </div>
            </fieldset>

            <label for="country">{{ __('Amazon country') }}:</label>
            <select id="choose-country" name="country" class="ml-2 form-control">
                <option value="">{{ __('(no country)') }}</option>
                @foreach(config('app.amz_countries') as $country)
                <option value="{{ $country['domain'] }}"@if(app('request')->query('country') == $country['domain']) selected="selected" @endif>{{ $country['flag'] . " Amazon." . $country['domain'] }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('panel.testers.download') }}{{ Request::getQueryString() ? '?'.Request::getQueryString() : '' }}" class="btn btn-primary"><i class="fa fa-fw fa-download"></i> Download</a>
    </div>

    <table class="table table-striped">
        <thead>
            <th>@orderable('full-name', 'Full name')</th>
            <th>@orderable('email', 'Email address')</th>
            <th>@orderable('verified', 'Verified')</th>
            <th>{{ __('Amazon countries') }}</th>
            <th>@orderable('created-at', 'Registration date')</th>
            <th></th>
        </thead>
        @forelse($testers as $tester)
        <tr>
            <td>{{ $tester->first_name }} <span class="text-uppercase">{{ $tester->last_name }}</span></td>
            <td>{{ $tester->tester->email }}</td>
            <td class="bit-bigger text-primary"><i class="fa-fw {{$tester->verified ? 'fa fa-check':'far fa'}}-square"></i></td>
            <td>
                @foreach(array_keys($tester->amazon_profiles) as $country)
                <span class="badge-pill badge-secondary text-uppercase badge">{{ $country }}</span>
                @endforeach
            </td>
            <td class="datetime">{{ $tester->created_at->format('d/m/Y H:i') }}</td>
            <td>
                <a href="{{ route('panel.testers.view', $tester->id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-fw fa-external-link-alt"></i> {{ __('View') }}
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-muted text-center font-italic">{{ __('There are no profiles in the system.') }}</td>
        </tr>
        @endforelse
    </table>
    <div class="float-right mb-2">
        {{ __('Showing page :page of :total', ['page' => $testers->currentPage(), 'total' => $testers->lastPage()]) }}
    </div>
    {{ $testers->appends(request()->query())->links() }}
    <div class="clearfix"></div>
</div>
@endsection