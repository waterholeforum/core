<?php

namespace Waterhole\Permissions;

use Waterhole\Models\Channel;
use Waterhole\Models\Concerns\Structurable;
use Waterhole\Models\Group;
use Waterhole\Models\Model;
use Waterhole\Models\Permission;
use Waterhole\Models\Structure;
use Waterhole\Models\StructureHeading;
use Waterhole\Models\User;

/**
 * Request-scoped access to permissions.
 *
 * Relevant grants and effective structure visibility are loaded lazily and
 * compiled into lookup sets for the remainder of the request.
 */
class PermissionRepository
{
    private const GLOBAL_SCOPE = '*';

    private array $grants = [];

    private array $permissionSets = [];

    private ?array $structureNodes = null;

    private array $visibleStructure = [];

    public function can(User|Group|null $recipient, string $ability, Model|string $scope): bool
    {
        if ($recipient instanceof User && $recipient->isAdmin()) {
            return true;
        }

        $permissions = $this->permissionSet(
            $recipient,
            $ability,
            is_string($scope) ? new $scope() : $scope,
        );

        return is_string($scope) ? $permissions !== [] : isset($permissions[$scope->getKey()]);
    }

    public function ids(User|Group|null $recipient, string $ability, string $scope): array
    {
        $ids = array_keys($this->permissionSet($recipient, $ability, new $scope()));

        return array_map(fn($id) => $id === self::GLOBAL_SCOPE ? null : $id, $ids);
    }

    public function flush(): void
    {
        $this->grants = [];
        $this->permissionSets = [];
        $this->structureNodes = null;
        $this->visibleStructure = [];
    }

    private function permissionSet(User|Group|null $recipient, string $ability, Model $scope): array
    {
        $key = implode('|', [
            $this->recipientKey($recipient),
            $ability,
            $scope->getMorphClass(),
        ]);

        return $this->permissionSets[$key] ??= $this->resolvePermissionSet(
            $recipient,
            $ability,
            $scope,
        );
    }

    private function resolvePermissionSet(
        User|Group|null $recipient,
        string $ability,
        Model $scope,
    ): array {
        if ($scope instanceof Structure) {
            return $ability === 'view'
                ? $this->visibleStructure($recipient)['ids']
                : $this->grants($recipient)[$scope->getMorphClass()][$ability] ?? [];
        }

        if ($ability === 'view' && $this->isStructurable($scope)) {
            return $this->visibleStructure($recipient)['content'][$scope->getMorphClass()] ?? [];
        }

        $permissions = $this->grants($recipient)[$scope->getMorphClass()][$ability] ?? [];

        if ($scope instanceof Channel) {
            $visible = $this->visibleStructure($recipient)['content'][$scope->getMorphClass()]
            ?? [];
            $permissions = array_intersect_key($permissions, $visible);
        }

        return $permissions;
    }

    private function grants(User|Group|null $recipient): array
    {
        $key = $this->recipientKey($recipient);

        if (array_key_exists($key, $this->grants)) {
            return $this->grants[$key];
        }

        $grants = [];
        $query = Permission::query()->toBase()->select('scope_type', 'scope_id', 'ability');

        $query->where(function ($query) use ($recipient) {
            foreach ($this->recipients($recipient) as $type => $ids) {
                $query->orWhere(
                    fn($query) => $query->where('recipient_type', $type)->whereIn(
                        'recipient_id',
                        $ids,
                    ),
                );
            }
        });

        foreach ($query->cursor() as $permission) {
            $scopeId = $permission->scope_id === null
                ? self::GLOBAL_SCOPE
                : (int) $permission->scope_id;
            $grants[$permission->scope_type][$permission->ability][$scopeId] = true;
        }

        return $this->grants[$key] = $grants;
    }

    private function visibleStructure(User|Group|null $recipient): array
    {
        $key = $this->recipientKey($recipient);

        if (array_key_exists($key, $this->visibleStructure)) {
            return $this->visibleStructure[$key];
        }

        $nodes = $this->structureNodes();
        $grants = $this->grants($recipient)[(new Structure())->getMorphClass()]['view'] ?? [];
        $headingType = (new StructureHeading())->getMorphClass();
        $visibility = [];
        $resolving = [];

        $isVisible = function (int $id) use (
            &$isVisible,
            &$visibility,
            &$resolving,
            $nodes,
            $grants,
            $headingType,
        ): bool {
            if (array_key_exists($id, $visibility)) {
                return $visibility[$id];
            }

            if (!isset($nodes[$id]) || isset($resolving[$id])) {
                return false;
            }

            $resolving[$id] = true;
            $node = $nodes[$id];
            $visible =
                ($node['content_type'] === $headingType || isset($grants[$id]))
                && (!$node['parent_id'] || $isVisible($node['parent_id']));
            unset($resolving[$id]);

            return $visibility[$id] = $visible;
        };

        $visible = ['ids' => [], 'content' => []];

        foreach ($nodes as $id => $node) {
            if (!$isVisible($id)) {
                continue;
            }

            $visible['ids'][$id] = true;
            $visible['content'][$node['content_type']][$node['content_id']] = true;
        }

        return $this->visibleStructure[$key] = $visible;
    }

    private function structureNodes(): array
    {
        if ($this->structureNodes !== null) {
            return $this->structureNodes;
        }

        $nodes = [];

        foreach (Structure::withoutGlobalScopes()
            ->toBase()
            ->select('id', 'parent_id', 'content_type', 'content_id')
            ->cursor() as $node) {
            $nodes[(int) $node->id] = [
                'parent_id' => $node->parent_id === null ? null : (int) $node->parent_id,
                'content_type' => $node->content_type,
                'content_id' => (int) $node->content_id,
            ];
        }

        return $this->structureNodes = $nodes;
    }

    private function recipients(User|Group|null $recipient): array
    {
        $groupType = (new Group())->getMorphClass();

        if (!$recipient || $recipient instanceof Group && $recipient->isGuest()) {
            return [$groupType => [Group::GUEST_ID]];
        }

        $groupIds = [Group::GUEST_ID, Group::MEMBER_ID];

        if ($recipient instanceof Group) {
            return [$groupType => array_unique([...$groupIds, $recipient->getKey()])];
        }

        return [
            $groupType => array_unique([...$groupIds, ...$recipient->groups->modelKeys()]),
            $recipient->getMorphClass() => [$recipient->getKey()],
        ];
    }

    private function recipientKey(User|Group|null $recipient): string
    {
        if (!$recipient || $recipient instanceof Group && $recipient->isGuest()) {
            return 'guest';
        }

        return $recipient->getMorphClass() . ':' . $recipient->getKey();
    }

    private function isStructurable(Model $scope): bool
    {
        return in_array(Structurable::class, class_uses_recursive($scope), true);
    }
}
