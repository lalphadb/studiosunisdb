@props(['title' => 'Administration'])

@extends('layouts.admin')

@section('title', $title)

@section('content')
    {{ $slot }}
@endsection
