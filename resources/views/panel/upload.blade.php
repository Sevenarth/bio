@extends('layouts.popup')

@section('content')
    <div class="px-4 py-3 h5 border-bottom border-top">
      {{ __('Upload picture') }}
    </div>
    <div class="px-2 py-3">
      <form id="form" action="{{ route('panel.postUpload') }}" method="post" enctype="multipart/form-data">
        @method('post')
        @csrf
        @if(Request::query('field', false))
        <input type="hidden" name="field" value="{{ Request::query('field') }}">
        @endif
        <div class="input-group mb-3">
          <div class="custom-file">
            <label class="custom-file-label" for="file_input">{{ __('Choose picture') }}</label>
            <input accept="image/*" type="file" class="custom-file-input{{ $errors->has('image') ? ' is-invalid': ''}}" id="file_input" name="image">
            @if($errors->has('image'))
            <div class="invalid-feedback">
              @foreach($errors->get('image') as $err)
                {{ $err }}<br>
              @endforeach
            </div>
            @endif
          </div>
        </div>
        <div class="h5 mb-3">
          {{ __('Anteprima') }}
        </div>
        <div id="preview" class="text-center mb-4">
          <i>{{ __('No picture has been chosen.') }}</i>
        </div>
        <button id="submitBtn" type="submit" class="btn btn-primary" disabled>{{ __('Upload and choose picture') }}</button>
      </form>
    </div>
@endsection
