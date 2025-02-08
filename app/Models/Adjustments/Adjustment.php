<?php

namespace App\Models\Adjustments;

use App\Extenders\Models\BaseModel;
use App\Models\Users\User;

class Adjustment extends Model
{
    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'adjustment_number' => $this->adjustment_number,
            'adjustment_date' => $this->adjustment_date,
            'adjustment_by' => $this->adjustment_by,
            'adjustment_checkbox' => $this->adjustment_checkbox,
            'status' => $this->status,
            'type' => $this->type,
            'sub_type' => $this->sub_type,
            'other_adjustment' => $this->other_adjustment,
            'issue_date' => $this->issue_date,
            'status' => $this->status,
            'amount' => $this->amount,
        ];
    }

    /**
     * Relationships
     */
    public function adjustment_by_user() {
        return $this->belongsTo(User::class, 'adjustment_by', 'id')->withTrashed();
    }

    /**
     * @Setters
     */
    public static function store($request, $item = null, $columns = [
        'adjustment_number',
        'adjustment_date',
        'adjustment_by',
        'adjustment_checkbox',
        'status',
        'type',
        'sub_type',
        'other_adjustment',
        'issue_date',
        'status',
        'amount',
    ])
    {
        
        $vars = $request->only($columns);

        if (!$item) {
            $item = static::create($vars);
            $item->update([
                'created_by' => $request->user()->id,
                'updated_by' => $request->user()->id,
            ]);
        } else {
            $item->update($vars);
            $item->update([
                'updated_by' => $request->user()->id,
            ]);
        }

        return $item;
    }

    /**
     * Renders
     */
    public function renderShowUrl() {
        return route('adjustments.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('adjustments.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('adjustments.restore', $this->id);
    }
}
