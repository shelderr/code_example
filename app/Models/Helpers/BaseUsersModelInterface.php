<?php

namespace App\Models\Helpers;

interface BaseUsersModelInterface
{
    public const DEFAULT_LANGUAGE = 'en';

    public const CONFIRM_TOKEN = 32;

    public const COUNTRY_LENGTH = 2;

    public const EMAIL_REGEX = '/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/';

    public const USERNAME_REGEX = '/^[A-Za-z0-9_-]{2,60}$/';

    public const PASSWORD_REGEX = '/(?=^.\S{7,}$)((?=.*\d)|(?=.*\W+))(?=.*[0-9])(?![.\n])(?=.*[A-Za-z0-9])(?=.*[~!^(){}<>%@#&*+.,=_-]).*$/';

    public const UUID_REGEX = '^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$';

    /**
     * "+" is required, first digit is not "0"
     */
    public const PHONE_REGEX = '/^[\+][1-9]{1}[\d]{9,13}$/';

    public const DEFAULT_CURRENCY = 'usd';

    public const SECRET_KEY_LENGTH = 32;

    //Photo
    public const MIN_WIDTH = 250;
    public const MAX_WIDTH = 500;

    public const MIN_HEIGHT = 500;
    public const MAX_HEIGHT = 1000;

    //Relations
    public const PERSONALITY_RELATION = 'personalityLink';

    //Link statuses
    public const LINK_STATUS_REJECTED = 'rejected';
    public const LINK_STATUS_ACCEPTED = 'accepted';
    public const LINK_STATUS_PENDING  = 'pending';
}
