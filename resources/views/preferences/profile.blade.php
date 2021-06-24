<x-waterhole::user-profile
    :user="Auth::user()"
    :title="__('waterhole::user.edit-profile-title')"
>
    <form
        action="{{ route('waterhole.preferences.profile') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        <div class="card card__body stack dividers">
            @components($form->fields())

            <div>
                <button type="submit" class="btn bg-accent btn--wide">
                    {{ __('waterhole::system.save-changes-button') }}
                </button>
            </div>
        </div>
    </form>
</x-waterhole::user-profile>
