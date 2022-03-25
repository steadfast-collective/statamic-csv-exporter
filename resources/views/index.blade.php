@extends('statamic::layout')
@section('title', __('CSV Exporter'))

@section('content')
    <div class="flex items-center justify-between">
        <h1>{{ __('CSV Exporter') }}</h1>
    </div>

    <div class="mt-3 card">
        <p class="mb-2">Which collections would you like to export?</p>

        <form method="POST" action="{{ cp_route('utilities.csv-exporter.export') }}">
            @csrf

            @foreach ($collections as $collection)
                <div class="flex items-center mb-2">
                    <input type="checkbox" name="collections[]" value="{{ $collection->handle() }}" id="collection-{{ $collection->handle() }}" class="mr-2">
                    <label for="collection-{{ $collection->handle() }}">{{ $collection->title() }}</label>
                </div>
            @endforeach

            @error('collections')
                <p class="text-red mb-2">{{ $message }}</p>
            @enderror

            <div class="mt-4">
                <button type="submit" class="btn-primary">Export</button>
            </div>
        </form>
    </div>
@stop
