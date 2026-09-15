<?php

namespace App\Jobs;

use App\Helpers\App\General\ImapMailHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncMailWithDatabaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mailId;

    public function __construct($id)
    {
        $this->mailId = $id;
    }

    public function handle()
    {
        $mailbox = new ImapMailHelper;
        $mailbox->parseAndStoreEmail($this->mailId);
        return custom_response('emails_has_been_synced_with_system');
    }
}
