<?php

namespace App\Models\FixedAssetDepreciationLines;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;
use App\Models\FixedAssets\FixedAsset;
use App\Models\FiscalPeriods\FiscalPeriod;
use App\Models\GeneralLedgers\GeneralLedgerLine;

class FixedAssetDepreciationLine extends Model
{
    /**
     * @Relationship
     */

    public function parent() {
        return $this->belongsTo(FixedAsset::class, 'asset_id', 'asset_id')->withTrashed();
    }

    public function fiscal_period_selected() {
        return $this->belongsTo(FiscalPeriod::class, 'fiscal_period_id', 'fiscal_period_id')->withTrashed();
    }

    public function general_ledger_line() {
        return $this->belongsTo(GeneralLedgerLine::class, 'general_ledger_line_id')->withTrashed();
    }

    public function posted_by_user() {
        return $this->belongsTo(User::class, 'posted_by')->withTrashed();
    }

    public function created_by_user() {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function updated_by_user() {
        return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }

    /**
     * @Setters
     */
    public static function store($request, $item = null, $columns = ['asset_id', 'period_number', 'period_date', 'fiscal_period_id', 'fiscal_period_code', 'depreciation_amount', 'accumulated_depreciation', 'book_value', 'client_id', 'company_id', 'created_by', 'updated_by'])
    {
        $vars = is_array($request) ? \Illuminate\Support\Arr::only($request, $columns) : $request->only($columns);

        if (!$item) {
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }

        return $item;
    }
}
