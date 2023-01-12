<x-waterhole::layout :title="__('waterhole::forum.edit-comment-title')">
    <div class="container section">
        <turbo-frame id="@domid($comment)" target="_top">
            <form
                method="POST"
                action="{{ $comment->url }}"
                class="comment"
            >
                @csrf
                @method('PATCH')

                <div class="comment__main">
                    <x-waterhole::attribution
                        :user="$comment->user"
                        :date="$comment->created_at"
                    />

                    <x-waterhole::validation-errors/>

                    <x-waterhole::text-editor
                        name="body"
                        :value="old('body', $comment->body)"
                    />

                    <div class="row gap-xs wrap justify-end">
                        <a
                            href="{{ $comment->post_url }}"
                            class="btn"
                        >{{ __('waterhole::system.cancel-button') }}</a>

                        <button
                            type="submit"
                            class="btn bg-accent"
                        >{{ __('waterhole::system.save-changes-button') }}</button>
                    </div>
                </div>
            </form>
        </turbo-frame>
    </div>
</x-waterhole::layout>
