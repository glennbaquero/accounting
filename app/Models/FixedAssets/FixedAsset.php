<?php

namespace App\Models\FixedAssets;

use App\Extenders\Models\BaseModel as Model;
use App\Models\AdminSetups\Client;
use App\Models\Users\User;
use App\Models\MainAccounts\MainAccount;
use App\Models\FixedAssetDepreciationLines\FixedAssetDepreciationLine;

class FixedAsset extends Model
{
    const STATUS_ACTIVE = 'Active';
    const STATUS_DISPOSED = 'Disposed';

    /**
     * @Relationship
     */

    public function depreciation_lines() {
        return $this->hasMany(FixedAssetDepreciationLine::class, 'asset_id', 'asset_id')->withTrashed();
    }

    public function main_account_selected() {
        return $this->belongsTo(MainAccount::class, 'main_account')->withTrashed();
    }

    public function accumulated_depreciation_account_selected() {
        return $this->belongsTo(MainAccount::class, 'accumulated_depreciation_account')->withTrashed();
    }

    public function depreciation_expense_account_selected() {
        return $this->belongsTo(MainAccount::class, 'depreciation_expense_account')->withTrashed();
    }

    public function gain_loss_account_selected() {
        return $this->belongsTo(MainAccount::class, 'gain_loss_account')->withTrashed();
    }

    public function client() {
        return $this->belongsTo(Client::class, 'client_id')->withTrashed();
    }

    public function created_by_user() {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function updated_by_user() {
        return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'client' => $this->client->name ?? null,
            'asset_id' => $this->asset_id,
            'asset_code' => $this->asset_code,
            'asset_name' => $this->asset_name,
            'description' => $this->description,
            'acquisition_date' => $this->acquisition_date,
            'acquisition_cost' => $this->acquisition_cost,
            'asset_status' => $this->asset_status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }

    /**
     * Sum of all posted depreciation lines (as of today's postings).
     *
     * @return float
     */
    public function getAccumulatedDepreciation() {
        return (float) $this->depreciation_lines()->where('posted_checkbox', true)->sum('depreciation_amount');
    }

    /**
     * Net book value = acquisition cost - accumulated (posted) depreciation.
     *
     * @return float
     */
    public function getNetBookValue() {
        return (float) $this->acquisition_cost - $this->getAccumulatedDepreciation();
    }

    /**
     * @Setters
     */
    public static function store($request, $item = null, $columns = ['asset_id', 'asset_code', 'asset_name', 'description', 'client_id', 'main_account', 'main_account_code', 'main_account_name', 'accumulated_depreciation_account', 'accumulated_depreciation_account_code', 'accumulated_depreciation_account_name', 'depreciation_expense_account', 'depreciation_expense_account_code', 'depreciation_expense_account_name', 'gain_loss_account', 'gain_loss_account_code', 'gain_loss_account_name', 'acquisition_date', 'acquisition_cost', 'salvage_value', 'useful_life_months', 'depreciation_method', 'created_by', 'updated_by'])
    {
        $vars = $request->only($columns);
        $vars['company_id'] = auth()->user()->company_id;

        if (!$item) {
            $vars['asset_status'] = static::STATUS_ACTIVE;
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }

        return $item;
    }

    /**
     * Renderers
     */

    public function renderShowUrl() {
        return route('fixed-assets.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('fixed-assets.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('fixed-assets.restore', $this->id);
    }
}
