@extends('app')

@section('buttons')
    @include('components/buttons')
@endsection

@section('table')
    @include('components/table')
@endsection

@section('scripts')
    <script>
        const config = {
            routes: {
                episodes: {
                    sync: "{{ route('episodes.sync') }}",
                    index: "{{ route('episodes.index') }}",
                    characters: "{{ route('episodes.characters', ['episode' => 'null']) }}"
                }
            }
        };
    </script>
@endsection

@include('components/modal')
