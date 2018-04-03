@extends('layouts.app')

@section('content')
<div class="container">
        <h1 class="mb-3">{{ __('Posts') }}</h1>
        <div class="my-2 d-flex w-100 justify-content-between">
            <form action="" method="get">
                <fieldset class="form-group m-0">
                    <div class="input-group">
                        <input type="text" class="form-control" name="s" id="search" placeholder="{{ __('Title, ID') }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-outline-primary">
                                    <i class="fa fa-fw fa-search"></i>
                            </button>
                        </div>
                    </div>
                </fieldset>
            </form>
            <a href="{{ route('panel.forms.new') }}" class="btn btn-primary">
                <i class="fa fa-fw fa-plus"></i> {{ __('New') }}
            </a>
        </div>
        <table class="table table-striped">
            <thead>
                <th>@orderable('id', '#')</th>
                <th>@orderable('title', 'Title')</th>
                <th>@orderable('opening-in', 'Opening in')</th>
                <th>@orderable('expires-in', 'Expires in')</th>
                <th></th>
            </thead>
            @forelse($forms as $form)
            <tr>
                <td>{{ $form->id }}</td>
                <td>{{ $form->title }}</td>
                <td class="relative-time">{{ $form->starts_on }}</td>
                <td class="relative-time">{{ $form->expires_on }}</td>
                <td>
                    <a href="" class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-fw fa-external-alt-link"></i> {{ __('View') }}
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-muted text-center font-italic">{{ __('There are no posts in the system.') }}</td>
            </tr>
            @endforelse
        </table>
    </div>
@endsection