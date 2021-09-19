@props(['errors'])

@if ($errors->any())
    <div class="alert alert--danger content">
        <p>Please fix the following errors:</p>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
