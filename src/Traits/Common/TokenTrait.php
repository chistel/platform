<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           TokenTrait.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:55 PM
 */

namespace Platform\Traits\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Platform\Database\Eloquent\Models\Common\Token;

/**
 * Trait TokenTrait
 *
 * @package Platform\Traits\Common
 */
trait TokenTrait
{
    /**
     * @param Model $model
     * @param $tokenType
     * @param null $source
     * @param null $code
     * @return mixed
     */
    public function createToken(Model $model, $tokenType, $source = null, $code = null)
    {
        $verificationToken = $code ?? Str::random(30);
        $verificationModel = new Token();
        $verificationModel->token = $verificationToken;
        $verificationModel->type = $tokenType;
        $verificationModel->source = $source ?? null;
        return $model->tokens()->save($verificationModel);
    }

}

