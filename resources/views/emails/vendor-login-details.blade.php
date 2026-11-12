    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Vendor Portal Login Details</title>
    </head>
    <body style="margin: 0; padding: 0; background-color: #f5f7fb; font-family: Arial, sans-serif; color: #1f2937;">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f7fb; padding: 30px 0;">
            <tr>
                <td align="center">
                    <table role="presentation" width="650" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; overflow: hidden; max-width: 650px;">
                        <tr>
                            <td style="background-color: #0f172a; padding: 22px; text-align: center; color: #ffffff; font-size: 24px; font-weight: bold;">
                                {{ $company_name }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 30px 30px 20px;">
                                <h2 style="margin: 0 0 20px; font-size: 24px; color: #2563eb;">Vendor Portal Login Details</h2>

                                <p style="margin: 0 0 16px; line-height: 1.7; font-size: 16px;">
                                    Dear {{ $vendor_name }},
                                </p>

                                <p style="margin: 0 0 24px; line-height: 1.7; font-size: 16px;">
                                    Welcome to the <strong>{{ $company_name }} Vendor Portal</strong>.
                                </p>

                                <p style="margin: 0 0 20px; line-height: 1.7; font-size: 16px;">
                                    Your vendor account has been successfully created. Please find your login details below:
                                </p>

                                <p style="margin: 0 0 8px; font-size: 16px; font-weight: bold; color: #2563eb;">Vendor Account Details</p>

                                <table role="presentation" cellpadding="8" cellspacing="0" style="margin-bottom: 24px; font-size: 15px; line-height: 1.6; border-collapse: collapse; width: 100%;">
                                    <tr>
                                        <td style="width: 200px; font-weight: bold; color: #2563eb;">Vendor / Company Name:</td>
                                        <td>{{ $vendor_company_name }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold; color: #2563eb;">Vendor ID:</td>
                                        <td>{{ $vendor_id }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold; color: #2563eb;">Login Email:</td>
                                        <td>{{ $login_email }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold; color: #2563eb;">Username:</td>
                                        <td>{{ $username }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold; color: #2563eb;">Portal URL:</td>
                                        <td>{{ $vendor_portal_url }}</td>
                                    </tr>
                                </table>

                                <p style="margin: 0 0 20px; text-align: center;">
                                    <a href="{{ $vendor_login_link }}" style="background-color: #2563eb; color: #ffffff; text-decoration: none; display: inline-block; padding: 12px 24px; border-radius: 6px; font-weight: bold;">
                                        Login to Vendor Portal
                                    </a>
                                </p>

                                <p style="margin: 0 0 16px; line-height: 1.7; font-size: 16px;">
                                    For security reasons, your password is not included in this email. If you have not yet set your password, please use the <strong>Forgot Password</strong> option on the login page to create one.
                                </p>

                                <p style="margin: 0 0 12px; font-size: 16px; font-weight: bold; color: #111827;">Important Security Information</p>

                                <p style="margin: 0 0 16px; line-height: 1.7; font-size: 16px;">
                                    Please keep your login credentials confidential and do not share them with unauthorized persons.
                                </p>

                                <p style="margin: 0 0 16px; line-height: 1.7; font-size: 16px;">
                                    If you did not request or authorize this vendor account, please contact our support team immediately at
                                    <a href="mailto:{{ $support_email }}" style="color: #2563eb;">{{ $support_email }}</a>.
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
