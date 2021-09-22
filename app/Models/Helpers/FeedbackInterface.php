<?php

namespace App\Models\Helpers;

interface FeedbackInterface
{
    //TYPES
    public const TYPE_IDEAS                 = 'ideas_improvement';
    public const TYPE_BULK                  = 'bulk_content_submission';
    public const TYPE_BUG                   = 'report_bug';
    public const TYPE_MISTAKE               = 'inaccurate_content';
    public const TYPE_INAPPROPRIATE_CONTENT = 'inappropriate_content';
    public const TYPE_COPYRIGHT             = 'copyright_issue';
    public const TYPE_OTHER                 = 'other';


    //FOLDERS
    public const MEDIA_FOLDER = '/uploads/feedback/media/';

    //STATUSES
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_PENDING  = 'pending';
    public const STATUS_DECLINED = 'declined';

    //Entities
    public const EVENT_ENTITY      = 'event';
    public const SHOW_ENTITY       = 'show';
    public const COLLECTIVE_ENTITY = 'collective';
    public const VENUE_ENTITY      = 'venue';
    public const PERSON_ENTITY     = 'person';

    //RELATIONS
    public const RELATION_IMAGES = 'images';
}
