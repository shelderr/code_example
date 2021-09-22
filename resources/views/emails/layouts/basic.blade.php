{{--<!DOCTYPE html>
<html lang="en">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width">
    <title>

    </title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
</head>

<body>


<table border="0" cellpadding="0" cellspacing="0"
       style="max-width: 1280px; width: 100%; margin: 0; padding: 0; background: #fafafa; letter-spacing: 0.6px;">
    <tr>
        <td>
            <img src="{{ asset('assets/mail/background.jpg') }}" alt="" width="1280px" style="display: block; max-width: 100%;"/>
        </td>
    </tr>
    <tr>
        <td style="padding: 20px 0;"></td>
    </tr>
    <tr>
        <td style="display: block;">
            <table border="0" cellpadding="0" cellspacing="0"
                   style="max-width: 1000px; width: 100%; margin: 0; padding: 0; background: #ffffff; border-radius: 5px; padding: 65px 74px; padding-bottom: 20px; margin: 0 auto;">
                @yield('content')
            </table>
        </td>
    </tr>

    @yield('underline')

    <tr>
        <td style="padding: 20px 0px;"></td>
    </tr>
    @include('emails.layouts.footer')
</table>

</body>
</html>--}}

        <!DOCTYPE html>
<html lang="en">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width">
    <title>

    </title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
</head>

<body>


<table border="0" cellpadding="0" cellspacing="0"
       style="max-width: 1200px; width: 100%; margin: 0; padding: 0; background: #ffffff;">
    <tr>
        <td>
            <img src="{{config('app.domain') . "/storage/assets/mail/mail.jpg" }}" alt="" width="1200px"
                 style="display: block; max-width: 100%;"/>
        </td>
    </tr>
    <tr>
        <td style="padding: 20px 0;"></td>
    </tr>
    <tr>
        <td>
            <table border="0" cellpadding="0" cellspacing="0"
                   style="max-width: 1160px; width: 96%; margin: 0; padding: 0; background: #ffffff; border-radius: 5px; padding: 30px 25px; margin: 0 auto;">
                @yield('content')
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding: 5px 0;"></td>
    </tr>
    <tr>
        <td style="height: 1px; background: #E0E0E0;"></td>
    </tr>
    <tr>
        <td style="padding: 5px 0;"></td>
    </tr>
    <tr>
        <td>
            <table border="0" cellpadding="0" cellspacing="0"
                   style="max-width: 1160px; width: 96%; margin: 0; padding: 0; background: #ffffff; border-radius: 5px; padding: 30px 25px; margin: 0 auto;">
                @yield('underline')
                <tr>
                    <td style="font: 15px Arial,sans-serif; line-height: 1.5; font-weight: 400; -webkit-text-size-adjust: none; padding: 15px 0;">
                       <p> Do not reply to this email. </p>
                        <p>
                            If you need to contact us, please write to <a href="mailto:admin@dsadasads.com" style="color: #AE263F; text-decoration: none;">admin@sdadsaadsads.com</a> or use the
                            <a href="{{ config('app.domain') . '/send_feedback/all?type=general' }}" style="color: #AE263F; text-decoration: none;"> Feedback Form. </a>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="font: 15px Arial,sans-serif; line-height: 1.5; font-weight: 400; -webkit-text-size-adjust: none; padding: 15px 0;">
                        Thank you,
                        <span style="display: block; font-weight: 700;">Team.</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    @include('emails.layouts.footer')
</table>

</body>


</html>