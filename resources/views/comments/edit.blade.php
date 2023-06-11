<x-waterhole::layout :title="__('waterhole::forum.edit-comment-title')">
    <div class="container section">
        <turbo-frame id="@domid($comment)" target="_top">
            <form method="POST" action="{{ $comment->url }}" class="comment">
                @csrf
                @method('PATCH')

                <div class="comment__main stack gap-md">
                    <x-waterhole::attribution
                        :user="$comment->user"
                        :date="$comment->created_at"
                    />

                    <x-waterhole::validation-errors />

                    <x-waterhole::text-editor
                        id="comment-body"
                        name="body"
                        :value="old('body', $comment->body)"
                        style="min-height: 40vh"
                    />

                    <div class="row gap-xs wrap justify-end">
                        <a href="{{ $comment->post_url }}" class="btn">
                            {{ __('waterhole::system.cancel-button') }}
                        </a>

                        <button
                            type="submit"
                            class="btn bg-accent"
                            data-hotkey="Meta+Enter,Ctrl+Enter"
                            data-hotkey-scope="comment-body"
                        >
                            {{ __('waterhole::system.save-changes-button') }}
                        </button>
                    </div>
                </div>
            </form>
        </turbo-frame>
    </div>
</x-waterhole::layout>
