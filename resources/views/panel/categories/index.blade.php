@extends('layouts.app')

@section('content')

<div class="container">
    <h1 class="mb-3">{{ __('Categories') }}</h1>
    <div class="my-2 d-flex w-100 justify-content-between">
        <a href="{{ route('panel.categories.create') }}" class="mb-4 btn btn-primary">
            <i class="fa fa-fw fa-plus"></i>
            {{ __('New category') }}
        </a>
    </div>

    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div>
      <ul class="list-group list-group-flush">
        @forelse($cats as $cat)
        <li class="list-group-item px-3 py-2" style="font-size: 18px">
        @openForm('panel.categories.delete', 'delete', arg="cat->id")
          <div class="float-right btn-group">
          <a href="{{ route('panel.categories.edit', $cat->id) }}" class="btn-outline-primary btn-sm btn"><i class="fas fa-fw fa-edit"></i> {{ __('Edit') }}</a>
          <button type="submit" data-placement="bottom" class="remove-confirmation btn btn-sm btn-danger" data-html="true" data-toggle="popover" data-trigger="focus" title="{{ __('Confirm request') }}" data-content="{{ __('Are you sure you want to delete this category?') }}"><i class="fa fa-fw fa-times"></i> {{ __('Delete') }}</button>
          </div>
          @closeForm
          {!! $cat->title !!}
        </li>
        @empty
        <li class="list-group-item text-center"><i>{{ __('There are no categories in the system.') }}</i></li>
        @endif
      </ul>
    </div>
</div>
@endsection
