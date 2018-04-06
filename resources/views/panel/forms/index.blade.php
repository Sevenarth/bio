@extends('layouts.app')

@section('content')
<div class="container">
        <h1 class="mb-3">{{ __('Posts') }}</h1>
        <div class="my-2 d-flex w-100 justify-content-between">
            <form id="filter" class="form-inline" method="get">
                <fieldset class="form-group m-0">
                    <div class="input-group mr-2">
                        <input type="text" value="{{ app('request')->query('s') }}" class="form-control" name="s" id="search" placeholder="{{ __('Title, ID') }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-outline-primary">
                                    <i class="fa fa-fw fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <label for="category">{{ __('Category') }}:</label>
                    <select id="choose-category" name="category" class="mx-2 form-control">
                        <option value="">{{ __('(no category)') }}</option>
                        @inject('categories', 'App\Services\Category')
                        @foreach($categories->tree() as $cat)
                        <option value="{{ $cat->id }}"@if(app('request')->query('category') == $cat->id) selected="selected" @endif>{!! $cat->title !!}</option>
                        @endforeach
                    </select>

                    <label for="country">{{ __('Amazon country') }}:</label>
                    <select id="choose-country" name="country" class="ml-2 form-control">
                        <option value="">{{ __('(no country)') }}</option>
                        @foreach(config('app.amz_countries') as $country)
                        <option value="{{ $country['domain'] }}"@if(app('request')->query('country') == $country['domain']) selected="selected" @endif>{{ $country['flag'] . " Amazon." . $country['domain'] }}</option>
                        @endforeach
                    </select>
                </fieldset>
            </form>
            <a href="{{ route('panel.forms.new') }}" class="btn btn-primary">
                <i class="fa fa-fw fa-plus"></i> {{ __('New') }}
            </a>
        </div>

        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif

        <table class="table table-striped">
            <thead>
                <th>@orderable('id', '#')</th>
                <th></th>
                <th>@orderable('title', 'Title')</th>
                <th>@orderable('counter', 'Counter')</th>
                <th>{{ __('Amazon countries') }}</th>
                <th>@orderable('category', 'Categoria')</th>
                <th>@orderable('created-at', 'Created at')</th>
                <th></th>
            </thead>
            @forelse($forms as $form)
            @php $form->fixCounter() @endphp
            <tr class="@if($form->counter == 0) table-success @endif">
                <td class="align-middle">{{ $form->id }}</td>
                <td class="align-middle"><img style="height: 50px" src="{{ !empty($form->pictures[0]) ? $form->pictures[0] : '/images/package.svg' }}" height="50" class="img-thumbnail img-responsive"></td>
                <td class="align-middle">{{ $form->title }}</td>
                <td class="align-middle">{{ $form->counter }}</td>
                <td class="align-middle">
                    @foreach($form->countries as $country)
                    <span class="badge badge-secondary text-uppercase">{{ $country }}</span>
                    @endforeach
                </td>
                @if($form->category)
                <td class="align-middle">{{ $form->category }}</td>
                @else
                <td class="align-middle text-muted font-italic">{{ __('No category') }}</td>
                @endif
                <td class="align-middle">{{ $form->created_at->format('d/m/Y') }}</td>
                <td class="align-middle">
                    <a href="{{ route('panel.forms.view', $form->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-fw fa-external-link-alt"></i> {{ __('View') }}
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-muted text-center font-italic">{{ __('There are no posts in the system.') }}</td>
            </tr>
            @endforelse
        </table>
        <div class="float-right mb-2">
                {{ __('Showing page :page of :total', ['page' => $forms->currentPage(), 'total' => $forms->lastPage()]) }}
        </div>
        {{ $forms->appends(request()->query())->links() }}
        <div class="clearfix"></div>
    </div>
@endsection