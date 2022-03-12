@php
    $title = __('waterhole::forum.edit-post-title');
@endphp

<x-waterhole::layout :title="$title">
    <div class="container section">
        <x-waterhole::dialog :title="$title" class="post-editor">
            <form
                method="POST"
                action="{{ route('waterhole.posts.update', ['post' => $post]) }}"
            >
                @csrf
                @method('PATCH')
                @return($post->url)

                <div class="form">
                    <x-waterhole::validation-errors/>

                    @include('waterhole::posts.fields')

                    <div class="row gap-xs wrap">
                        <button type="submit" class="btn bg-accent">
                            {{ __('waterhole::system.save-changes-button') }}
                        </button>

                        <x-waterhole::cancel :default="$post->url" class="btn"/>
                    </div>
                </div>
            </form>
        </x-waterhole::dialog>
    </div>
</x-waterhole::layout>
