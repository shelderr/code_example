<?php

namespace App\Traits;

trait DeletedUserAccessors
{
    private string $message = 'Deleted User';

    public function getEmailAttribute($email)
    {
        if ($this->is_deleted && self::$showRemovedUserFields == false) {
            return $this->message;
        }

        return $email;
    }

    public function getUserNameAttribute($username)
    {
        if ($this->is_deleted && self::$showRemovedUserFields == false) {
            return $this->message;
        }

        return $username;
    }

    public function getPhotoAttribute($photo)
    {
        if ($this->is_deleted && self::$showRemovedUserFields == false) {
            return $this->message;
        }

        return $photo;
    }
}
