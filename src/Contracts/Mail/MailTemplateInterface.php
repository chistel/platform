<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           MailTemplateInterface.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 2:36 PM
 */

namespace Platform\Contracts\Mail;

use Illuminate\Contracts\Mail\Mailable;

interface MailTemplateInterface
{
    public static function findForMailable(Mailable $mailable);

    /**
     * Get the mail subject.
     *
     * @return string
     */
    public function getSubject(): string;

    /**
     * Get the mail template.
     *
     * @return string
     */
    public function getHtmlTemplate(): string;

    /**
     * Get the mail template.
     *
     * @return null|string
     */
    public function getTextTemplate(): ?string;
}

