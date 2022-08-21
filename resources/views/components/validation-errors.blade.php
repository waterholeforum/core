@if ($errors->any())
    <x-waterhole::alert type="danger" :class="$errors->count() > 1 ? 'alert--lg' : ''">
        @if ($errors->count() === 1)
            {{ $errors->first() }}
        @else
            <p class="weight-bold">The following errors were found:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </x-waterhole::alert>
@endif
