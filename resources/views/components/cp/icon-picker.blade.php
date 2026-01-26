<div data-controller="icon-picker" class="icon-picker">
    @if (is_string($value) && $content)
        <div class="row gap-sm" data-icon-picker-target="preview">
            @icon($value, ['class' => 'text-md'])
            <button type="button" class="btn" data-action="icon-picker#change">
                {{ __('waterhole::system.icon-picker-change-button') }}
            </button>
        </div>
    @endif

    <div
        class="row gap-sm align-start"
        data-icon-picker-target="form"
        data-controller="reveal"
        @if (is_string($value) && $content) hidden @endif
    >
        <select style="width: auto" data-reveal-target="if" name="{{ $name }}[type]">
            <option value="">
                {{ __('waterhole::system.icon-picker-none-option') }}
            </option>
            <option value="emoji" @selected($type === 'emoji')>
                {{ __('waterhole::system.icon-picker-emoji-option') }}
            </option>
            <option value="svg" @selected($type === 'svg')>
                {{ __('waterhole::system.icon-picker-svg-option') }}
            </option>
            <option value="file" @selected($type === 'file')>
                {{ __('waterhole::system.icon-picker-image-option') }}
            </option>
        </select>

        <div
            class="stack gap-xs full-width"
            data-reveal-target="then"
            data-reveal-value="emoji"
            data-icon-picker-target="emoji"
        >
            <input
                type="text"
                name="{{ $name }}[emoji]"
                @if ($type === 'emoji') value="{{ $content }}" @endif
                style="width: 5ch"
            />

            <ui-popup data-controller="emoji-picker" hidden>
                <button type="button" class="btn btn--icon">
                    @icon($type === 'emoji' && $content ? 'emoji:' . $content : 'tabler-mood-smile')
                </button>
                <div class="menu emoji-picker" hidden>
                    <emoji-picker></emoji-picker>
                </div>
            </ui-popup>
        </div>

        <div class="stack gap-xs full-width" data-reveal-target="then" data-reveal-value="svg">
            <input
                type="text"
                list="icons"
                name="{{ $name }}[svg]"
                @if ($type === 'svg') value="{{ $content }}" @endif
            />

            <div class="field__description">
                {{
                    __('waterhole::system.icon-picker-svg-description', [
                        'sets' => implode(', ', array_map(fn ($set) => $set['prefix'], app(BladeUI\Icons\Factory::class)->all())),
                    ])
                }}
                <a
                    href="https://blade-ui-kit.com/blade-icons#search"
                    target="_blank"
                    rel="noopener"
                    class="with-icon"
                >
                    <span>{{ __('waterhole::system.icon-picker-svg-search-link') }}</span>
                    @icon('tabler-external-link')
                </a>
            </div>

            <datalist id="icons">
                @foreach (app(BladeUI\Icons\IconsManifest::class)->getManifest($sets = app(BladeUI\Icons\Factory::class)->all()) as $set => $paths)
                    @foreach ($paths as $icons)
                        @foreach ($icons as $icon)
                            <option value="{{ $sets[$set]['prefix'] }}-{{ $icon }}"></option>
                        @endforeach
                    @endforeach
                @endforeach
            </datalist>
        </div>

        <div class="stack gap-xs full-width" data-reveal-target="then" data-reveal-value="file">
            <input
                type="file"
                name="{{ $name }}[file]"
                accept=".jpg,.jpeg,.png,.bmp,.gif,.webp,.svg"
            />
        </div>
    </div>
</div>
