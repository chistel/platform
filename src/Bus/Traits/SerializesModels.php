<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           SerializesModels.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 10:11 PM
 */

namespace Platform\Bus\Traits;

use Illuminate\Queue\SerializesModels as BaseSerializesModels;

trait SerializesModels
{
    use BaseSerializesModels;
    use DatabaseSafeSerialization {
        DatabaseSafeSerialization::__sleep insteadof BaseSerializesModels;
        DatabaseSafeSerialization::__wakeup insteadof BaseSerializesModels;

        BaseSerializesModels::__sleep as baseSleep;
        BaseSerializesModels::__wakeup as baseWakeup;
    }

    public function completeSleep(): array
    {
        return $this->baseSleep();
    }

    public function completeWakeup()
    {
        $this->baseWakeup();
    }
}
