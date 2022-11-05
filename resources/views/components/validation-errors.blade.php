@if ($errors->any())
    <x-waterhole::alert type="danger">
        @if ($errors->count() === 1)
            {{ $errors->first() }}
        @else
            <p class="weight-bold">
                {{ __('waterhole::system.validation-errors-message') }}
            </p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </x-waterhole::alert>
@endif
