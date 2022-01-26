<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Job.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Abstracts;

use Platform\Abstracts\Http\FormRequest;
use Platform\Traits\Common\Jobs;
use Platform\Traits\Common\Relationships;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class Job implements ShouldQueue
{
	use InteractsWithQueue, Jobs, Queueable, Relationships, SerializesModels;

	/**
	 * @param $request
	 * @return FormRequest
	 */
	public function getRequestInstance($request): FormRequest
    {
		if (!is_array($request)) {
			return $request;
		}

		$class = new class() extends FormRequest {};

		return $class->merge($request);
	}
}
