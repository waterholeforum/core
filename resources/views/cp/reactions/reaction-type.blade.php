@php
    $title = isset($reactionType)
        ? __('waterhole::cp.edit-reaction-type-title')
        : __('waterhole::cp.create-reaction-type-title');
@endphp

<x-waterhole::cp :title="$title">
    <turbo-frame id="modal">
        <x-waterhole::dialog :title="$title" class="dialog--sm">
            <form
                method="POST"
                action="{{
                    isset($reactionType)
                        ? route('waterhole.cp.reaction-sets.reaction-types.update', compact('reactionSet', 'reactionType'))
                        : route('waterhole.cp.reaction-sets.reaction-types.store', compact('reactionSet'))
                }}"
                enctype="multipart/form-data"
            >
                @csrf
                @if (isset($reactionType))
                    @method('PATCH')
                @endif

                <div class="stack gap-lg">
                    <x-waterhole::validation-errors />

                    @components($form->fields())

                    <div class="row gap-xs wrap">
                        <button type="submit" class="btn bg-accent btn--wide">
                            {{
                                isset($reactionType)
                                    ? __('waterhole::system.save-changes-button')
                                    : __('waterhole::system.create-button')
                            }}
                        </button>

                        <a href="{{ url()->previous() }}" class="btn" data-action="modal#hide">
                            {{ __('waterhole::system.cancel-button') }}
                        </a>
                    </div>
                </div>
            </form>
        </x-waterhole::dialog>
    </turbo-frame>
</x-waterhole::cp>
