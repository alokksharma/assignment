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
                        <h2 style="margin:0 0 20px;font-size:24px;color:#111827;">Project Access Details</h2>

                        <p style="margin:0 0 16px;line-height:1.7;font-size:16px;">Dear {{ $clientName }},</p>

                        <p style="margin:0 0 20px;line-height:1.7;font-size:16px;">
                            Welcome to the <strong>{{ $companyName }} Project API Network</strong>.
                        </p>

                        <p style="margin:0 0 20px;line-height:1.7;font-size:16px;">
                            We are pleased to confirm that your project access has been successfully configured. Please find the project details below.
                        </p>

                        <p style="margin:0 0 8px;font-size:16px;font-weight:bold;color:#111827;">Project Details</p>

                        <table role="presentation" cellpadding="8" cellspacing="0" style="margin-bottom:24px;font-size:15px;line-height:1.6;border-collapse:collapse;width:100%;">
                            <tr><td style="width:220px;font-weight:bold;color:#2563eb;">Project Name:</td><td>{{ $projectName }}</td></tr>
                            <tr><td style="font-weight:bold;color:#2563eb;">Client Name:</td><td>{{ $clientName }}</td></tr>
                            <tr><td style="font-weight:bold;color:#2563eb;">Project Code:</td><td>{{ $projectCode }}</td></tr>
                            <tr><td style="font-weight:bold;color:#2563eb;">Project URL:</td><td>{{ $projectUrl }}</td></tr>
                            <tr><td style="font-weight:bold;color:#2563eb;">Supplier ID:</td><td>{{ $supplierId }}</td></tr>
                            <tr><td style="font-weight:bold;color:#2563eb;">Status:</td><td>{{ $status }}</td></tr>
                        </table>

                        <p style="margin:0 0 12px;line-height:1.7;font-size:16px;">
                            <strong>Supplier Token:</strong> {{ $supplierToken }}
                        </p>

                        <p style="margin:0 0 16px;line-height:1.7;font-size:16px;">
                            For security reasons, please keep this project credential information confidential and do not share it with unauthorized persons.
                        </p>

                        <p style="margin:0 0 16px;line-height:1.7;font-size:16px;">
                            If you did not request or authorize this project setup, please contact our support team immediately at
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
