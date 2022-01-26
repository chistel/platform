<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           GoogleCalendar.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     04/12/2021, 9:01 AM
 */

namespace Platform\Services\Google;

use Platform\Exceptions\GoogleClientInvalidConfiguration;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use DateTime;
use DateTimeInterface;
use Google\Exception;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_Events;

class GoogleCalendar
{
	private Google_Service_Calendar $calendarService;

	/**
	 * @param string $calendarId
	 * @throws Exception
	 * @throws GoogleClientInvalidConfiguration
	 */
	public function __construct(protected string $calendarId)
	{
		$config = config('services.google');
		$client = (new GoogleClient)->createAuthenticatedGoogleClient($config);
		//$client->setSubject('chistelbrown@gmail.com');
		$client->setSubject('admin@gateacademy.com.ng');

		$this->calendarService =  new Google_Service_Calendar($client);
	}

	public function getCalendarId(): string
	{
		return $this->calendarId;
	}

	/*
	 * @link https://developers.google.com/google-apps/calendar/v3/reference/events/list
	 */
	public function listEvents(CarbonInterface $startDateTime = null, CarbonInterface $endDateTime = null, array $queryParameters = []): Google_Service_Calendar_Events
	{
		$parameters = [
			'singleEvents' => true,
			'orderBy' => 'startTime',
		];

		if (is_null($startDateTime)) {
			$startDateTime = Carbon::now()->startOfDay();
		}

		$parameters['timeMin'] = $startDateTime->format(DateTimeInterface::RFC3339);

		if (is_null($endDateTime)) {
			$endDateTime = Carbon::now()->addYear()->endOfDay();
		}
		$parameters['timeMax'] = $endDateTime->format(DateTimeInterface::RFC3339);

		$parameters = array_merge($parameters, $queryParameters);

		return $this
			->calendarService
			->events
			->listEvents($this->calendarId, $parameters);
	}

	public function getEvent(string $eventId): Google_Service_Calendar_Event
	{
		return $this->calendarService->events->get($this->calendarId, $eventId);
	}

	/*
	 * @link https://developers.google.com/google-apps/calendar/v3/reference/events/insert
	 */
	public function insertEvent($event, $optParams = []): Google_Service_Calendar_Event
	{
		//$dd = $this->calendarService->calendars->get($this->calendarId);
		//dd($dd);

		if ($event instanceof GoogleEvent) {
			$event = $event->googleEvent;
		}

		return $this->calendarService->events->insert($this->calendarId, $event, $optParams);
	}

	/*
	* @link https://developers.google.com/calendar/v3/reference/events/quickAdd
	*/
	public function insertEventFromText(string $event): Google_Service_Calendar_Event
	{
		return $this->calendarService->events->quickAdd($this->calendarId, $event);
	}

	/**
	 * @param $event
	 * @param array $optParams
	 * @return Google_Service_Calendar_Event
	 */
	public function updateEvent($event, array $optParams = []): Google_Service_Calendar_Event
	{
		if ($event instanceof GoogleEvent) {
			$event = $event->googleEvent;
		}

		return $this->calendarService->events->update($this->calendarId, $event->id, $event, $optParams);
	}

	/**
	 * @param $eventId
	 * @param array $optParams
	 */
	public function deleteEvent($eventId, array $optParams = [])
	{
		if ($eventId instanceof GoogleEvent) {
			$eventId = $eventId->id;
		}

		$this->calendarService->events->delete($this->calendarId, $eventId, $optParams);
	}

	/**
	 * @return Google_Service_Calendar
	 */
	public function getService(): Google_Service_Calendar
	{
		return $this->calendarService;
	}
}
