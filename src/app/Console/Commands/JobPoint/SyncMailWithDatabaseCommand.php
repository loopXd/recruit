<?php


namespace App\Console\Commands\JobPoint;


use App\Helpers\App\General\ImapMailHelper;
use App\Jobs\SyncMailWithDatabaseJob;
use App\Models\App\ImapMail\EmailInboxId;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncMailWithDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync mail with database using imap';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        ini_set('memory_limit', '256M');
        $mailbox = new ImapMailHelper;
        $ids = $mailbox->getIds();

        $emailInboxId = EmailInboxId::query()->first();

        $lastId = -1;
        $last_read_id = -1;
        if ($emailInboxId) {
            $lastId = $emailInboxId->last_read_id;
            $last_read_id = $emailInboxId->last_read_id;
        }

        foreach ($ids as $id) {
            if ($id > $lastId) {
                SyncMailWithDatabaseJob::dispatch($id)->onQueue('high');
            }
            if ($id > $last_read_id) {
                $last_read_id = $id;
            }
        }

        if ($emailInboxId) {
            $emailInboxId->update(['last_read_id' => $last_read_id]);
        } else {
            if ($last_read_id >= 0)
                EmailInboxId::query()->create(['last_read_id' => $last_read_id]);
        }
    }
}