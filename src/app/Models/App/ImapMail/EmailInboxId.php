<?php


namespace App\Models\App\ImapMail;


use App\Models\App\AppModel;

class EmailInboxId extends AppModel
{
    protected $fillable = [
        'last_read_id',
    ];
}