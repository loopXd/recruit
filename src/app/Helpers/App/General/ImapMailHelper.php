<?php

namespace App\Helpers\App\General;


use App\Models\App\JobPost\ApplicationEmail;
use App\Repositories\Core\Setting\SettingRepository;
use Exception;
use Illuminate\Support\Facades\Storage;
use PhpImap\Exceptions\ConnectionException;


class ImapMailHelper
{
    protected $mailbox;
    protected $mail_ids;

    public function __construct()
    {
        $appImapSettings = $this->getImapSettings();
        try {
            $this->mailbox = new \PhpImap\Mailbox(
                '{'. $appImapSettings['imap_server'] . ':' . $appImapSettings['imap_port'] . '/imap/ssl/novalidate-cert}INBOX', // IMAP server and mailbox folder
                $appImapSettings['imap_user'], // Username for the before configured mailbox
                $appImapSettings['imap_password'] // Password for the before configured username
            );
            $this->mail_ids = $this->mailbox->searchMailbox('ALL');
        } catch (ConnectionException $e) {
            exit('IMAP connection failed: ' . $e->getMessage());
        } catch (Exception $e) {
            exit('An error occurred: ' . $e->getMessage());
        }
    }

    public function getImapSettings()
    {
        return resolve(SettingRepository::class)->getDeliverySettingLists('imap_config');
    }

    public function getIds()
    {
        return $this->mail_ids;
    }

    public function parseAndStoreEmail($mail_id)
    {
        $this->storeEmail($mail_id);
        return true;
    }

    public function getMailBox($mail_id)
    {
        return $this->mailbox->getMail(
            $mail_id, // ID of the email, you want to get
            false // Do NOT mark emails as seen (optional)
        );
    }

    public function storeEmail($mail_id)
    {
        $email = $this->getMailBox($mail_id);

        $formattedInReplyTo = $this->getFormattedInReplyTo($email);
        $isDuplicate = $this->checkApplicationEmailDuplicate($this->formattedId((string)$email->messageId));
        $applicationEmail = $this->getApplicationEmail($formattedInReplyTo);

        echo "Condition----- > " . !$isDuplicate && $applicationEmail;
        if (!$isDuplicate && $applicationEmail) {

            $insertable = [
                'applicant_id' => $applicationEmail->applicant_id,
                'job_post_id' => $applicationEmail->job_post_id,
                'user_id' => $applicationEmail->user_id,
                'sender' => 'applicant',
                'message_id' => $this->formattedId((string)$email->messageId),
                'reference_id' => $formattedInReplyTo,
                'text_html' => $email->textHtml,
            ];

            echo "mail id ----- > " . $this->formattedId((string)$email->messageId);
            $applicationEmail = ApplicationEmail::query()->create($insertable);
            $paths = $this->storeSyncEmailAttachments($email);
            foreach ($paths as $path) {
                $applicationEmail->attachments()->create([
                    'type' => 'job_post_conversation',
                    'path' => $path
                ]);
            }
        }
    }

    public function storeSyncEmailAttachments($email): array
    {
        $paths = [];
        if (!empty($email->getAttachments())) {
            echo \count($email->getAttachments()) . " attachements\n";
        }
        // Save attachments one by one
        if (!$this->mailbox->getAttachmentsIgnore()) {
            $attachments = $email->getAttachments();

            foreach ($attachments as $attachment) {
                echo '--> Saving ' . (string)$attachment->name . '...';

                // Set individually filePath for each single attachment
                $file = \time() . '_' . $attachment->name;
                $filePath = storage_path('app/public/job_post_conversation_attachment/') . $file;
                $attachment->setFilePath($filePath);
                $paths[] = Storage::url('job_post_conversation_attachment/' . $file);

                if ($attachment->saveToDisk()) {
                    echo "OK, saved!\n";
                } else {
                    echo "ERROR, could not save!\n";
                }
            }
        }
        return $paths;
    }

    public function checkApplicationEmailDuplicate($messageId)
    {
        return ApplicationEmail::query()
            ->where('message_id', $messageId)
            ->first();
    }

    public function getFormattedInReplyTo($email)
    {
        $formattedInReplyTo = '';

        if (isset($email->headers->references)) {
            echo "references " . $email->headers->references . "\n";
            $references = explode(' ', $email->headers->references);
            $in_reply_to = last($references);
            echo "in_reply_to " . $in_reply_to . "\n";
            $formattedInReplyTo = $this->formattedId($in_reply_to);
        }
        return $formattedInReplyTo;
    }

    public function getApplicationEmail($formattedInReplyTo)
    {
        return ApplicationEmail::query()
            ->with('applicant')
            ->where('message_id', $formattedInReplyTo)
            ->first();
    }

    public function formattedId($id)
    {
        $formattedId = str_replace('<', '', $id);
        return str_replace('>', '', $formattedId);
    }
}
