<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           SerializesModels.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Bus;

use Illuminate\Queue\SerializesModels as BaseSerializesModels;

trait SerializesModels
{
    use BaseSerializesModels {

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
