<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="x-apple-disable-message-reformatting">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $appName ?? config('app.name') }}</title>
</head>
<body style="margin:0;padding:0;background:#f6f6f8;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f6f6f8;">
    <tr>
      <td align="center" style="padding:24px;">
        <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width:600px;background:#ffffff;border-radius:8px;overflow:hidden;border:1px solid #eaeaea;">
          <tr>
            <td style="padding:32px 32px 8px 32px;font-family:Arial,Helvetica,sans-serif;">
              <h1 style="margin:0 0 12px 0;font-size:22px;line-height:1.4;color:#111827;">
                {{ $titleH1 ?? 'Chào' }} @if(!empty($greetingName ?? '')) {{ $greetingName }} @endif
              </h1>
              <div style="margin:0 0 16px 0;font-size:15px;line-height:1.7;color:#374151;">
                {!! $slot !!}
              </div>
            </td>
          </tr>
          @isset($buttonText)
          <tr>
            <td align="center" style="padding:8px 32px 24px 32px;">
              <a href="{{ $buttonUrl ?? config('app.url') }}" target="_blank"
                 style="display:inline-block;padding:12px 20px;text-decoration:none;
                        border-radius:6px;background:#2563eb;color:#ffffff;
                        font-family:Arial,Helvetica,sans-serif;font-size:14px;">
                {{ $buttonText }}
              </a>
            </td>
          </tr>
          @endisset
          <tr>
            <td style="padding:0 32px 28px 32px;font-family:Arial,Helvetica,sans-serif;">
              <p style="margin:0;font-size:14px;line-height:1.7;color:#374151;">
                Cảm ơn,<br>{{ $appName ?? config('app.name') }}
              </p>
            </td>
          </tr>
        </table>
        <div style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#6b7280;margin-top:12px;">
          Email này được gửi tự động, vui lòng không trả lời.
        </div>
      </td>
    </tr>
  </table>
</body>
</html>
