@extends('emails.layouts.basic')

@section('content')
    <tr>
        <td style="font: 20px Arial,sans-serif; font-weight: 500; -webkit-text-size-adjust: none;">
            @lang('email_texts.hello'), {{ $firstname }}
        </td>
    </tr>
    <tr>
        <td style="font: 15px Arial,sans-serif; line-height: 1.5; font-weight: 400; -webkit-text-size-adjust: none; padding: 30px 0; padding-right: 100px;">
            @lang('email_texts.blocked_account') <br>
            @lang('email_texts.restore_account') <br>
        </td>
    </tr>
@endsection
