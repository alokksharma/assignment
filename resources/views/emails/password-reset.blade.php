<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
</head>

<body style="margin:0; padding:0; background-color:#f5f7fb; font-family:Arial, Helvetica, sans-serif; color:#1f2937;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0"
       style="background-color:#f5f7fb; padding:30px 0;">
    <tr>
        <td align="center">

            <table role="presentation" width="600" cellpadding="0" cellspacing="0"
                   style="width:100%; max-width:600px; background-color:#ffffff; border-radius:8px; overflow:hidden;">

                <!-- Header -->
                <tr>
                    <td style="background-color:#2563eb; padding:20px; text-align:center; color:#ffffff; font-size:24px; font-weight:bold;">
                        {{ $appName }}
                    </td>
                </tr>

                <!-- Content -->
                <tr>
                    <td style="padding:30px;">

                        <h2 style="margin:0 0 20px; font-size:24px; color:#111827;">
                            Reset Your Password
                        </h2>

                        <p style="margin:0 0 16px; line-height:1.6; font-size:16px;">
                            Dear {{ $user->name ?? 'User' }},
                        </p>

                        <p style="margin:0 0 16px; line-height:1.6; font-size:16px;">
                            We received a request to reset the password for your
                            {{ $appName }} account.
                        </p>

                        <!-- Reset Button -->
                        <p style="margin:0 0 24px; text-align:center;">
                            <a href="{{ $resetUrl }}"
                               style="background-color:#2563eb;
                                      color:#ffffff;
                                      text-decoration:none;
                                      display:inline-block;
                                      padding:12px 24px;
                                      border-radius:6px;
                                      font-weight:bold;">
                                Reset Password
                            </a>
                        </p>

                        <p style="margin:0 0 16px; line-height:1.6; font-size:16px;">
                            For security reasons, this password reset link will expire in
                            <strong>{{ $expiry_time }}</strong>.
                        </p>

                        <p style="margin:0 0 16px; line-height:1.6; font-size:16px;">
                            If you did not request a password reset, please ignore this
                            email. Your account will remain secure.
                        </p>

                        <p style="margin:0 0 16px; line-height:1.6; font-size:16px;">
                            If you experience any issues, please contact our support team at
                            <a href="mailto:{{ $support_email }}"
                               style="color:#2563eb; text-decoration:none;">
                                {{ $support_email }}
                            </a>.
                        </p>

                        <p style="margin:24px 0 0; line-height:1.6; font-size:16px;">
                            Best regards,<br>
                            <strong>{{ $appName }}</strong>
                        </p>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="padding:20px 30px; background-color:#f9fafb; text-align:center; color:#6b7280; font-size:12px;">
                        © {{ date('Y') }} {{ $appName }}. All rights reserved.
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
