@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
        <h3 class="m-0">{{ __($cat->id ? 'Edit category #' : 'New category') }}{{ $cat->id ?? '' }}</h3>
    </div>
    <div class="card-body">
    @if(!empty($cat->id))
    @openForm('panel.categories.update', 'patch', arg="cat->id")
    @else
    @openForm('panel.categories.put', 'put')
    @endif

    @formTextfield('title', 'Category name', placeholder="Tecnology", editMode="cat")

    <fieldset class="form-group">
    <label for="categories">{{ __('Category parent')}}</label>
      <select class="custom-select" name="parent_id">
      <option value=""{{ !empty(old('parent_id', $cat->parent_id)) ? '' : ' selected' }}>{{ __('(no category)') }}</option>
        @foreach($cats as $cat_)
        <option value="{{ $cat_->id }}"{{ old('categories', $cat->parent_id) == $cat_->id ? " selected" : "" }}>{!! $cat_->title !!}</option>
        @endforeach
      </select>
    </fieldset>

    <button type="submit" class="mt-3 mb-2 btn btn-primary">@if(!empty($cat->id)) {{ __('Edit category') }} @else {{ __('Add new category') }} @endif</button>
  </form>
  </div>
</div>
@endsection