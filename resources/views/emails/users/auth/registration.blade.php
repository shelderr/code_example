@extends('emails.layouts.basic')

@section('content')
    <tr>
        <td style="font: 24px Arial,sans-serif; font-weight: 700; -webkit-text-size-adjust: none; color: #AE263F; padding-bottom: 40px;">
            Welcome to ShowMemory
        </td>
    </tr>
    <tr>
        <td style="font: 20px Arial,sans-serif; font-weight: 500; -webkit-text-size-adjust: none;">
            @lang('email_texts.hello'), {{ $username }}
        </td>
    </tr>
    <tr>
        <td style="font: 15px Arial,sans-serif; line-height: 1.5; font-weight: 400; -webkit-text-size-adjust: none; padding: 30px 0; padding-right: 100px;">
            @lang('email_texts.register_text')
        </td>
    </tr>

    <tr class="link">
        <td style="font: 15px Arial,sans-serif; font-weight: 400; -webkit-text-size-adjust: none;">
            <a href="{{ $confirmationUrl }}" style="color: #AE263F; text-decoration: none;">
                link_________________
            </a>
        </td>
    </tr>
@endsection
@section('underline')
    <tr class="underline">
        <td>
            <table border="0" cellpadding="0" cellspacing="0"
                   style="max-width: 1000px; width: 100%; margin: 0; padding: 0; background: #ffffff; border-radius: 5px; padding: 10px 74px; margin: 0 auto;">
                <tr>
                    <td style="font: 15px Arial,sans-serif; font-weight: 500; -webkit-text-size-adjust: none;">
                        <p>If the link did not open, copy it to the clipboard, paste it into the address bar of the
                            browser, press <a href="" style="color: #AE263F; text-decoration: none;">Enter.</a>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection
