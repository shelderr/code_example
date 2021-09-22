<?php

namespace App\Rules\Event;

use App\Models\Events\Event;
use Illuminate\Contracts\Validation\Rule;

class IsOriginalRule implements Rule
{
    private Event $event;

    private string $errMsg = '';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $eventId)
    {
        $this->event = Event::findOrFail($eventId);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param        $isOriginal
     *
     * @return bool
     */
    public function passes($attribute, $isOriginal)
    {
        if ($isOriginal == false && ($this->event->is_original == true && $this->event->childrenEditions()->count() > 0)) {
            $this->errMsg = 'event already have editions';

            return false;
        }

        if ($isOriginal == true && ($this->event->is_original == false && $this->event->parentEdition()->exists())) {
            $this->errMsg = 'event already in other editions';
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
        return $this->errMsg;
    }
}
