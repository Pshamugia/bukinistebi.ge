<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectLine;
    public $messageContent;
    public $encryptedEmail;
    public $unsubscribeLink; // ✅ Add this
    
    public function __construct($subjectLine, $messageContent, $encryptedEmail)
    {
        $this->subjectLine = $subjectLine;
        $this->messageContent = $messageContent;
        $this->encryptedEmail = $encryptedEmail;
        $this->unsubscribeLink = route('unsubscribe', ['email' => $encryptedEmail]); // ✅ build the URL
    }

    public function build()
    {
        return $this->from('info@bukinistebi.ge', 'Bukinistebi.ge')
                    ->subject($this->subjectLine)
                    ->view('emails.subscription')
                    ->with([
                        'messageContent' => $this->messageContent,
                        'encryptedEmail' => $this->encryptedEmail,
                        'unsubscribeLink' => $this->unsubscribeLink,
                    ])
                    ->withSwiftMessage(function ($message) {
                        $message->getHeaders()
                            ->addTextHeader('List-Unsubscribe', "<{$this->unsubscribeLink}>");
                    });
    }
    
}
 