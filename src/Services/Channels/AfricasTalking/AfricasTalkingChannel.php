<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           AfricasTalkingChannel.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     04/12/2021, 12:07 PM
 */

namespace Platform\Services\Channels\AfricasTalking;

use Exception;
use Illuminate\Notifications\Notification;
use Platform\Exceptions\CouldNotSendNotification;
use AfricasTalking\SDK\AfricasTalking as AfricasTalkingSDK;

class AfricasTalkingChannel
{
    /** @var AfricasTalkingSDK */
    protected AfricasTalkingSDK $africasTalking;

    /** @param  AfricasTalkingSDK  $africasTalking */
    public function __construct(AfricasTalkingSDK $africasTalking)
    {
        $this->africasTalking = $africasTalking;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param  Notification  $notification
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toAfricasTalking($notifiable);

        if (!$phoneNumber = $notifiable->routeNotificationFor('africasTalking')) {
            return;
        }

        if (empty(($message->getSender())) || is_null($message->getSender())) {
            $params = [
                'to' => $phoneNumber,
                'message' => $message->getContent(),
            ];
        } else {
            $params = [
                'to' => $phoneNumber,
                'message' => $message->getContent(),
                'from' => $message->getSender(),
            ];
        }

        try {
            $this->africasTalking->sms()->send($params);
        } catch (Exception $e) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($e->getMessage());
        }
    }
}
