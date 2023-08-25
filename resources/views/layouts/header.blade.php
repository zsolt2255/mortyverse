<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MortyVerse</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href='{{ asset('css/bootstrap.min.css') }}'>
    <link rel="stylesheet" href='{{ asset('css/dataTables.bootstrap4.min.css') }}'>
    <link rel="stylesheet" href='{{ asset('css/buttons.bootstrap.min.css') }}'>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" href="{{ asset('icons/icons8-rick-sanchez-120.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('icons/icons8-rick-sanchez-120.png') }}" type="image/x-icon">
</head>
