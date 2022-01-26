<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           AfricasTalkingMessage.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     04/12/2021, 12:07 PM
 */

namespace Platform\Services\Channels\AfricasTalking;

class AfricasTalkingMessage
{
	/** @var string */
	protected string $content;

	/** @var string|null */
	protected ?string $from;

	/**
	 * Set content for this message.
	 *
	 * @param string $content
	 * @return $this
	 */
	public function content(string $content): self
	{
		$this->content = trim($content);

		return $this;
	}

	/**
	 * Set sender for this message.
	 *
	 * @param string $from
	 * @return self
	 */
	public function from(string $from): self
	{
		$this->from = trim($from);

		return $this;
	}

	/**
	 * Get message content.
	 *
	 * @return string
	 */
	public function getContent(): string
    {
		return $this->content;
	}

	/**
	 * Get sender info.
	 *
	 * @return string
	 */
	public function getSender(): string
    {
		return $this->from ?? config('platform.africastalking.from');
	}
}
