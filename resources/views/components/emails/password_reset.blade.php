<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RAN Lending Password Reset OTP</title>
</head>
<body style="margin:0;padding:0;background:#f3f6fb;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
  <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:#f3f6fb;padding:24px 12px;">
    <tr>
      <td align="center">
        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width:620px;background:#ffffff;border:1px solid #e3ebf7;border-radius:14px;overflow:hidden;">
          <tr>
            <td style="padding:0;">
              <div style="background:linear-gradient(135deg,#0056b3 0%,#1e4fa8 60%,#ff8c00 100%);padding:22px 26px;text-align:center;">
                <div style="font-size:28px;line-height:1;color:#ffffff;font-weight:700;letter-spacing:0.4px;">RAN Lending</div>
                <div style="margin-top:10px;font-size:13px;color:#dbeafe;">Account Security Verification</div>
              </div>
            </td>
          </tr>

          <tr>
            <td style="padding:28px 26px 10px 26px;">
              <h1 style="margin:0 0 12px 0;font-size:22px;line-height:1.3;color:#0f172a;">Reset Your Password</h1>
              <p style="margin:0 0 14px 0;font-size:15px;line-height:1.65;color:#334155;">
                We received a request to reset your password. Use this OTP to continue.
              </p>

              <div style="margin:20px 0;padding:18px 14px;border:1px dashed #9ec5ff;border-radius:12px;background:#f8fbff;text-align:center;">
                <div style="font-size:12px;color:#64748b;letter-spacing:0.6px;text-transform:uppercase;margin-bottom:8px;">Your OTP Code</div>
                <div style="font-size:42px;line-height:1;font-weight:800;letter-spacing:8px;color:#0056b3;">{{ $otp }}</div>
              </div>

              <div style="padding:12px 14px;background:#fff8eb;border:1px solid #ffe0ad;border-radius:10px;font-size:14px;line-height:1.6;color:#7a4a00;">
                This code is valid for <strong>{{ $expiryMinutes ?? 3 }} minutes</strong> and can only be used once.
              </div>
            </td>
          </tr>

          <tr>
            <td style="padding:8px 26px 18px 26px;">
              <p style="margin:0 0 10px 0;font-size:14px;line-height:1.7;color:#475569;">
                If you did not request a password reset, please ignore this email.
              </p>
              <p style="margin:0;font-size:14px;line-height:1.7;color:#475569;">
                Thanks,<br><strong style="color:#0f172a;">RAN Lending Team</strong>
              </p>
            </td>
          </tr>

          <tr>
            <td style="padding:16px 26px;background:#f8fafc;border-top:1px solid #e5edf8;text-align:center;">
              <p style="margin:0;font-size:12px;line-height:1.7;color:#64748b;">
                &copy; {{ date('Y') }} RAN Lending. All rights reserved.
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
