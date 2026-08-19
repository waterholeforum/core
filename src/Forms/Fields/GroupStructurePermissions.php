<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Validator;
use Illuminate\View\View;
use Waterhole\Forms\Field;
use Waterhole\Models\Channel;
use Waterhole\Models\Group;
use Waterhole\Models\Model;
use Waterhole\Models\Structure;
use Waterhole\Models\StructureHeading;
use Waterhole\Permissions\PermissionRepository;

class GroupStructurePermissions extends Field
{
    public Collection $structure;
    public Collection $abilities;
    public ?Group $inheritedGroup;
    public Group $permissionGroup;

    public function __construct(
        public ?Group $model,
    ) {
        $this->structure = Structure::flattenTree(
            Structure::withoutGlobalScopes()
                ->tree()
                ->with('content')
                ->inSiblingOrder()
                ->get()
                ->toTree(),
        );
        $this->structure->each(
            fn(Structure $node) => $node->content?->setRelation('structure', $node),
        );

        $this->inheritedGroup = match (true) {
            $this->model?->isMember() => Group::guest(),
            $this->model?->isCustom() => Group::member(),
            default => null,
        };

        $this->permissionGroup = $this->model->exists ? $this->model : Group::member();

        // Construct an array of all abilities that apply to the structure
        // content to use as columns for the permission grid.
        $this->abilities = $this->structure->flatMap(fn(Structure $node) => (
            method_exists($node->content, 'abilities') ? $node->content->abilities() : []
        ))->unique();

        if ($this->model?->isGuest()) {
            $this->abilities = $this->abilities->filter(fn($ability) => $ability === 'view');
        } elseif ($this->model?->isMember()) {
            $this->abilities = $this->abilities->reject(fn($ability) => $ability === 'moderate');
        }
    }

    public function shouldRender(): bool
    {
        return !$this->model->isAdmin();
    }

    public function render(): View
    {
        return $this->view('waterhole::components.cp.group-structure-permissions');
    }

    public function allows(Group $group, string $ability, Model $scope): bool
    {
        return app(PermissionRepository::class)->can($group, $ability, $scope);
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['permissions' => ['array']]);

        $validator->after(function (Validator $validator) {
            $permissions = $validator->getData()['permissions'] ?? [];
            $canView = [];

            foreach ($this->structure as $node) {
                $viewKey = $node->getMorphClass() . ':' . $node->getKey();

                if ($node->content instanceof StructureHeading) {
                    if ($permissions[$viewKey]['view'] ?? false) {
                        $validator->errors()->add(
                            "permissions.$viewKey.view",
                            __('validation.prohibited', ['attribute' => 'permission']),
                        );
                    }

                    continue;
                }

                $view =
                    (bool) ($permissions[$viewKey]['view'] ?? false) || $this->inheritsView($node);
                $parentView = !$node->parent_id || ($canView[$node->parent_id] ?? false);

                if ($view && !$parentView) {
                    $validator->errors()->add(
                        "permissions.$viewKey.view",
                        __('waterhole::cp.group-structure-permission-ancestor-required-message'),
                    );
                }

                $canView[$node->getKey()] = $view && $parentView;

                if (!$node->content instanceof Channel) {
                    continue;
                }

                $actionKey = $node->content->getMorphClass() . ':' . $node->content->getKey();
                if (
                    collect($permissions[$actionKey] ?? [])->except('view')->contains(true)
                    && !$view
                ) {
                    $validator->errors()->add(
                        "permissions.$actionKey",
                        __('waterhole::cp.group-structure-permission-ancestor-required-message'),
                    );
                }
            }
        });
    }

    public function saved(FormRequest $request): void
    {
        $this->model->savePermissions($request->validated('permissions'));
    }

    private function inheritsView(Structure $node): bool
    {
        return $this->inheritedGroup && $this->allows($this->inheritedGroup, 'view', $node);
    }
}
