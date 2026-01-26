<div {{ $attributes->class($showBanner ? 'flag-container' : '') }}>
    @if ($showBanner)
        <x-waterhole::alert class="bg-activity-soft p-md wrap" icon="tabler-flag">
            @if ($canModerate)
                <ui-popup placement="bottom-start" class="row">
                    <button
                        type="button"
                        class="btn btn--sm btn--transparent btn--start btn--end color-inherit text-xs -my-xs"
                    >
                        <x-waterhole::flag-summary :subject="$subject" />
                        @icon('tabler-chevron-down', ['class' => 'icon--narrow text-xxs'])
                    </button>

                    <div hidden class="menu flags-menu" tabindex="-1">
                        @foreach ($subject->pendingFlags->sortByDesc('created_at') as $flag)
                            <div class="menu-item is-inert">
                                <div>
                                    <div class="menu-item__title">
                                        @if ($flag->createdBy)
                                            <x-waterhole::user-label
                                                :user="$flag->createdBy"
                                                link
                                            />
                                        @else
                                            <span class="color-muted">
                                                {{ __('waterhole::forum.report-system-user') }}
                                            </span>
                                        @endif
                                        ·
                                        <x-waterhole::relative-time
                                            :datetime="$flag->created_at"
                                        />
                                    </div>
                                    <div class="menu-item__description">
                                        {{
                                            Lang::has($key = "waterhole::forum.report-reason-$flag->reason-label")
                                                ? __($key)
                                                : Str::headline($flag->reason)
                                        }}
                                        @if ($flag->note)
                                                · {{ $flag->note }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </ui-popup>
            @else
                <div class="weight-medium">
                    {{ __('waterhole::forum.pending-approval-title') }}
                </div>
            @endif

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
