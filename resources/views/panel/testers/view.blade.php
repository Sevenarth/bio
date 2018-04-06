@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">{{ __('Profile #') . $profile->id }} <small><span class="badge {{ $profile->verified ? 'badge-success' : 'badge-danger' }}">{{ __($profile->verified ? 'Verified': 'Not verified') }}</span></small></h1>
    <div class="row bit-bigger mb-3">
        <div class="col-sm-6">
            <label>{{ __('Full name') }}</label>
            <div class="form-control bigger">{{ $profile->first_name . " " . strtoupper($profile->last_name) }}</div>
        </div>
        <div class="col-sm-6">
            <label>{{ __('Email address') }}</label>
            <div class="form-control bigger">{{ $profile->tester->email }}</div>
        </div>
    </div>
    <div class="bit-bigger">
        <label>{{ __('Post') }}</label>
        <div class="row my-3">
            <div class="col-2 text-center">
                <img style="max-height: 100px" src="{{ (!empty($profile->form) && !empty($profile->form->pictures[0])) ? $profile->form->pictures[0] : url('/images/package.svg') }}" alt="image" class="img-thumbnail img-responsive">
            </div>
            <div class="col-10 bigger">
                @if(!empty($profile->form))
                <a href="{{ route('panel.forms.view', $profile->form->id) }}">{{ $profile->form->title }}</a>
                @else
                <span class="text-muted font-italic">{{ __('Missing post') }}</span>
                @endif <br>
                <small><b>{{ __('Registration date') }}</b>: {{ $profile->created_at->format('d/m/Y H:i:s') }}</small>
            </div>
        </div>
        <label>{{ __('Amazon profiles') }}</label>
        @foreach($profile->amazon_profiles as $country => $link)
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <div class="input-group-text">{{ config('app.amz_countries')[config('app.amz_indexes')[$country]]['flag'] }} Amazon.{{ $country }}</div>
            </div>
            <input type="text" value="{{ $link }}" readonly class="form-control bg-white">
            <div class="input-group-append">
                <a href="{{ $link }}" class="btn btn-secondary input-group-btn" target="_blank"><i class="fa fa-fw fa-external-link-alt"></i> {{ __('View') }}</a>
            </div>
        </div>
        @endforeach

        @php $other_profiles = \App\FormTester::where('tester_id', $profile->tester_id)->where('id', '<>', $profile->id)->paginate(10); @endphp
        <label>{{ __('Other profiles with the same email address') }} <span class="badge badge-secondary">{{ $other_profiles->total() }}</span></label>
        <table class="table table-striped">
            <thead>
                <th>{{ __('Full name') }}</th>
                <th></th>
                <th>{{ __('Post') }}</th>
                <th>{{ __('Amazon countries') }}</th>
                <th></th>
            </thead>
            @forelse($other_profiles as $tester)
            <tr>
                <td class="align-middle">{{ $tester->first_name }} <span class="text-uppercase">{{ $tester->last_name }}</span></td>
                <td class="align-middle"><img style="height: 50px" src="{{ (!empty($tester->form) && !empty($tester->form->pictures[0])) ? $tester->form->pictures[0] : '/images/package.svg' }}" height="50" class="img-thumbnail img-responsive"></td>
                <td class="align-middle">@if(!empty($tester->form)) <a href="{{ route('panel.forms.view', $tester->form->id) }}">{{ $tester->form->title }}</a> @else <span class="text-muted font-italic">{{ __('Missing post') }}</span> @endif</td>
                <td class="align-middle">
                    @foreach(array_keys($tester->amazon_profiles) as $country)
                    <span class="badge-pill badge-secondary text-uppercase badge">{{ $country }}</span>
                    @endforeach
                </td>
                <td class="align-middle">
                    <a href="{{ route('panel.testers.view', $tester->tester->id) }}" class="btn btn-outline-primary">
                        <i class="fa fa-fw fa-external-link-alt"></i> {{ __('View') }}
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-muted text-center font-italic">{{ __('There are no profiles in the system.') }}</td>
            </tr>
            @endforelse
        </table>
        <div class="float-right mb-2">
            {{ __('Showing page :page of :total', ['page' => $other_profiles->currentPage(), 'total' => $other_profiles->lastPage()]) }}
        </div>
        {{ $other_profiles->appends(request()->query())->links() }}
        <div class="clearfix"></div>
        
    </div>
</div>
@endsection