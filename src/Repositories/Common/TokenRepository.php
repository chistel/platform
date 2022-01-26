<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           TokenRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:27 PM
 */

namespace Platform\Repositories\Common;

use Platform\Eloquent\Repository;
use Platform\Events\Common\SendToken;
use Platform\Events\Common\VerifyToken;
use Platform\Database\Eloquent\Models\Common\Token;
use Platform\Traits\Common\TokenTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class TokenRepository
 * @package Platform\Repositories\Common
 */
class TokenRepository extends Repository
{
   use TokenTrait;

	/**
	 * @return string
	 */
   public function model()
   {
      return Token::class;
   }

	/**
	 * Execute the job.
	 *
	 * @param $entity
	 * @param $type
	 * @return mixed
	 */
   public function sendToken($entity, $type)
   {
      return DB::transaction(function () use ($entity, $type) {
         $token = $this->createToken($entity, $type);
         event(new SendToken($token));
      });
   }

	/**
	 * @param $request
	 * @param $token
	 * @return mixed
	 */
   public function validateToken($request, $token)
   {
      return DB::transaction(function () use($request, $token){
         $token->tokenable()->update($request->toArray());

         event(new VerifyToken($token->tokenable));
         $token->delete();
         return $token->tokenable;
      });
   }
}
