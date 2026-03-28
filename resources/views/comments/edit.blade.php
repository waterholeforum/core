<x-waterhole::layout :title="__('waterhole::forum.edit-comment-title')">
    <div class="container section">
        <turbo-frame id="@domid($comment)" target="_top">
            <form
                method="POST"
                action="{{ $comment->url }}"
                class="comment"
                data-controller="dirty-form"
                data-shortcut-selection-key="{{ dom_id($comment) }}"
                data-shortcut-scope="form"
            >
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
                        autofocus
                    />

                    <div class="row gap-xs wrap justify-end">
                        <a
                            href="{{ $comment->post_url }}"
                            class="btn"
                            data-shortcut-trigger="navigation.close"
                        >
                            {{ __('waterhole::system.cancel-button') }}

                            <ui-tooltip>
                                {{ __('waterhole::system.cancel-button') }}
                                <x-waterhole::shortcut-label shortcut="navigation.close" />
                            </ui-tooltip>
                        </a>

                        <button
                            type="submit"
                            class="btn bg-accent"
                            data-shortcut-trigger="form.submit"
                        >
                            {{ __('waterhole::system.save-changes-button') }}
                        </button>
                    </div>
                </div>
            </form>
        </turbo-frame>
    </div>
</x-waterhole::layout>
