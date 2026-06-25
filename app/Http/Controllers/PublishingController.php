<?php

namespace App\Http\Controllers;

use App\Models\Publishing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class PublishingController extends Controller
{
    public function landing()
    {
        $items = Schema::hasTable('publishing')
            ? Publishing::latest()->get()
            : collect();

        return view('publishing.landing', compact('items'));
    }

    public function show($id)
    {
        $item = Publishing::findOrFail($id);

        return view('publishing.show', compact('item'));
    }

    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255'],
            'message'    => ['required', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ], [], [
            'name' => 'სახელი',
            'email' => 'ელფოსტა',
            'message' => 'შეტყობინება',
            'attachment' => 'ფაილი',
        ]);

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.transport' => 'smtp',
            'mail.mailers.smtp.host' => env('PUBLISHING_MAIL_HOST'),
            'mail.mailers.smtp.port' => env('PUBLISHING_MAIL_PORT'),
            'mail.mailers.smtp.encryption' => env('PUBLISHING_MAIL_ENCRYPTION'),
            'mail.mailers.smtp.username' => env('PUBLISHING_MAIL_USERNAME'),
            'mail.mailers.smtp.password' => env('PUBLISHING_MAIL_PASSWORD'),
            'mail.from.address' => env('PUBLISHING_MAIL_FROM_ADDRESS'),
            'mail.from.name' => env('PUBLISHING_MAIL_FROM_NAME'),
        ]);

$file = $request->hasFile('attachment') ? $request->file('attachment') : null;

        Mail::send([], [], function ($mail) use ($validated, $file) {
            $mail->to('publishing@bukinistebi.ge')
                ->from(env('PUBLISHING_MAIL_FROM_ADDRESS'), env('PUBLISHING_MAIL_FROM_NAME'))
                ->replyTo($validated['email'], $validated['name'])
                ->subject('[PUBLISHING] ' . $validated['name'])
                ->html(
                    '<h2>გამომცემლობა ბუკინისტები</h2>
                    <p><strong>სახელი:</strong> ' . e($validated['name']) . '</p>
                    <p><strong>ელფოსტა:</strong> ' . e($validated['email']) . '</p>
                    <p><strong>შეტყობინება:</strong><br>' . nl2br(e($validated['message'])) . '</p>'
                );
                if ($file) {
        $mail->attach($file->getRealPath(), [
            'as' => $file->getClientOriginalName(),
            'mime' => $file->getMimeType(),
        ]);
    }
});

        return back()
            ->with('publishing_success', 'თქვენი შეტყობინება წარმატებით გაიგზავნა.')
            ->withFragment('about-publishing');
    }
}
