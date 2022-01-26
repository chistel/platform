<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           hashid.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

use Vinkla\Hashids\Facades\Hashids;

/**
 * @param $hashid
 * @return null
 */
function hashid($hashid)
{
   if (is_null($hashid)) {
      return null;
   }
   return Hashids::connection(getHashidsConnection())
      ->encode($hashid);
}

/**
 * Decode the hashid to the id
 *
 * @param string $hashid
 * @return int|null
 */
function decodeHashId($hashid): ?int
{
   if (is_null($hashid)) {
      return null;
   }
   return @Hashids::connection(getHashidsConnection())
      ->decode($hashid)[0];
}

function getHashidsConnection()
{
   return config('hashids.default');
}
