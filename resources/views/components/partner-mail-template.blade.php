<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f7fb;padding:30px 0;font-family:Arial,sans-serif;color:#1f2937;">
    <tr>
        <td align="center">
            <table role="presentation" width="700" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:10px;overflow:hidden;max-width:700px;">
                <tr>
                    <td style="background-color:#2563eb;padding:22px;text-align:center;color:#ffffff;font-size:24px;font-weight:bold;">
                        {{ $companyName }}
                    </td>
                </tr>
                <tr>
                    <td style="padding:30px 30px 20px;">
                        <h2 style="margin:0 0 20px;font-size:24px;color:#111827;">Partner API Login &amp; Integration Details</h2>

                        <p style="margin:0 0 16px;line-height:1.7;font-size:16px;">Dear {{ $partnerName }},</p>

                        <p style="margin:0 0 20px;line-height:1.7;font-size:16px;">
                            Welcome to the <strong>{{ $companyName }} Partner API Network</strong>.
                        </p>

                        <p style="margin:0 0 20px;line-height:1.7;font-size:16px;">
                            We are pleased to confirm that your partner account has been successfully activated. Please find your API access details below.
                        </p>

                        <p style="margin:0 0 8px;font-size:16px;font-weight:bold;color:#111827;">Partner Account Details</p>

                        <table role="presentation" cellpadding="8" cellspacing="0" style="margin-bottom:24px;font-size:15px;line-height:1.6;border-collapse:collapse;width:100%;">
                            <tr><td style="width:220px;font-weight:bold;color:#2563eb;">Partner / Company Name:</td><td>{{ $partnerCompanyName }}</td></tr>
                            <tr><td style="font-weight:bold;color:#2563eb;">Partner ID:</td><td>{{ $partnerId }}</td></tr>
                            <tr><td style="font-weight:bold;color:#2563eb;">API Username:</td><td>{{ $apiUsername }}</td></tr>
                            <tr><td style="font-weight:bold;color:#2563eb;">Login Email:</td><td>{{ $loginEmail }}</td></tr>
                            <tr><td style="font-weight:bold;color:#2563eb;">Account Status:</td><td>{{ $accountStatus }}</td></tr>
                            <tr><td style="font-weight:bold;color:#2563eb;">Activation Date:</td><td>{{ $activationDate }}</td></tr>
                        </table>

                        <p style="margin:0 0 8px;font-size:16px;font-weight:bold;color:#111827;">API Access</p>

                        <p style="margin:0 0 8px;line-height:1.7;font-size:16px;"><strong>Partner Portal:</strong> {{ $partnerPortalUrl }}</p>
                        <p style="margin:0 0 12px;line-height:1.7;font-size:16px;"><strong>Login to Partner Portal:</strong> <a href="{{ $partnerLoginLink }}" style="color:#2563eb;">{{ $partnerLoginLink }}</a></p>
                        <p style="margin:0 0 8px;line-height:1.7;font-size:16px;"><strong>API Base URL:</strong> {{ $apiBaseUrl }}</p>
                        <p style="margin:0 0 20px;line-height:1.7;font-size:16px;"><strong>API Documentation:</strong> {{ $apiDocumentationUrl }}</p>

                        <p style="margin:0 0 8px;font-size:16px;font-weight:bold;color:#111827;">API Authentication</p>

                        <table role="presentation" cellpadding="8" cellspacing="0" style="margin-bottom:24px;font-size:15px;line-height:1.6;border-collapse:collapse;width:100%;">
                            <tr><td style="width:220px;font-weight:bold;color:#2563eb;">Authentication Method:</td><td>{{ $authenticationMethod }}</td></tr>
                            <tr><td style="font-weight:bold;color:#2563eb;">API Key / Client ID:</td><td>{{ $clientId }}</td></tr>
                            <tr><td style="font-weight:bold;color:#2563eb;">API Secret:</td><td>{{ $clientSecretOrSecureLink }}</td></tr>
                        </table>

                        <p style="margin:0 0 20px;line-height:1.7;font-size:16px;"><strong>Security Notice:</strong> For security reasons, API secrets should not be shared by email. If applicable, your secret will be available through the secure Partner Portal.</p>

                        <p style="margin:0 0 8px;font-size:16px;font-weight:bold;color:#111827;">Integration Information</p>

                        <p style="margin:0 0 16px;line-height:1.7;font-size:16px;">
                            Your API access can be used for:<br>
                            • Receiving available survey/sample opportunities<br>
                            • Sending respondent/sample traffic<br>
                            • Tracking survey status and conversions<br>
                            • Receiving completion/reconciliation updates<br>
                            • Managing partner/project-level integrations<br>
                            • Accessing relevant project and performance information
                        </p>

                        <table role="presentation" cellpadding="8" cellspacing="0" style="margin-bottom:24px;font-size:15px;line-height:1.6;border-collapse:collapse;width:100%;">
                            <tr><td style="width:220px;font-weight:bold;color:#2563eb;">API Environment:</td><td>{{ $productionOrSandbox }}</td></tr>
                            <tr><td style="font-weight:bold;color:#2563eb;">API Version:</td><td>{{ $apiVersion }}</td></tr>
                            <tr><td style="font-weight:bold;color:#2563eb;">Callback / Postback URL:</td><td>{{ $postbackUrl }}</td></tr>
                        </table>

                        <p style="margin:0 0 12px;font-size:18px;font-weight:bold;color:#111827;">Account Security</p>

                        <p style="margin:0 0 20px;line-height:1.7;font-size:16px;">
                            If you did not request or authorize this API account, please contact our technical support team immediately at
                            <a href="mailto:{{ $supportEmail }}" style="color:#2563eb;">{{ $supportEmail }}</a>.
                        </p>

                        <p style="margin:0 0 24px;text-align:center;">
                            <a href="{{ $accountSecurityLink }}" style="background-color:#2563eb;color:#ffffff;text-decoration:none;display:inline-block;padding:12px 24px;border-radius:6px;font-weight:bold;">
                                Review Account Security
                            </a>
                        </p>

                        <p style="margin:0 0 16px;line-height:1.7;font-size:16px;">
                            Please keep your API credentials confidential. Do not publish API keys, client secrets, or authentication tokens in source code, public repositories, browser-side applications, or unsecured communication channels.
                        </p>

                        <p style="margin:0 0 16px;line-height:1.7;font-size:16px;">
                            For API integration assistance, please contact <strong>{{ $technicalSupportEmail }}</strong>.
                        </p>

                        <p style="margin:24px 0 0;line-height:1.7;font-size:16px;">
                            Best regards,<br>
                            <strong>{{ $companyName }}</strong><br>
                            <span style="color:#4b5563;">Growing Through Insights</span><br>
                            <a href="{{ $companyWebsite }}" style="color:#2563eb;">{{ $companyWebsite }}</a><br>
                            <a href="mailto:{{ $supportEmail }}" style="color:#2563eb;">{{ $supportEmail }}</a><br>
                            <a href="mailto:{{ $technicalSupportEmail }}" style="color:#2563eb;">{{ $technicalSupportEmail }}</a>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
