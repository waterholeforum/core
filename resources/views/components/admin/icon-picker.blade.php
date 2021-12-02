<div data-controller="icon-picker" class="icon-picker">
    @if (is_string($value) && $value)
        <div class="cluster-sm" data-icon-picker-target="preview">
            <x-waterhole::icon icon="{{ $value }}" class="text-md"/>
            <button type="button" class="btn" data-action="icon-picker#change">Change</button>
        </div>
    @endif

    <div
        class="cluster-sm align-start"
        data-icon-picker-target="form"
        data-controller="reveal"
        @if (is_string($value) && $value) hidden @endif
    >
        <select class="input" style="width: auto" data-reveal-target="if" name="{{ $name }}[type]">
            <option value="">None</option>
            <option value="emoji" @if ($type === 'emoji') selected @endif>Emoji</option>
            <option value="svg" @if ($type === 'svg') selected @endif>SVG Icon</option>
            <option value="file" @if ($type === 'file') selected @endif>Image</option>
        </select>

        <div class="stack-xs full-width" data-reveal-target="then" data-reveal-value="emoji">
            <input type="text" class="input" name="{{ $name }}[emoji]" @if ($type === 'emoji') value="{{ $content }}" @endif>
            <div class="field__description">Enter a single emoji character using your system keyboard, or paste one from <a href="https://emojipedia.org" target="_blank" rel="noopener">Emojipedia</a>.</div>
        </div>

        <div class="stack-xs full-width" data-reveal-target="then" data-reveal-value="svg">
            <input type="text" class="input" list="icons" name="{{ $name }}[svg]" @if ($type === 'svg') value="{{ $content }}" @endif>
            <div class="field__description">Enter the name of a <a href="https://blade-ui-kit.com/blade-icons#search" target="_blank" rel="noopener">Blade Icon</a> from one of the following installed sets: {{ implode(', ', array_map(fn($set) => $set['prefix'], app(BladeUI\Icons\Factory::class)->all())) }}</div>
            <div class="field__description"><a href="" class="with-icon"><x-waterhole::icon icon="heroicon-s-question-mark-circle"/>Learn more about SVG icons</a></div>
            <datalist id="icons">
                @foreach (app(BladeUI\Icons\IconsManifest::class)->getManifest($sets = app(BladeUI\Icons\Factory::class)->all()) as $set => $paths)
                    @foreach ($paths as $icons)
                        @foreach ($icons as $icon)
                            <option value="{{ $sets[$set]['prefix'] }}-{{ $icon }}">
                        @endforeach
                    @endforeach
                @endforeach
            </datalist>
        </div>

        <div class="stack-xs full-width" data-reveal-target="then" data-reveal-value="file">
            <input type="file" class="input" name="{{ $name }}[file]">
        </div>
    </div>
</div>
