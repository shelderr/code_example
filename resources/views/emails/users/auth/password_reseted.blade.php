@extends('emails.layouts.basic')

@section('content')
    <tr>
        <td align="center" style="padding: 20px 0;">
            <img src="{{ asset('assets/mail/check.png') }}" alt="" width="100px"
                 style="display: block; max-width: 100%;"/>
        </td>
    </tr>
    <tr>
        <td style="font: 20px Arial,sans-serif; font-weight: 500; -webkit-text-size-adjust: none;"> {{ $username ?? '' }}
        </td>
    </tr>
    <tr>
        <td style="font: 15px Arial,sans-serif; line-height: 1.5; font-weight: 400; -webkit-text-size-adjust: none; padding: 30px 0; padding-right: 100px;">
            @lang('email_texts.reset_password_success')
        </td>
    </tr>
@endsection

@section('underline')
    <td style="font: 15px Arial,sans-serif; line-height: 1.5; font-weight: 400; -webkit-text-size-adjust: none; padding: 30px 0; padding-right: 100px;">
        @lang('email_texts.reset_password_text_security') <a href="mailto:support@showmemory.com"
                                                             style="color: #AE263F; text-decoration: none; font-weight: 600;">support@showmemory.com</a>
    </td>
@endsection



