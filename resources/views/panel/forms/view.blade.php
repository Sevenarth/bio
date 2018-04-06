@extends('layouts.app')

@section('content')
<div class="container">
        <div class="my-2 d-flex w-100 justify-content-between">
        <h1 class="mb-3" style="font-size: 2rem">#{{ $form->id }} {{ $form->title }} <small>@if($form->counter > 0)<span class="badge badge-secondary">{{ __(':num left', ['num' => $form->counter]) }}</span>@else <span class="badge badge-success">{{ __('Complete') }}</span>@endif</small></h1>
            @openForm('panel.forms.delete', 'delete', arg="form->id")
            <div class="btn-group float-right" role="group">@inject('hashids', 'App\Services\Hashids')
                <a href="{{ route('viewPost', $hashids->encode($form->id)) }}" target="_blank" class="btn btn-primary"><i class="fa fa-fw fa-external-link-alt"></i> {{ __('View') }}</a>
                <a href="{{route("panel.forms.edit", $form->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
              <button type="submit" data-placement="bottom" class="remove-confirmation btn btn-danger" data-html="true" data-toggle="popover" data-trigger="focus" title="{{ __('Confirm request') }}" data-content="{{ __('Are you sure you want to delete this post?') }}"><i class="fa fa-fw fa-times"></i> {{ __('Delete') }}</button>
            </div>
            @closeForm
        </div>
        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif

        @openForm('panel.forms.delete', 'delete', arg="form->id")
        <div class="btn-group text-center d-block d-md-none mb-4" role="group">
        <a href="{{route("panel.forms.edit", $form->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a><button type="submit" class="remove-confirmation btn btn-danger" data-html="true" data-placement="bottom" data-toggle="popover" data-trigger="focus" title="{{ __('Confirm request') }}" data-content="{{ __('Are you sure you want to delete this post?') }}"><i class="fa fa-fw fa-times"></i> {{ __('Delete') }}</button>
        </div>
        @closeForm

        <div class="row">
            <div class="col-sm-8">
                <div class="form-control markdown mb-3" style="max-height: 400px; overflow-y: auto">{!! $form->description !!}</div>
                <hr>
                @php $profiles = $form->testers()->withPivot('first_name', 'last_name', 'created_at', 'id')->paginate(10); @endphp
                <h4>{{ __('Profiles') }} <small class="badge badge-secondary">{{ $profiles->total() }}</small></h4>
                <table class="mt-2 table table-striped table-condensed">
                    <thead>
                        <th>{{ __('Full name') }}</th>
                        <th>{{ __('Email address') }}</th>
                        <th>{{ __('Registration date') }}</th>
                        <th></th>
                    </thead>
                    @forelse($profiles as $tester)
                    <tr>
                        <td>{{ $tester->profile->first_name }} <span class="text-uppercase">{{ $tester->profile->last_name }}</span></td>
                        <td>{{ $tester->email }}</td>
                        <td class="datetime">{{ $tester->profile->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('panel.testers.view', $tester->profile->id) }}" class="btn btn-sm btn-outline-primary">
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
                    Mostrando pagina {{ $profiles->currentPage() }} di {{ $profiles->lastPage() }}
                </div>
                {{ $profiles->appends(request()->query())->links() }}
                <div class="clearfix"></div>
            </div>
            <div class="col-sm-4">
                <h4>{{ __('Last subtraction') }}</h4>
                <span class="bit-bigger">{{ !empty($form->last_count) ? $form->last_count->format('d/m/Y H:i') : 'N/A'}}</span>
                <hr>
                <h4>{{ __('Next subtraction') }}</h4>
                @inject('timeStopper', 'App\Services\TimeStopper') 
                <span class="bit-bigger">{{ $form->counter > 0 ? $timeStopper->retrieveFromNow($form->starts_on, $form->counts_on_time, $form->counts_on_space)->format('d/m/Y H:i') : 'N/A' }}</span>
                <hr>
                <h4>{{ __('Amazon countries') }}</h4>
                @foreach($form->countries as $country)
                <i class="fas fa-chevron-right fa-fw"></i> {{ config('app.amz_countries')[config('app.amz_indexes')[$country]]['flag'] }} Amazon.{{ $country }}<br>
                @endforeach
                <hr>
                <h4>{{ __('Category') }}</h4>
                <div class="form-control">
                    @if($form->category)
                    <a href="{{ route('panel.forms.index') }}?category={{ $form->category->id }}">{{ $form->category->title }}</a>
                    @else
                    <div class="text-muted font-italic">{{ __('No category') }}</div>
                    @endif
                </div>
                <hr>
                <h4>{{ __('Pictures') }}</h4>
                @php $images = $form->pictures; @endphp
                <div id="image-slideshow" class="mb-5 bg-secondary form-control carousel slide" data-ride="carousel">
                  <ol class="carousel-indicators">
                    @forelse(array_keys($images) as $id)
                    <li data-target="#image-slideshow" data-slide-to="{{ $id }}"{{ $id === 0 ? ' class="active"' : '' }}></li>
                  @empty
                    <li data-target="#image-slideshow" data-slide-to="0" class="active"></li>
                  @endforelse
                  </ol>
                  <div class="carousel-inner">
                    @forelse($images as $id => $image)
                    <div class="text-center carousel-item{{ $id === 0 ? ' active' : '' }}">
                      <img style="max-height: 250px" class="mw-100" src="{{ !empty($image) ? $image : '/images/package.svg' }}" alt="image-{{ $id+1 }}">
                    </div>
                    @empty
                      <div class="text-center carousel-item active">
                        <img style="max-height: 250px" class="mw-100" src="/images/package.svg" alt="default image">
                      </div>
                  @endforelse
                  </div>
                  <a class="carousel-control-prev" href="#image-slideshow" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  </a>
                  <a class="carousel-control-next" href="#image-slideshow" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  </a>
                </div>
            </div>
        </div>
    </div>
@endsection