<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New contact message</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(15,23,42,0.08);">
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#0f766e,#115e59);padding:28px 32px;">
                            <h1 style="margin:0;font-size:20px;font-weight:700;color:#ffffff;">New Contact Form Submission</h1>
                            <p style="margin:6px 0 0;font-size:13px;color:#99f6e4;">Someone has contacted you via the MediCare website.</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:32px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                <tr>
                                    <td style="padding:10px 0;font-size:12px;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;color:#94a3b8;width:120px;">Name</td>
                                    <td style="padding:10px 0;font-size:15px;font-weight:600;color:#0f172a;">{{ $data['name'] }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;font-size:12px;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;color:#94a3b8;">Email</td>
                                    <td style="padding:10px 0;font-size:15px;color:#0f172a;">
                                        <a href="mailto:{{ $data['email'] }}" style="color:#0d9488;text-decoration:none;">{{ $data['email'] }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;font-size:12px;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;color:#94a3b8;">Subject</td>
                                    <td style="padding:10px 0;font-size:15px;font-weight:600;color:#0f172a;">{{ $data['subject'] }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0 6px;font-size:12px;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;color:#94a3b8;">Message</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding:0 0 24px;">
                                        <div style="margin-top:8px;padding:16px 18px;background:#f8fafc;border:1px solid #e2e8f0;border-left:4px solid #0d9488;border-radius:10px;font-size:14px;line-height:1.7;color:#334155;">{{ $data['message'] }}</div>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                <tr>
                                    <td style="background:#f0fdfa;border:1px solid #ccfbf1;border-radius:10px;padding:12px 16px;font-size:13px;color:#0f766e;">
                                        <strong>Submitted:</strong> {{ now()->format('D, d M Y') }} at {{ now()->format('h:i A') }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:20px 32px;border-top:1px solid #f1f5f9;font-size:12px;color:#94a3b8;">
                            This notification was sent automatically from the MediCare website. Reply to this email to contact the sender directly.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
