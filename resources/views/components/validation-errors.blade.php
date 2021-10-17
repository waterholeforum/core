@props(['errors'])

@if ($errors->any())
    <x-waterhole::alert type="danger">
        @if ($errors->count() === 1)
            <h4>Error</h4>
            <p>{{ $errors->first() }}</p>
        @else
            <h4>The following errors were found:</h4>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </x-waterhole::alert>
@endif
