<?php

namespace App\Eloquent\Relationships;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class LeaveableMorphToMany extends MorphToMany
{
    public function __construct(
        Builder $query,
        Model $parent,
        $name,
        $table,
        $foreignPivotKey,
        $relatedPivotKey,
        $parentKey,
        $relatedKey,
        $relationName = null,
        $inverse = false
    ) {
        parent::__construct(
            $query,
            $parent,
            $table,
            $foreignPivotKey,
            $relatedPivotKey,
            $parentKey,
            $relatedKey,
            $relationName
        );

        $query->wherePivot('left_at', null);
    }

    public function detach($ids = null, $touch = true)
    {
        $query = $this->newPivotQuery();
        // If associated IDs were passed to the method we will only delete those
        // associations, otherwise all of the association ties will be broken.
        // We'll return the numbers of affected rows when we do the deletes.
        if (! is_null($ids)) {
            $ids = $this->parseIds($ids);
            if (empty($ids)) {
                return 0;
            }
            $query->whereIn($this->relatedPivotKey, (array) $ids);
        }

        $results = $query->update(['left_at' => now()]);
        if ($touch) {
            $this->touchIfTouching();
        }
        return $results;
    }

    protected function baseAttachRecord($id, $timed)
    {
        $record[$this->relatedPivotKey] = $id;
        $record[$this->foreignPivotKey] = $this->parent->{$this->parentKey};
        // If the record needs to have creation and update timestamps, we will make
        // them by calling the parent model's "freshTimestamp" method which will
        // provide us with a fresh timestamp in this model's preferred format.
        if ($timed) {
            $record = $this->addTimestampsToAttachment($record);
        }
        foreach ($this->pivotValues as $value) {
            $record[$value['column']] = $value['value'];
        }
        $record['joined_at'] = now();
        return $record;
    }
}
