@extends('layouts.app')

@section('header') @endsection
@section('footer') @endsection

@section('meta')
<meta property="og:title" content="{{ $form->title }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:image" content="{{ !empty($form->pictures[0]) ? $form->pictures[0] : url('/images/package.svg') }}" />
@endsection

@section('content')
<div class="container">
    <div class="mb-3">
        <h1>{{ $form->title }}</h1>
    </div>
    @if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif
    @if($form->counter > 0)
    <div class="alert alert-success">
        <h3 class="m-0">{{ trans_choice(__('There is only one position left for this post! Hurry up!|There are still :num positions available for this post!'), $form->counter, ['num' => $form->counter]) }}</h3>
    </div>
    @else
    <div class="alert alert-warning">
        <h3 class="m-1">{!! __('Sorry! <small>There are no positions open for this post anymore... but you can still compile the form, so that we can contact you for another opportunity!</small>') !!}</h3>
    </div>
    @endif
    <div class="row">
        <div class="col-sm-7">

            <div class="slideshow">
                @foreach($form->pictures as $image)
                <div><img class="img-slideshow" src="{{ !empty($image) ? $image : '/images/package.svg' }}"></div>
                @endforeach
            </div>
            <div class="clearfix"></div>
            <div class="markdown bigger">{!! $form->description !!}</div>
        </div>
        <div class="col-sm-5">
            <h4>{{ __('Available for the following Amazon countries:') }}</h4>
            <span class="bit-bigger">
            @foreach($form->countries as $country)
            <i class="fas fa-chevron-right fa-fw"></i> {{ config('app.amz_countries')[config('app.amz_indexes')[$country]]['flag'] }} Amazon.{{ $country }}<br>
            @endforeach
            </span>
            <hr>
            <div class="card">
                <div class="card-header bit-bigger">
                    {{ __('Leave your info here!') }}
                </div>
                <div class="card-body">
                    <div id="sample_profile" class="d-none">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary dropdown-toggle" id="profile-sample-btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ __('Select country') }}</button>
                            <div class="dropdown-menu">
                                @foreach(config('app.amz_countries') as $country)
                                <a class="dropdown-item select-country" data-target="profile-sample" data-country="{{ $country['domain'] }}" href="#profile-sample-btn">{{ $country['flag'] }} Amazon.{{ $country['domain'] }}</a>
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="profile_countries[]" value="" id="profile-sample-country">
                        <input type="text" name="profile_links[]" value="" class="form-control" aria-label="Text input with dropdown button">
                    </div>
                    @inject('hashids', 'App\Services\Hashids')
                    <form id="apply" action="{{ route('postApplication', $hashids->encode($form->id)) }}" method="post">
                    @method('post')
                    @csrf
                    <fieldset class="form-group">
                        <label>{{ __('Your name') }}</label>
                        <div class="input-group {{ ($errors->has('first_name') or $errors->has('last_name')) ? 'is-invalid' : '' }}">
                            <input type="text" value="{{ old('first_name', '') }}" placeholder="{{ __('First name') }}" class="form-control" name="first_name" id="first_name">
                            <input type="text" value="{{ old('last_name', '') }}" placeholder="{{ __('Last name') }}" class="form-control" name="last_name" id="last_name">
                        </div>
                        @if($errors->has('first_name') or $errors->has('last_name'))
                        <div class="invalid-feedback">
                            @foreach($errors->get('first_name') as $err) {{ $err }}<br> @endforeach
                            @foreach($errors->get('last_name') as $err) {{ $err }}<br> @endforeach
                        </div>
                        @endif
                    </fieldset>
                    @formTextfield('email', 'Email address', type="email", placeholder="me@example.com")
                    <fieldset class="form-group">
                        <div class="d-flex w-100 justify-content-between align-content-center">
                            <label>{{ __('Amazon profiles') }}</label>
                        </div>

                        <div id="profiles" data-count="{{ count(old('profile_links', [''])) }}">
                        @for($i = 0; $i < count(old('profile_links', [''])); $i++)
                        <div id="profile-{{ $i+1 }}-wrapper" class="input-group mb-3">
                            <div id="profile-{{ $i+1 }}" class="input-group {{ ($errors->has('profile_links.'.$i) or $errors->has('profile_countries.'.$i)) ? 'is-invalid' : '' }}">
                                <div class="input-group-prepend">
                                    @php $oldCountry = old('profile_countries', [''])[$i];
                                    if(!empty($oldCountry) && !in_array($oldCountry, array_keys(config('app.amz_indexes'))))
                                        $oldCountry = "";
                                    @endphp
                                    <button class="btn btn-outline-secondary dropdown-toggle" id="profile-{{ $i+1 }}-btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ !empty($oldCountry) ? config('app.amz_countries')[config('app.amz_indexes')[$oldCountry]]['flag'] . ' Amazon.' . $oldCountry : __('Select country') }}</button>
                                    <div class="dropdown-menu">
                                        @foreach(config('app.amz_countries') as $country)
                                        <a class="dropdown-item select-country" data-target="profile-{{ $i+1 }}" data-country="{{ $country['domain'] }}" href="#profile-{{ $i+1 }}-btn">{{ $country['flag'] }} Amazon.{{ $country['domain'] }}</a>
                                        @endforeach
                                    </div>
                                </div>
                                <input type="hidden" name="profile_countries[]" value="{{ $oldCountry }}" id="profile-{{ $i+1 }}-country">
                                <input type="text" name="profile_links[]" value="{{ old('profile_links', [''])[$i] }}" class="form-control" aria-label="Text input with dropdown button">
                                @if($i > 0)
                                <div class="input-group-append"><button class="btn btn-danger remove-profile" data-target="profile-{{ $i+1 }}" type="button"><i class="fa fa-fw fa-trash"></i></button></div>
                                @endif
                            </div>
                            @if($errors->has('profile_links.'.$i) or $errors->has('profile_countries.'.$i))
                            <div class="invalid-feedback">
                                @foreach($errors->get('profile_countries.'.$i) as $err) {{ $err }}<br> @endforeach
                                @foreach($errors->get('profile_links.'.$i) as $err) {{ $err }}<br> @endforeach
                            </div>
                            @endif
                        </div>
                        @endfor
                        </div>
                        <button class="btn btn-sm btn-primary" type="button" id="add-profile"><i class="fa fa-fw fa-plus"></i> {{ __('Add extra profile') }}</button>
                    </fieldset>

                    <button class="btn btn-primary g-recaptcha" data-sitekey="{{ config('app.recaptcha_public_key') }}" data-callback="onSubmit">{{ __('Apply') }}</button>
                    @closeForm
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
function onSubmit(token) {$("#apply").submit();}
</script>
@endsection