{{-- resources/views/emails/contact-reply.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply to Your Message</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            background-color: #f4f1eb;
            color: #2c2c2c;
            line-height: 1.7;
        }

        .email-wrapper {
            max-width: 620px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 2px;
            overflow: hidden;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
        }

        /* ── Header ── */
        .email-header {
            background: #1a2e1a;
            padding: 40px 48px 32px;
            text-align: center;
            position: relative;
        }

        .email-header::after {
            content: '';
            display: block;
            width: 60px;
            height: 2px;
            background: #c9a84c;
            margin: 20px auto 0;
        }

        .hotel-name {
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .hotel-tagline {
            font-size: 12px;
            color: #c9a84c;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-top: 6px;
            font-family: 'Arial', sans-serif;
        }

        /* ── Gold Banner ── */
        .gold-banner {
            background: #c9a84c;
            padding: 14px 48px;
            text-align: center;
        }

        .gold-banner p {
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #1a2e1a;
            font-family: 'Arial', sans-serif;
            font-weight: 700;
        }

        /* ── Body ── */
        .email-body {
            padding: 48px;
        }

        .greeting {
            font-size: 22px;
            color: #1a2e1a;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .intro-text {
            font-size: 15px;
            color: #555;
            margin-bottom: 32px;
            font-family: 'Arial', sans-serif;
            line-height: 1.8;
        }

        /* ── Original Message Box ── */
        .original-msg-label {
            font-size: 10px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #c9a84c;
            font-family: 'Arial', sans-serif;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .original-msg-box {
            background: #f9f7f2;
            border-left: 3px solid #c9a84c;
            padding: 16px 20px;
            margin-bottom: 32px;
            border-radius: 0 4px 4px 0;
        }

        .original-msg-box p {
            font-size: 14px;
            color: #777;
            font-style: italic;
            font-family: 'Arial', sans-serif;
        }

        /* ── Reply Box ── */
        .reply-label {
            font-size: 10px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #1a2e1a;
            font-family: 'Arial', sans-serif;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .reply-box {
            background: #1a2e1a;
            padding: 28px 32px;
            border-radius: 4px;
            margin-bottom: 36px;
        }

        .reply-box p {
            font-size: 15px;
            color: #e8e4d9;
            font-family: 'Arial', sans-serif;
            line-height: 1.9;
            white-space: pre-line;
        }

        /* ── Divider ── */
        .divider {
            border: none;
            border-top: 1px solid #e8e4d9;
            margin: 32px 0;
        }

        .closing {
            font-size: 15px;
            color: #555;
            font-family: 'Arial', sans-serif;
        }

        .signature {
            margin-top: 20px;
        }

        .signature .name {
            font-size: 17px;
            color: #1a2e1a;
            font-weight: 700;
        }

        .signature .role {
            font-size: 12px;
            color: #c9a84c;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-family: 'Arial', sans-serif;
        }

        /* ── Footer ── */
        .email-footer {
            background: #f4f1eb;
            padding: 28px 48px;
            text-align: center;
            border-top: 1px solid #e0dbd0;
        }

        .email-footer p {
            font-size: 11px;
            color: #999;
            font-family: 'Arial', sans-serif;
            letter-spacing: 0.5px;
            line-height: 1.8;
        }

        .footer-brand {
            font-size: 13px;
            color: #1a2e1a;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        /* ── Responsive ── */
        @media (max-width: 600px) {

            .email-header,
            .email-body,
            .gold-banner,
            .email-footer {
                padding-left: 24px;
                padding-right: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">

        {{-- ── Header ── --}}
        <div class="email-header">
            <div class="hotel-name">Bella Monte</div>
            <div class="hotel-tagline">Resort &bull; Shogran</div>
        </div>

        {{-- ── Gold Banner ── --}}
        <div class="gold-banner">
            <p>&#9993; &nbsp; Message from Our Team</p>
        </div>

        {{-- ── Body ── --}}
        <div class="email-body">

            <p class="greeting">Dear {{ $contact->name }},</p>

            <p class="intro-text">
                Thank you for reaching out to Bella Monte Resort. We have carefully reviewed your message and are
                pleased to respond below.
            </p>

            {{-- Original Message --}}
            <p class="original-msg-label">&#9656; Your Original Message</p>
            <div class="original-msg-box">
                <p>{{ $contact->message }}</p>
            </div>

            {{-- Admin Reply --}}
            <p class="reply-label">&#9656; Our Response</p>
            <div class="reply-box">
                <p>{{ $contact->reply_message }}</p>
            </div>

            <hr class="divider">

            <p class="closing">
                We hope this addresses your inquiry. Should you have any further questions, please do not hesitate to
                contact us. We look forward to welcoming you to Bella Monte Resort.
            </p>

            <div class="signature">
                <p class="name">Bella Monte Resort Team</p>
                <p class="role">Guest Relations &bull; Shogran</p>
            </div>

        </div>

        {{-- ── Footer ── --}}
        <div class="email-footer">
            <p class="footer-brand">Bella Monte Resort</p>
            <p>
                Shogran, Kaghan Valley, Pakistan<br>
                This email was sent in response to your contact form submission.<br>
                &copy; {{ date('Y') }} Bella Monte Resort. All rights reserved.
            </p>
        </div>

    </div>
</body>

</html>
