<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f7fb;padding:30px 0;font-family:Arial,sans-serif;color:#1f2937;">
    <tr>
        <td align="center">
            <table role="presentation" width="650" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:10px;overflow:hidden;max-width:650px;">
                <tr>
                    <td style="background-color:#2563eb;padding:22px;text-align:center;color:#ffffff;font-size:24px;font-weight:bold;">
                        {{ $companyName }}
                    </td>
                </tr>
                <tr>
                    <td style="padding:30px 30px 20px;">
                        <h2 style="margin:0 0 20px;font-size:24px;color:#111827;">User Access Invitation</h2>

                        <p style="margin:0 0 16px;line-height:1.7;font-size:16px;">Dear {{ $userName }},</p>

                        <p style="margin:0 0 24px;line-height:1.7;font-size:16px;">
                            Welcome to the <strong>{{ $companyName }}</strong> platform.
                        </p>

                        <p style="margin:0 0 20px;line-height:1.7;font-size:16px;">
                            You have been assigned the <strong>{{ $userRole }}</strong> role and can access the system using the details below.
                        </p>

                        <p style="margin:0 0 8px;font-size:16px;font-weight:bold;color:#111827;">Account Details</p>

                        <table role="presentation" cellpadding="8" cellspacing="0" style="margin-bottom:24px;font-size:15px;line-height:1.6;border-collapse:collapse;width:100%;">
                            <tr><td style="width:200px;font-weight:bold;color:#2563eb;">Name:</td><td>{{ $userName }}</td></tr>
                            <tr><td style="font-weight:bold;color:#2563eb;">Email:</td><td>{{ $userEmail }}</td></tr>
                            <tr><td style="font-weight:bold;color:#2563eb;">Role:</td><td>{{ $userRole }}</td></tr>
                            <tr><td style="font-weight:bold;color:#2563eb;">Portal URL:</td><td>{{ $portalUrl }}</td></tr>
                        </table>

                        <p style="margin:0 0 20px;text-align:center;">
                            <a href="{{ $loginLink }}" style="background-color:#2563eb;color:#ffffff;text-decoration:none;display:inline-block;padding:12px 24px;border-radius:6px;font-weight:bold;">
                                Login to Portal
                            </a>
                        </p>

                        <p style="margin:0 0 16px;line-height:1.7;font-size:16px;">
                            Please use your registered email address to sign in. If you have not set your password yet, use the Forgot Password option on the login page.
                        </p>

                        <p style="margin:0 0 12px;font-size:16px;font-weight:bold;color:#111827;">Security Notice</p>

                        <p style="margin:0 0 16px;line-height:1.7;font-size:16px;">
                            Please keep your account credentials confidential and do not share them with unauthorized persons.
                        </p>

                        <p style="margin:0 0 16px;line-height:1.7;font-size:16px;">
                            If you did not expect this access, please contact support immediately at
                            <a href="mailto:{{ $supportEmail }}" style="color:#2563eb;">{{ $supportEmail }}</a>.
                        </p>

                        <p style="margin:24px 0 0;line-height:1.7;font-size:16px;">
                            Best regards,<br>
                            <strong>{{ $companyName }}</strong><br>
                            <span style="color:#4b5563;">Growing Through Insights</span><br>
                            <a href="{{ $companyWebsite }}" style="color:#2563eb;">{{ $companyWebsite }}</a>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
