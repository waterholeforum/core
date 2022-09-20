<?php

namespace Waterhole\Views\Components;

use Illuminate\Http\Request;
use Illuminate\View\Component;
use Intervention\Image\Facades\Image;
use Waterhole\Models\User;

class UserProfileFields extends Component
{
    public function __construct(public ?User $user = null)
    {
    }

    public function render()
    {
        return view('waterhole::components.user-profile-fields');
    }

    public function save(Request $request): void
    {
        $data = $request->validate([
            'avatar' => 'nullable|image',
            'headline' => 'nullable|string|max:30',
            'bio' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:30',
            'website' => 'nullable|string|max:100',
            'show_online' => 'boolean',
        ]);

        $this->user->fill($data)->save();

        if ($request->input('remove_avatar')) {
            $this->user->removeAvatar();
        }

        if ($file = $request->file('avatar')) {
            $this->user->uploadAvatar(Image::make($file));
        }
    }
}
