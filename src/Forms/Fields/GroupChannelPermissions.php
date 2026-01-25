<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\Group;
use Waterhole\Models\Structure;

class GroupChannelPermissions extends Field
{
    public Collection $structure;
    public Collection $abilities;

    public function __construct(public ?Group $model)
    {
        $this->structure = Structure::with('content')->orderBy('position')->get();

        // Construct an array of all abilities that apply to the structure
        // content to use as columns for the permission grid.
        $this->abilities = $this->structure
            ->flatMap(function (Structure $node) {
                return method_exists($node->content, 'abilities')
                    ? $node->content->abilities()
                    : [];
            })
            ->unique();
    }

    public function render(): string
    {
        return <<<'blade'
            <div class="field">
                <div class="field__label">
                    {{ __('waterhole::cp.group-channel-permissions-title') }}
                </div>
                <div>
                    <div class="table-container card">
                        <table
                            class="table permission-grid"
                            data-controller="permission-grid"
                        >
                            <colgroup>
                                <col>
                                @foreach ($abilities as $ability)
                                    <col>
                                @endforeach
                            </colgroup>
                            <thead>
                                <tr>
                                    <td></td>
                                    @foreach ($abilities as $ability)
                                        <th>{{ __("waterhole::system.ability-$ability") }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($structure as $node)
                                    <tr>
                                        <th>
                                            @if ($node->content instanceof Waterhole\Models\Channel)
                                                <x-waterhole::channel-label :channel="$node->content"/>
                                            @else
                                                {{ $node->content->name }}
                                            @endif
                                        </th>
                                        @foreach ($abilities as $ability)
                                            @if (method_exists($node->content, 'abilities') && in_array($ability, $node->content->abilities()))
                                                @php
                                                    $key = $node->content->getMorphClass().':'.$node->content->getKey();
                                                @endphp
                                                <td class="choice-cell">
                                                    <label class="choice">
                                                        <input
                                                            type="hidden"
                                                            name="permissions[{{ $key }}][{{ $ability }}]"
                                                            value="0"
                                                        >
                                                        <input
                                                            type="checkbox"
                                                            name="permissions[{{ $key }}][{{ $ability }}]"
                                                            value="1"
                                                            {{--
                                                                If members are allowed, then this group *must* be allowed too,
                                                                so disable the checkbox.
                                                            --}}
                                                            @disabled(Waterhole::permissions()->can(Waterhole\Models\Group::member(), $ability, $node->content))
                                                            {{--
                                                                Check this box if it was checked before, or if the ability is
                                                                allowed for this group, or for members in general.
                                                            --}}
                                                            @checked(old("permissions.$key.$ability", Waterhole::permissions()->can($model ?? Waterhole\Models\Group::member(), $ability, $node->content)))
                                                            {{--
                                                                Non-"view" abilities depend on the "view" ability being allowed.
                                                            --}}
                                                            @if ($ability !== 'view') data-depends-on="permissions[{{ $key }}][view]" @endif
                                                        >
                                                    </label>
                                                </td>
                                            @else
                                                <td></td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->appendRules(['permissions' => ['array']]);
    }

    public function saved(FormRequest $request): void
    {
        $this->model->savePermissions($request->validated('permissions'));
    }
}
