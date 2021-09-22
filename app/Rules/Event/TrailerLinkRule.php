<?php

namespace App\Rules\Event;

use App\Exceptions\Http\BadRequestException;
use App\Models\Events\EventTrailers;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Response;

class TrailerLinkRule implements Rule
{
    private $data;

    private string $msg = 'url validation error';

    /**
     * Create a new rule instance.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param        $url
     *
     * @return bool
     * @throws \App\Exceptions\Http\BadRequestException
     */
    public function passes($attribute, $url): bool
    {
        if (is_null($this->data)) {
            throw new BadRequestException('Missing url type', Response::HTTP_BAD_REQUEST);
        }

        if (is_array($this->data)) {
            $arrayId = explode('.', $attribute)[1];

            $type = $this->data[$arrayId]['type'];
        }

        if (is_string($this->data)) {
            $type = $this->data;
        }

        if ($type === EventTrailers::TYPE_YOUTUBE) {
            $pattern = EventTrailers::YOUTUBE_REGEX;
        }

        if ($type === EventTrailers::TYPE_VIMEO) {
            $pattern = EventTrailers::VIMEO_REGEX;
        }

        if (! isset($pattern)) {
            throw new BadRequestException(
                'Url type not found',
                Response::HTTP_BAD_REQUEST
            );
        }

        $urlCheck = preg_match($pattern, $url);

        if (! $urlCheck) {
            $this->msg = "$type link is incorrect";

            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->msg;
    }
}
