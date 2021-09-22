@extends('emails.layouts.basic')

@section('content')
    <tr>
        <td align="center" style="font: 20px Arial,sans-serif; font-weight: 500; -webkit-text-size-adjust: none;">
            @lang('email_texts.reset_password_text_1')
        </td>
    </tr>

    <tr>
        <td align="center">
            <button style="font: 20px Arial,sans-serif; font-weight: 500; padding: 15px 25px; background: #AE263F; border-radius: 8px; border: none; appearance: none; color: #ffffff; margin: 15px auto; cursor: pointer;">
                <a href="{{ $resetUrl ?? ''  }}" style="color: inherit; text-decoration: inherit; ">
                    Reset Password
                </a>
            </button>
        </td>
    </tr>
    {{--<tr class="link">
        <td style="font: 15px Arial,sans-serif; font-weight: 400; -webkit-text-size-adjust: none;">
            <a href="{{$resetUrl ?? ''}}" style="color: #4AB248; text-decoration: none;">
                link_________________
            </a>
        </td>
    </tr>--}}
@endsection

@section('underline')
    <td
            style="font: 15px Arial,sans-serif; line-height: 1.5; font-weight: 400; -webkit-text-size-adjust: none; padding: 30px 0; padding-right: 100px;">
        @lang('email_texts.reset_password_text_security')  <a href="mailto:support@showmemory.com"
                                                             style="color: #AE263F; text-decoration: none; font-weight: 600;">support@showmemory.com</a>
    </td>
@endsection



