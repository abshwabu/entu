<?php

namespace App\Jobs;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendTelegramNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Message $message) {}

    public function handle(): void
    {
        if (!config('services.telegram.enabled')) {
            return;
        }

        $botToken = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        if (!$botToken || !$chatId) {
            return;
        }

        $subject = $this->message->subject ?: 'General Portfolio Inquiry';
        $text = "📩 *New Contact Message — Entu Creative*\n\n"
              . "👤 *From:* " . $this->escapeMarkdown($this->message->name) . "\n"
              . "✉️ *Email:* " . $this->escapeMarkdown($this->message->email) . "\n"
              . "📌 *Subject:* " . $this->escapeMarkdown($subject) . "\n\n"
              . "💬 *Message:*\n" . $this->escapeMarkdown($this->message->body);

        try {
            Http::timeout(5)->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Throwable $e) {
            Log::warning('Telegram message notification failed: ' . $e->getMessage());
        }
    }

    private function escapeMarkdown(string $text): string
    {
        return str_replace(['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'], '\\$0', $text);
    }
}
