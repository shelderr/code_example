@extends('emails.layouts.basic')

@section('content')
    <tr>
        <td style="font: 20px Arial,sans-serif; font-weight: 500; -webkit-text-size-adjust: none;">
            @lang('email_texts.hello'), {{ $userName }}
        </td>
    </tr>
    <tr>
        <td style="font: 15px Arial,sans-serif; line-height: 1.5; font-weight: 400; -webkit-text-size-adjust: none; padding: 30px 0; padding-right: 100px;">
            @lang('email_texts.verification.rejected') <br>
        </td>
    </tr>
    <tr>
        <td align="center">
            <button style="font: 20px Arial,sans-serif; font-weight: 500; padding: 15px 25px; background: #AE263F; border-radius: 8px; border: none; appearance: none; color: #ffffff; margin: 15px auto; cursor: pointer;">
                <a href="{{ $personLink ?? ''  }}" style="color: inherit; text-decoration: inherit; ">
                    See Person
                </a>
            </button>
        </td>
    </tr>

@endsection
