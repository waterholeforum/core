<x-waterhole::user-profile :user="Auth::user()" title="Edit Profile">
    <form action="{{ route('waterhole.preferences.profile') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card form-groups">
            <x-waterhole::user-profile-fields :user="Auth::user()"/>

            <div>
                <button type="submit" class="btn btn--primary btn--wide">Save Changes</button>
            </div>
        </div>
    </form>
</x-waterhole::user-profile>
