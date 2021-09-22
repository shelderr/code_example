<?php

namespace App\Exceptions;

interface ErrorMessages
{
    public const IMAGE_NOT_EXIST = 'image_not_exist';

    public const INVALID_CREDENTIALS = 'invalid_credentials';

    public const MODEL_LOAD_ERROR = 'model_not_loaded';

    public const UNAUTHORIZED = 'unauthorized';

    public const MODEL_NOT_FOUND = 'model_not_found';

    public const MODEL_NOT_UPDATED = 'model_not_updated';

    public const MODEL_NOT_CREATED = 'model_not_created';

    public const MODEL_NOT_DELETED = 'model_not_deleted';

    public const UNAUTHENTICATED = 'unauthenticated';

    public const USER_NOT_FOUND = 'user_not_found';

    public const USER_NOT_EXIST = 'user_does_not_exist';

    public const USERNAME_NOT_EXIST = 'username does not exist. Please go to the settings page and set username ';

    public const USER_BLOCKED = 'user_blocked';

    public const USER_NOT_HAS_DELETE_REQUESTS = 'User has no delete requests';

    public const USER_HAS_NO_EMAIL = 'user has no email address';

    public const EMAIL_IS_BLOCKED = 'user is blocked';

    public const ADMIN_NOT_FOUND = 'admin_not_found';

    public const ADMIN_NOT_EXIST = 'admin_not_exist';

    public const ADMIN_BLOCKED = 'admin_blocked';

    public const EMAIL_NOT_CONFIRMED = 'email_not_confirmed';

    public const TOKEN_NOT_PROVIDED = 'token_not_provided';

    public const TOKEN_EXPIRED = 'token_expired';

    public const PHONE_NOT_FOUND = 'phone_not_found';

    public const INVALID_TOTP_CODE = 'invalid_totp_code';

    public const TOTP_CODE_ISNT_SENDED = 'totp_code_isnt_sended';

    public const TWO_FA_AUTH_ENABLED = 'two_fa_auth_enabled';

    public const TOKEN_INVALID = 'token_invalid';

    public const INVALID_TOKEN = 'invalid_token';

    public const NO_PERMISSIONS = 'no_permissions';

    public const DATA_NOT_FOUND = 'data_not_found';

    public const HTTP_NOT_FOUND = 'http_not_found';

    public const FILE_UPLOAD = 'error_during_uploading_file';

    public const DATA_IS_ALREADY_CREATED = 'data_is_already_created';

    public const POST_TOO_LARGE = 'post_too_large';

    public const ALREADY_REQUESTED_CHANGE_EMAIL = 'already_requested_email_change';

    public const BINDING_ERROR = 'binding_resolution_exception';

    public const SOMETHING_WENT_WRONG = 'something_went_wrong';

    public const ACCESS_DENIED = 'access_denied';

    public const DATE_NOT_ALLOWED = 'date not allowed for this collection type';

    public const HAS_MANY_COUNTRIES = 'already has too many countries';


    //Events
    public const ALREADY_APPLAUDED               = 'already applauded';
    public const TRAILERS_EXCEEDED               = 'Number of trailers exceeded';
    public const INVALID_EVENT_PERSON_TYPE       = 'This type is invalid for this event';
    public const INVALID_SHOW_PERSON_TYPE        = 'This type is invalid for this show';
    public const USER_HAVE_NO_APPLAUDS           = 'The user has not rated it before';
    public const CANT_ATTACH_COLLECTIVE_TO_EVENT = 'Unable to attach a collective to an event';

    //Event Acts
    public const ACTS_AWARDS_ONLY_FOR_EVENTS = 'Awards can be added only in events';

    //EventPerson
    public const AWARDS_ONLY_FOR_JURY = 'Awards can only be applied to the jury';

    //Bookmarks
    public const FOLDER_NOT_FOUND         = 'folder not found';
    public const BOOKMARK_ALREADY_APPLIED = 'bookmark already applied';

    //Persons
    public const PERSON_ALREADY_VERIFIED        = 'person already verified';
    public const USER_ALREADY_HAVE_VERIFICATION = 'user already verified';
    public const USER_WAITING_VERIFICATION      = 'user waiting verification';
    public const USER_HAVE_NO_REQUESTS          = 'user has no requests';

    //Collectives
    public const PERSON_ALREADY_ATTEMPTED = 'the person is already in this collective';

    //Venue
    public const SHOW_ALREADY_ATTACHED  = 'the show is already tied to this place';
    public const VENUE_ALREADY_ATTACHED = 'the venue is already tied to this place';
    public const ONLY_COUNTRY_ALLOWED   = 'Only country allowed in this case';
}
