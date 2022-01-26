<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Broadcast.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Events;

use Illuminate\Contracts\Auth\Authenticatable;

class Broadcast
{
    /**
     * @param Authenticatable $user
     * @return string|null
     */
    public function userChannel(Authenticatable $user): ?string
    {
        if (!$user) {
            return null;
        }
        return "private-user-{$user->hashId()}";
    }

    /**
     * @return string
     */
    public function simpleMessage(): string
    {
        return 'message.simple';
    }
}
