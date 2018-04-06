@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
        <h3 class="m-0">{{ __($form->id ? 'Edit post #' : 'New post') }}{{ $form->id ?? '' }}</h3>
    </div>
        <div class="card-body">
            @if($form->id)
            @openForm('panel.forms.update', 'patch', arg="form->id")
            @else
            @openForm('panel.forms.put', 'put')
            @endif

            <div class="row">
                <div class="col-sm-6">
                    <fieldset class="form-group">
                        <label for="title">{{ __('Title') }}</label>
                        <input type="text" value="{{ old('title', $form->title) }}" class="form-control {{ $errors->has('title') ? 'is-invalid' : ''}}" id="title" name="title">
                        @invalidFeedback('title')
                    </fieldset>
                </div>
                <div class="col-sm-6">
                    <fieldset class="form-group">
                        <label for="counter">{{ __('Counter') }}</label>
                        <input type="number" value="{{ old('counter', $form->counter) }}" name="counter" id="counter" class="form-control {{ $errors->has('counter') ? 'is-invalid' : ''}}" min="0">
                        @invalidFeedback('counter')
                    </fieldset>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <fieldset class="form-group">
                    <label for="starts_on">Data inizio <small class="text-muted">(ora se lasciato in bianco)</small></label>
                    <div class="input-group {{ ($errors->has('starts_on_date')||$errors->has('starts_on_time')) ? 'is-invalid' : '' }}">
                        <input class="form-control{{ $errors->has('starts_on_date') ? ' is-invalid' : ''}}" type="date" name="starts_on_date" value="{{ old('starts_on_date', !empty($form->starts_on) ? $form->starts_on->toDateString() : '') }}">
                        <input class="form-control{{ $errors->has('starts_on_time') ? ' is-invalid' : ''}}" type="time" name="starts_on_time" value="{{ old('starts_on_time', !empty($form->starts_on) ? $form->starts_on->format('H:i') : '') }}">
                    </div>
                    @if($errors->has('starts_on_date')||$errors->has('starts_on_time'))
                    <div class="invalid-feedback">
                        @foreach($errors->get('starts_on_date') as $err) {{$err}}<br> @endforeach
                        @foreach($errors->get('starts_on_time') as $err) {{$err}}<br> @endforeach
                    </div>
                    @endif
                    </fieldset>
                </div>
                <div class="col-sm-6">
                    <fieldset class="form-group">
                    <label>Diminuisci ogni:</label>
                    <div class="input-group {{ ($errors->has('counts_on_time') or $errors->has('counts_on_space')) ? 'is-invalid' : '' }}">
                        <input type="number" min="1" step="1" class="form-control" name="counts_on_time" value="{{ old('counts_on_time', !empty($form->id) ? $form->counts_on_time : '') }}" required>
                        <select class="custom-select" name="counts_on_space" required>
                        @foreach(config('app.timeSpaces') as $id => $timeSpace)
                            <option value="{{ $id }}" {{ $id == old('counts_on_space', !empty($form->id) ? $form->counts_on_space : null) ? 'selected' : '' }}>{{ $timeSpace }}</option>
                        @endforeach
                        </select>
                    </div>
                    @if($errors->has('counts_on_time') or $errors->has('counts_on_space'))
                        @foreach($errors->get('counts_on_time') as $err)
                        {{ $err}}<br>
                        @endforeach
                        @foreach($errors->get('counts_on_space') as $err)
                        {{ $err}}<br>
                        @endforeach
                    @endif
                    </fieldset>
                </div>
            </div>

            <fieldset class="form-group">
                <label for="countries">{{ __('Amazon countries') }}</label>
                <select name="countries[]" id="countries" class="form-control {{ $errors->has('countries') ? 'is-invalid' : '' }}" multiple>
                    @foreach(config('app.amz_countries') as $countries)
                    <option value="{{ $countries['domain'] }}"{{ in_array($countries['domain'], old('countries', $form->countries)) ? " selected" : "" }}>{{ $countries['flag'] }} Amazon.{{ $countries['domain'] }}</option>
                    @endforeach
                </select>
                @invalidFeedback('countries')
                <small class="text-muted">{!! __('Choose more countries by holding <code class="border rounded p-1">Ctrl</code> on PC or <code class="h6 border rounded">&#8984;</code> on macOS.') !!}</small>
            </fieldset>

            <fieldset class="form-group">
                <label for="category">{{ __('Category') }}</label>
                <select name="category" class="custom-select">
                    <option value="">{{ __('(no category)') }}</option>
                    @inject('categories', 'App\Services\Category')
                    @foreach($categories->tree() as $cat)
                    <option value="{{ $cat->id }}" {{ $form->category_id == $cat->id ? 'selected="selected"' : '' }}>{!! $cat->title !!}</option>
                    @endforeach
                </select>
            </fieldset>

            <fieldset class="form-group">
                <label for="description">{{ __('Description') }}</label>
                <div class="{{ $errors->has('description') ? 'is-invalid' : '' }}">
                    <textarea name="description" id="description">{{ old('description', $form->description) }}</textarea>
                </div>
                @invalidFeedback('description')
            </fieldset>


            <fieldset class="form-group">
                <label class="mb-2">{{ __('Pictures') }} <small class="text-muted">({{ __('optional') }})</small></label>
                <div class="row no-gutters justify-content-center align-middle rounded border p-3 tab-content" id="images-box" data-page="{{ route("panel.upload") }}" data-quantity="1">
        
                    <div id="image-1-wrapper" class="col-3 my-2 px-3">
                    <img id="image-1" src="{{ old('images.0', (!empty($form->pictures[0])) ? $form->pictures[0] : '/images/package.svg') }}" class="img-fluid rounded border image-field">
                    </div>
                    <div id="image-1-box" class="image-box col-9 d-none">
                    <div class="rounded border px-3 py-3">
                        <fieldset class="form-group">
                        <label for="image-1-field">{{ __('Picture link') }}</label>
                        <input type="text" name="images[]" id="image-1-field" data-target="image-1" value="{{ old('images.0', !empty($form->pictures[0]) ? $form->pictures[0] : '') }}" placeholder="http://" class="image-field-input form-control">
                        </fieldset>
                        <div class="btn-group">
                        <button class="btn btn-primary upload-imageBox" data-target="image-1" type="button">{{ __('Upload picture') }}</button>
                        </div>
                    </div>
                    </div>
        
                    @if(count(old('images', $form->pictures)) > 1)
                    @for ($i=1; $i < count(old('images', $form->pictures)); $i++)
                    <div id="image-{{ $i+1 }}-wrapper" class="col-3 my-2 px-3">
                        <img id="image-{{ $i+1 }}" src="{{ old('images.'.$i, !empty($form->pictures[$i]) ? $form->pictures[$i] : '/images/package.svg') }}" class="img-fluid rounded border image-field">
                    </div>
                    <div id="image-{{ $i+1 }}-box" class="image-box col-9 d-none">
                        <div class="rounded border px-3 py-3">
                        <fieldset class="form-group">
                            <label for="image-{{ $i+1 }}-field">{{ __('Picture link') }}</label>
                            <input type="text" name="images[]" id="image-{{ $i+1 }}-field" data-target="image-{{ $i+1 }}" value="{{ old('images.'.$i, !empty($form->pictures[$i]) ? $form->pictures[$i] : '') }}" placeholder="http://" class="image-field-input form-control">
                        </fieldset>
                        <div class="btn-group">
                            <button class="btn btn-primary upload-imageBox" data-target="image-{{ $i+1 }}" type="button">{{ __('Upload picture') }}</button>
                            <button type="button" class="btn btn-danger image-remove" data-target="image-{{ $i+1 }}">{{ __('Remove picture') }}</button>
                        </div>
                        </div>
                    </div>
                    @endfor
                    @endif
        
                    <div title="Aggiungi nuova immagine" id="image-add-wrapper" class="col-3 my-2 px-3">
                    <svg id="image-add" viewBox="0 0 150 150" class="btn btn-outline-secondary p-0 rounded border d-block img-fluid image-field svg-outline-secondary">
                        <g transform="translate(-11.341498,-79.714325)" id="layer1">
                        <path id="rect819" transform="scale(0.26458333)" d="m 298.30859,463.04688 c -4.50856,-10e-6 -8.13867,3.62815 -8.13867,8.13671 v 77.40821 h -78.75586 c -3.37499,0 -6.09179,2.7168 -6.09179,6.09179 v 60.12696 c 0,3.37499 2.7168,6.09179 6.09179,6.09179 h 78.75586 v 77.40821 c 0,4.50856 3.63011,8.13672 8.13867,8.13672 h 56.04297 c 4.50856,0 8.13672,-3.62816 8.13672,-8.13672 v -77.40821 h 78.75781 c 3.375,0 6.0918,-2.7168 6.0918,-6.09179 v -60.12696 c 0,-3.37499 -2.7168,-6.09179 -6.0918,-6.09179 h -78.75781 v -77.40821 c 0,-4.50856 -3.62816,-8.13671 -8.13672,-8.13671 z" style="opacity:1;vector-effect:none;fill-opacity:1;fill-rule:evenodd;stroke-width:1.19450116;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:normal" />
                        </g>
                    </svg>
                    </div>
                </div>
                <small class="text-muted">{{ __('The images are displayed in the same order as here, from the first till the last.') }}</small>
            </fieldset>

            <button type="submit" class="btn btn-primary">{{ __($form->id ? 'Edit' : 'Create') }}</button>
        </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>$("#description").mde();</script>
@endsection