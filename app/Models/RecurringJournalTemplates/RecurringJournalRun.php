<?php

namespace App\Models\RecurringJournalTemplates;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;
use App\Models\Journals\GeneralJournal;

class RecurringJournalRun extends Model
{
    /**
     * @Relationship
     */

    public function parent() {
        return $this->belongsTo(RecurringJournalTemplate::class, 'template_id', 'template_id')->withTrashed();
    }

    public function general_journal() {
        return $this->belongsTo(GeneralJournal::class, 'general_journal_number', 'general_journal_number')->withTrashed();
    }

    public function created_by_user() {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }
}
