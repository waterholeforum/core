<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Waterhole\Database\Migration;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;
use Waterhole\Models\Structure;
use Waterhole\Models\StructureHeading;
use Waterhole\Models\StructureLink;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('structure', function (Blueprint $table) {
            $table
                ->foreignId('parent_id')
                ->nullable()
                ->after('id')
                ->constrained('structure')
                ->restrictOnDelete();
            $table->index(['parent_id', 'is_listed', 'position']);
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropIndex(['scope_type', 'scope_id']);
            $table->dropIndex(['recipient_type', 'recipient_id']);
            $table->index(['scope_type', 'scope_id', 'ability'], 'permissions_scope_lookup');
            $table->index(
                ['recipient_type', 'recipient_id', 'ability'],
                'permissions_recipient_lookup',
            );
        });

        Schema::table('channels', function (Blueprint $table) {
            $table->index('name');
        });

        $types = [
            (new Channel())->getMorphClass(),
            (new Page())->getMorphClass(),
            (new StructureLink())->getMorphClass(),
        ];
        $structureType = (new Structure())->getMorphClass();

        $permissions = DB::table('permissions')
            ->where('ability', 'view')
            ->whereIn('scope_type', $types)
            ->orderBy('id')
            ->get();

        foreach ($permissions as $permission) {
            $nodeId = DB::table('structure')
                ->where('content_type', $permission->scope_type)
                ->where('content_id', $permission->scope_id)
                ->value('id');

            if (!$nodeId) {
                continue;
            }

            DB::table('permissions')
                ->where('id', $permission->id)
                ->update([
                    'scope_type' => $structureType,
                    'scope_id' => $nodeId,
                ]);
        }

        $headingType = (new StructureHeading())->getMorphClass();

        DB::table('structure')->where('content_type', $headingType)->update(['is_listed' => true]);
    }

    public function down(): void
    {
        $structureType = (new Structure())->getMorphClass();

        $permissions = DB::table('permissions')
            ->where('ability', 'view')
            ->where('scope_type', $structureType)
            ->orderBy('id')
            ->get();

        foreach ($permissions as $permission) {
            $node = DB::table('structure')->find($permission->scope_id);

            if (!$node) {
                continue;
            }

            DB::table('permissions')
                ->where('id', $permission->id)
                ->update([
                    'scope_type' => $node->content_type,
                    'scope_id' => $node->content_id,
                ]);
        }

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropIndex('permissions_scope_lookup');
            $table->dropIndex('permissions_recipient_lookup');
            $table->index(['scope_type', 'scope_id']);
            $table->index(['recipient_type', 'recipient_id']);
        });

        Schema::table('channels', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });

        Schema::table('structure', function (Blueprint $table) {
            $table->dropIndex(['parent_id', 'is_listed', 'position']);
            $table->dropConstrainedForeignId('parent_id');
        });
    }
};
