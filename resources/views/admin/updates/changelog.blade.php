<turbo-frame id="{{ $package['name'] }}_changelog">
    {{ $changelog ? Illuminate\Mail\Markdown::parse($changelog) : 'No changelog available.' }}
</turbo-frame>
