@extends('emails.layouts.basic')

@section('content')
    <tr сlass="hello">
        <td style="font: 23px Arial,sans-serif; font-weight: 700; -webkit-text-size-adjust: none; padding-bottom: 75px;">
            @lang('email_texts.feedback_text')
        </td>
    </tr>

    <tr class="content">
        <td style="font: 15px Arial,sans-serif; font-weight: 400; -webkit-text-size-adjust: none; padding-bottom: 10px;">
            <b> @lang('email_texts.feedback_type'): </b> {{ $feedbackType }}
        </td>
    </tr>

    <tr class="content">
        <td style="font: 15px Arial,sans-serif; font-weight: 400; -webkit-text-size-adjust: none; padding-bottom: 10px;">
            <b> @lang('email_texts.user_name'): </b> {{ $userName }}
        </td>
    </tr>

    <tr class="content">
        <td style="font: 15px Arial,sans-serif; font-weight: 400; -webkit-text-size-adjust: none; padding-bottom: 10px;">
            <b> @lang('email_texts.subject'): </b> {{ $title }}

        </td>
    </tr>

    <tr class="content">
        <td style="font: 15px Arial,sans-serif; font-weight: 400; -webkit-text-size-adjust: none; padding-bottom: 10px;">
            <b> @lang('email_texts.message'): </b> {{ $msg }}
        </td>
    </tr>

    <tr class="content">
        <td style="font: 15px Arial,sans-serif; font-weight: 400; -webkit-text-size-adjust: none; padding-bottom: 10px;">
            <b> Link: </b> <a href="{{ $url }}">{{ $url }}</a>
        </td>
    </tr>

    <tr class="content">

        <td style="font: 15px Arial,sans-serif; font-weight: 400; -webkit-text-size-adjust: none; padding-bottom: 10px;">
            @if (!is_null($images) )
                <p>Attached images</p>
            @endif

            @foreach($images as $image)
                <p><a href="{{ $image->url }}">{{ $image->url }}</a></p>
            @endforeach
        </td>

    </tr>

    @if(! empty($links))
        <tr class="content">
            <td style="font: 15px Arial,sans-serif; font-weight: 400; -webkit-text-size-adjust: none; padding-bottom: 10px;">
                @if(! empty($links))
                    <p>Attached links</p>
                @endif

                @foreach($links as $attachment)
                    <p><a href="{{ $attachment->link }}">{{ $attachment->link }}</a></p>
                @endforeach
            </td>
        </tr>
    @endif
@endsection