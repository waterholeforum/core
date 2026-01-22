<div {{ $attributes->class($showBanner ? 'flag-container' : '') }}>
    @if ($showBanner)
        <x-waterhole::alert class="bg-activity-soft p-md" icon="tabler-flag">
            <div class="weight-bold">
                @if ($canModerate)
                    <x-waterhole::flag-summary :subject="$subject" />
                @else
                    {{ __('waterhole::forum.pending-approval-title') }}
                @endif
            </div>

            @if ($canModerate && $subject)
                <x-slot:action>
                    <div class="row gap-xs mx-xxs">
                        <x-waterhole::action-button
                            :for="$subject"
                            action="Waterhole\Actions\DismissFlags"
                            class="btn"
                        />
                        @if ($subject instanceof Waterhole\Models\Post)
                            <x-waterhole::action-button
                                :for="$subject"
                                action="Waterhole\Actions\TrashPost"
                                class="btn"
                            />
                        @elseif ($subject instanceof Waterhole\Models\Comment)
                            <x-waterhole::action-button
                                :for="$subject"
                                action="Waterhole\Actions\RemoveComment"
                                class="btn"
                            />
                        @endif
                    </div>
                </x-slot>
            @endif
        </x-waterhole::alert>
    @endif

    {{ $slot ?? '' }}
</div>
