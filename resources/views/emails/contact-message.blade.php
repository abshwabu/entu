<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Portfolio Message</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f4f5;
            color: #18181b;
            margin: 0;
            padding: 30px 15px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e4e4e7;
        }
        .header {
            background-color: #09090b;
            color: #ffffff;
            padding: 28px 32px;
        }
        .header h1 {
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 6px 0;
            color: #ffffff;
            letter-spacing: -0.02em;
        }
        .header p {
            font-size: 13px;
            color: #a1a1aa;
            margin: 0;
        }
        .content {
            padding: 32px;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .meta-table td {
            padding: 8px 0;
            font-size: 14px;
            border-bottom: 1px solid #f4f4f5;
        }
        .meta-label {
            font-weight: 600;
            color: #71717a;
            width: 90px;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.05em;
        }
        .meta-value {
            color: #18181b;
        }
        .message-box {
            background-color: #fafafa;
            border: 1px solid #e4e4e7;
            border-radius: 12px;
            padding: 20px;
            margin-top: 16px;
            font-size: 15px;
            line-height: 1.6;
            color: #27272a;
            white-space: pre-wrap;
        }
        .footer {
            background-color: #f4f4f5;
            padding: 20px 32px;
            text-align: center;
            font-size: 12px;
            color: #71717a;
            border-top: 1px solid #e4e4e7;
        }
        .reply-btn {
            display: inline-block;
            background-color: #d97706;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 13px;
            margin-top: 24px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Entu Creative — New Portfolio Message</h1>
            <p>Received through the website contact form</p>
        </div>
        <div class="content">
            <table class="meta-table">
                <tr>
                    <td class="meta-label">From:</td>
                    <td class="meta-value"><strong>{{ $contactMessage->name }}</strong> &lt;{{ $contactMessage->email }}&gt;</td>
                </tr>
                @if($contactMessage->subject)
                    <tr>
                        <td class="meta-label">Subject:</td>
                        <td class="meta-value">{{ $contactMessage->subject }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="meta-label">Date:</td>
                    <td class="meta-value">{{ $contactMessage->created_at?->format('F j, Y, g:i a') ?? now()->format('F j, Y, g:i a') }}</td>
                </tr>
            </table>

            <div style="font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: #71717a; margin-top: 20px;">
                Message Content:
            </div>
            <div class="message-box">{{ $contactMessage->body }}</div>

            <div style="text-align: center;">
                <a href="mailto:{{ $contactMessage->email }}?subject=Re:%20{{ rawurlencode($contactMessage->subject ?: 'Inquiry') }}" class="reply-btn">
                    Reply to {{ $contactMessage->name }} &rarr;
                </a>
            </div>
        </div>
        <div class="footer">
            Entu Creative Portfolio &bull; {{ config('app.url') }} &bull; Admin Panel: {{ url('/admin/messages') }}
        </div>
    </div>
</body>
</html>
