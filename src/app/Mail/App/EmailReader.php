<?php


namespace App\Mail\App;


use App\Exceptions\GeneralException;
use Exception;

class EmailReader
{
    public $conection;
    private $inbox;
    private $msg_count;
    private $server;
    private $user;
    private $password;
    private $port;

    public function __construct()
    {
        $this->loadConfigValues();
        $this->connect();
        $this->inbox();
    }

    public function getInbox()
    {
        return $this->inbox;
    }

    public function connect()
    {
        try {
            $this->conection = imap_open($this->server, $this->user, $this->password);
        } catch (Exception $e) {
            throw new GeneralException('Connection could not be established.');
        }
    }

    public function close()
    {
        $this->inbox = [];
        $this->msg_count = 0;
        imap_close($this->conection);
    }

    public function loadConfigValues()
    {
        $emailServerConfig = config('imap.email_server');

        try {
            $this->server = $emailServerConfig['server'];
            $this->user = $emailServerConfig['user'];
            $this->password = $emailServerConfig['password'];
            $this->port = $emailServerConfig['port'];

            throw_if(($this->user === null || $this->password === null || $this->port === null || $this->server === null),
                new GeneralException(trans('default.invalid_imap_configuration')));

        } catch (Exception $e) {

            throw new GeneralException(trans('default.invalid_imap_configuration'));
        }
    }

    public function inbox()
    {
        $this->msg_count = imap_num_msg($this->conection);

      //  $mail_con = imap_open($this->server, $this->user, $this->password);
       // $mailboxes = imap_list($mail_con, $this->server, '*');

        $tempInbox = [];
        for ($i = 1; $i <= $this->msg_count; $i++) {

            $tempInbox[] = [
                'index' => $i,
                'header' => imap_headerinfo($this->conection, $i),
                'body_text' => imap_fetchbody($this->conection, $i, 2),
                'body_structure' => imap_fetchstructure($this->conection, $i),
            ];
        }
        $this->inbox = $tempInbox;
    }

    public function getInboxCount()
    {
        return $this->msg_count;
    }

    public function getMessage($msg_index = null)
    {
        if (count($this->inbox) <= 0) {
            return [];
        } elseif (!is_null($msg_index) && isset($this->inbox[$msg_index])) {
            return $this->inbox[$msg_index];
        }
        return $this->inbox[0];

    }

}