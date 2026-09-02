<?php

namespace App\Models\RecurringJournalTemplateLines;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;
use App\Models\RecurringJournalTemplates\RecurringJournalTemplate;
use App\Models\MainAccounts\MainAccount;

class RecurringJournalTemplateLine extends Model
{
    /**
     * @Relationship
     */

    public function parent() {
        return $this->belongsTo(RecurringJournalTemplate::class, 'template_id', 'template_id')->withTrashed();
    }

    public function main_account_selected() {
        return $this->belongsTo(MainAccount::class, 'main_account')->withTrashed();
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
    public static function store($request, $item = null, $columns = ['template_id', 'main_account', 'main_account_code', 'main_account_name', 'description', 'debit_amount', 'credit_amount', 'client_id', 'created_by', 'updated_by'])
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
