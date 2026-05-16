@extends('layouts.app')

@section('content')
    @if (isset($person))
        <livewire:people.form :person="$person" :wire:key="'person-form-'.$person" />
    @else
        <livewire:people.form />
    @endif
@endsection
