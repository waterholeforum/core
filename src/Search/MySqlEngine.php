<?php

/*
 * This file is part of Waterhole.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Waterhole\Search;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Builder;
use Laravel\Scout\Engines\Engine;

class MySqlEngine extends Engine
{
    public function update($models)
    {
        if ($models->isEmpty()) {
            return;
        }

        $objects = $models->map(function ($model) {
            if (empty($searchableData = $model->toSearchableArray())) {
                return null;
            }

            return array_merge(
                ['id' => $model->getScoutKey()],
                $searchableData
            );
        })->filter()->values()->all();

        $table = $models->first()->searchableAs().'_search_index';

        foreach ($objects as $object) {
            DB::table($table)
                ->updateOrInsert(
                    Arr::only($object, 'id'),
                    Arr::except($object, 'id')
                );
        }
    }

    public function delete($models)
    {
        $table = $models->first()->searchableAs().'_search_index';

        $ids = $models->map(function ($model) {
            return $model->getScoutKey();
        })->values()->all();

        DB::table($table)
            ->whereIn('id', $ids)
            ->delete();
    }

    public function search(Builder $builder)
    {
        $index = $builder->index ?: $builder->model->searchableAs();
        $table = $index.'_search_index';

        $fullText = $builder->model->fullTextColumns();

        $query = DB::table("$table as a")
            ->where(function ($query) use ($fullText, $builder) {
                foreach ($fullText as $column => $weight) {
                    $query->orWhereRaw('MATCH('.$column.') AGAINST (? IN BOOLEAN MODE)', [$builder->query]);
                }
            });

        foreach ($builder->wheres as $field => $value) {
            $query->where($field, $value);
        }

        if ($builder->callback) {
            $query = call_user_func($builder->callback, $query, $this);
        }

        $countQuery = clone $query;

        // Construct an SQL phrase to determine the 'score' of a row. This is
        // calculated by adding up the full-text ranking of each column
        // multiplied by its weight.
        $score = [
            'sql' => collect($fullText)
                ->map(function ($weight, $column) {
                    return 'MATCH('.$column.') AGAINST (? IN BOOLEAN MODE) * '.$weight;
                })
                ->implode(' + '),
            'bindings' => array_fill(0, count($fullText), $builder->query)
        ];

        if (isset($builder->group)) {
            $group = $builder->group;

            $query->joinSub(function ($query) use ($score, $table, $group, $fullText, $builder) {
                $query->selectRaw("id, row_number() over (partition by $group order by $score[sql]) as r", $score['bindings'])
                    ->from($table)
                    ->where(function ($query) use ($fullText, $builder) {
                        foreach ($fullText as $column => $weight) {
                            $query->orWhereRaw('MATCH('.$column.') AGAINST (? IN BOOLEAN MODE)', [$builder->query]);
                        }
                    });
            }, 'b', 'a.id', '=', 'b.id');

            $query->where('r', 1);

            $countQuery->groupBy($group);
        }

        // $results['nbHits'] = $countQuery->count();

        if (count($builder->orders)) {
            foreach ($builder->orders as $order) {
                $query->orderBy($order['column'], $order['direction']);
            }
        } else {
            $query->orderByRaw("$score[sql] desc", $score['bindings']);
        }

        if ($builder->limit) {
            $query = $query->take($builder->limit);
        }

        $results['hits'] = $query->pluck('a.id')->all();

        return $results;
    }

    public function paginate(Builder $builder, $perPage, $page)
    {
        // not implemented
    }

    public function mapIds($results)
    {
        return $results['hits'];
    }

    public function map(Builder $builder, $results, $model)
    {
        if (count($results['hits']) === 0) {
            return $model->newCollection();
        }

        $objectIds = $results['hits'];
        $objectIdPositions = array_flip($objectIds);

        return $model->getScoutModelsByIds(
            $builder, $objectIds
        )->filter(function ($model) use ($objectIds) {
            return in_array($model->getScoutKey(), $objectIds);
        })->sortBy(function ($model) use ($objectIdPositions) {
            return $objectIdPositions[$model->getScoutKey()];
        })->values();
    }

    public function getTotalCount($results)
    {
        return $results['nbHits'];
    }

    public function flush($model)
    {
        $table = $model->searchableAs().'_search_index';

        DB::table($table)->delete();
    }

    public function lazyMap(Builder $builder, $results, $model)
    {
        // TODO: Implement lazyMap() method.
    }

    public function createIndex($name, array $options = [])
    {
        // TODO: Implement createIndex() method.
    }

    public function deleteIndex($name)
    {
        // TODO: Implement deleteIndex() method.
    }
}
