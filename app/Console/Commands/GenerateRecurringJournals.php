<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RecurringJournalTemplates\RecurringJournalTemplate;
use Carbon\Carbon;

class GenerateRecurringJournals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'journals:generate-recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate General Journal entries for every active recurring journal template that is due to run';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $templates = RecurringJournalTemplate::where('status', RecurringJournalTemplate::STATUS_ACTIVE)
            ->whereDate('next_run_date', '<=', now())
            ->get();

        if (!$templates->count()) {
            $this->info('No recurring journal templates are due.');
            return 0;
        }

        foreach ($templates as $template) {
            try {
                if (!$template->template_lines()->count()) {
                    $this->warn("Skipped {$template->template_id} ({$template->template_name}): no lines defined.");
                    continue;
                }

                $journal = $template->generateJournalEntry();
                $this->info("Generated {$journal->general_journal_number} from {$template->template_id} ({$template->template_name}).");
            } catch (\Throwable $e) {
                $this->error("Failed to generate journal for {$template->template_id} ({$template->template_name}): {$e->getMessage()}");
            }
        }

        return 0;
    }
}
