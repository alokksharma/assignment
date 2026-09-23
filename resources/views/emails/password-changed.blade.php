<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Password Has Been Changed Successfully</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f5f7fb; font-family: Arial, sans-serif; color: #1f2937;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f7fb; padding: 30px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="650" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; overflow: hidden; max-width: 650px;">
                    <tr>
                        <td style="background-color: #2563eb; padding: 22px; text-align: center; color: #ffffff; font-size: 24px; font-weight: bold;">
                            {{ $company_name }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px 30px 20px;">
                            <h2 style="margin: 0 0 20px; font-size: 24px; color: #111827;">Your Password Has Been Changed Successfully</h2>

                            <p style="margin: 0 0 16px; line-height: 1.7; font-size: 16px;">
                                Dear {{ $user_name }},
                            </p>

                            <p style="margin: 0 0 24px; line-height: 1.7; font-size: 16px;">
                                This is to confirm that the password for your {{ $company_name }} account has been changed successfully.
                            </p>

                            <p style="margin: 0 0 8px; font-size: 16px; font-weight: bold; color: #111827;">Password Change Details</p>

                            <table role="presentation" cellpadding="8" cellspacing="0" style="margin-bottom: 24px; font-size: 15px; line-height: 1.6; border-collapse: collapse; width: 100%;">
                                <tr>
                                    <td style="width: 120px; font-weight: bold; color: #374151;">Date:</td>
                                    <td>{{ $change_date }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; color: #374151;">Time:</td>
                                    <td>{{ $change_time }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; color: #374151;">Time Zone:</td>
                                    <td>{{ $time_zone }}</td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 20px; line-height: 1.7; font-size: 16px;">
                                You can now use your new password to access your account.
                            </p>

                            <p style="margin: 0 0 12px; font-size: 18px; font-weight: bold; color: #111827;">Did You Not Make This Change?</p>

                            <p style="margin: 0 0 20px; line-height: 1.7; font-size: 16px;">
                                If you did not change your password, please take immediate action to secure your account.
                            </p>

                            <p style="margin: 0 0 24px; text-align: center;">
                                <a href="{{ $account_security_link }}" style="background-color: #2563eb; color: #ffffff; text-decoration: none; display: inline-block; padding: 12px 24px; border-radius: 6px; font-weight: bold;">
                                    Secure My Account
                                </a>
                            </p>

                            <p style="margin: 0 0 16px; line-height: 1.7; font-size: 16px;">
                                This will allow you to review your account security and take the necessary steps to protect your account.
                            </p>

                            <p style="margin: 0 0 16px; line-height: 1.7; font-size: 16px;">
                                You can also contact our support team immediately at
                                <a href="mailto:{{ $support_email }}" style="color: #2563eb;">{{ $support_email }}</a>.
                            </p>

                            <p style="margin: 0 0 16px; line-height: 1.7; font-size: 16px;">
                                For your security, please keep your password confidential and never share your login credentials with anyone.
                            </p>

                            <p style="margin: 24px 0 0; line-height: 1.7; font-size: 16px;">
                                Best regards,<br>
                                <strong>{{ $company_name }}</strong><br>
                                <span style="color: #4b5563;">Growing Through Insights</span><br>
                                <a href="{{ $company_website }}" style="color: #2563eb;">{{ $company_website }}</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
