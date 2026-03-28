<?php

namespace Waterhole\View\Components;

use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\Models\User;

class CreatePostButton extends Component
{
    public Response $response;
    public bool $hasDraft;
    public ?Channel $targetChannel;

    public function __construct(public ?Channel $channel = null)
    {
        $user = Auth::user();
        [
            'targetChannel' => $this->targetChannel,
            'response' => $this->response,
        ] = static::resolveTarget($user, $this->channel);

        $this->hasDraft = (bool) $user?->drafts()->exists();
    }

    public static function resolveTarget(?User $user = null, mixed $channel = null): array
    {
        $gate = Gate::forUser($user ?: new User());
        $currentChannel = $channel ?: request()->route('channel');
        $currentChannel = $currentChannel instanceof Channel ? $currentChannel : null;
        $channelResponse = $currentChannel
            ? $gate->inspect('waterhole.channel.post', $currentChannel)
            : null;

        if ($channelResponse?->allowed()) {
            return ['targetChannel' => $currentChannel, 'response' => $channelResponse];
        }

        return [
            'targetChannel' => null,
            'response' => $gate->inspect('waterhole.post.create'),
        ];
    }

    public function enabled(): bool
    {
        return $this->response->allowed();
    }

    public function showDraft(): bool
    {
        return $this->enabled() && $this->hasDraft;
    }

    public function label(): string
    {
        if ($this->showDraft()) {
            return __('waterhole::forum.resume-draft-button');
        }

        return __(
            $this->targetChannel?->translations['waterhole::forum.create-post-button'] ??
                'waterhole::forum.create-post-button',
        );
    }

    public function href(): ?string
    {
        if (!$this->enabled()) {
            return null;
        }

        return route(
            'waterhole.posts.create',
            array_filter(['channel_id' => $this->targetChannel?->id]),
        );
    }

    public function forbiddenMessage(): string
    {
        return $this->response->message() ?: __('waterhole::system.forbidden-message');
    }

    public function render()
    {
        return $this->view('waterhole::components.create-post-button');
    }
}
