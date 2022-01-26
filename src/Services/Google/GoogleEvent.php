<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           GoogleEvent.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     04/12/2021, 9:01 AM
 */

namespace Platform\Services\Google;

use Platform\Exceptions\GoogleClientInvalidConfiguration;
use ArrayAccess;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use DateTimeInterface;
use Google\Exception;
use Google_Service_Calendar_ConferenceData;
use Google_Service_Calendar_ConferenceSolutionKey;
use Google_Service_Calendar_CreateConferenceRequest;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventAttendee;
use Google_Service_Calendar_EventDateTime;
use Google_Service_Calendar_EventSource;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RRule\RRule;

class GoogleEvent
{
	/** @var Google_Service_Calendar_Event */
	public $googleEvent;

	/**
	 * @param string|null $calendarId
	 * @param array $attendees
	 */
	public function __construct(protected ?string $calendarId = null, protected array $attendees = [])
	{
		$this->attendees = [];
		$this->googleEvent = new Google_Service_Calendar_Event;
	}

	/**
	 * @param Google_Service_Calendar_Event $googleEvent
	 * @param $calendarId
	 * @return static
	 * @throws Exception
	 * @throws GoogleClientInvalidConfiguration
	 */
	public static function createFromGoogleCalendarEvent(Google_Service_Calendar_Event $googleEvent, $calendarId): static
	{
		//this option are to create a conference and add a link to meet in event
		/*$googleCalendar = static::getGoogleCalendar($calendarId);
		$service = $googleCalendar->getService();
		$conference = new Google_Service_Calendar_ConferenceData();
		$conferenceRequest = new Google_Service_Calendar_CreateConferenceRequest();
		$conferenceRequest->setRequestId(Str::uuid());
		$conference->setCreateRequest($conferenceRequest);
		$googleEvent->setConferenceData($conference);
		$googleEvent = $service->events->patch($calendarId, $googleEvent->id, $googleEvent, ['conferenceDataVersion' => 1]);
		*/
		$event = new static;

		$event->googleEvent = $googleEvent;
		$event->calendarId = $calendarId;

		return $event;
	}

	/**
	 * @param array $properties
	 * @param string|null $calendarId
	 * @param array $optParams
	 * @return mixed
	 * @throws Exception
	 * @throws GoogleClientInvalidConfiguration
	 */
	public static function create(array $properties, string $calendarId = null, array $optParams = []): mixed
	{
		$event = new static;

		$event->calendarId = static::getGoogleCalendar($calendarId)->getCalendarId();

		foreach ($properties as $name => $value) {
			$event->$name = $value;
		}

		return $event->save('insertEvent', $optParams);
	}

	/**
	 * @param string $text
	 * @return GoogleEvent
	 * @throws Exception
	 * @throws GoogleClientInvalidConfiguration
	 */
	public static function quickCreate(string $text): GoogleEvent
	{
		$event = new static;

		$event->calendarId = static::getGoogleCalendar()->getCalendarId();

		return $event->quickSave($text);
	}

	/**
	 * @param CarbonInterface|null $startDateTime
	 * @param CarbonInterface|null $endDateTime
	 * @param array $queryParameters
	 * @param string|null $calendarId
	 * @return Collection
	 * @throws Exception
	 * @throws GoogleClientInvalidConfiguration
	 */
	public static function get(CarbonInterface $startDateTime = null, CarbonInterface $endDateTime = null, array $queryParameters = [], string $calendarId = null): Collection
	{
		$googleCalendar = static::getGoogleCalendar($calendarId);

		$googleEvents = $googleCalendar->listEvents($startDateTime, $endDateTime, $queryParameters);

		$googleEventsList = $googleEvents->getItems();

		while ($googleEvents->getNextPageToken()) {
			$queryParameters['pageToken'] = $googleEvents->getNextPageToken();

			$googleEvents = $googleCalendar->listEvents($startDateTime, $endDateTime, $queryParameters);

			$googleEventsList = array_merge($googleEventsList, $googleEvents->getItems());
		}

		$useUserOrder = isset($queryParameters['orderBy']);

		return collect($googleEventsList)
			->map(function (Google_Service_Calendar_Event $event) use ($calendarId) {
				return static::createFromGoogleCalendarEvent($event, $calendarId);
			})
			->sortBy(function (self $event, $index) use ($useUserOrder) {
				if ($useUserOrder) {
					return $index;
				}

				return $event->sortDate;
			})
			->values();
	}

	/**
	 * @param $eventId
	 * @param string|null $calendarId
	 * @return static
	 * @throws Exception
	 * @throws GoogleClientInvalidConfiguration
	 */
	public static function find($eventId, string $calendarId = null): self
	{
		$googleCalendar = static::getGoogleCalendar($calendarId);

		$googleEvent = $googleCalendar->getEvent($eventId);

		return static::createFromGoogleCalendarEvent($googleEvent, $calendarId);
	}

	/**
	 * @param $name
	 * @return array|ArrayAccess|Carbon|false|mixed|string
	 */
	public function __get($name)
	{
		$name = $this->getFieldName($name);

		if ($name === 'sortDate') {
			return $this->getSortDate();
		}

		if ($name === 'source') {
			return [
				'title' => $this->googleEvent->getSource()->title,
				'url' => $this->googleEvent->getSource()->url,
			];
		}

		$value = Arr::get($this->googleEvent, $name);

		if (in_array($name, ['start.date', 'end.date']) && $value) {
			$value = Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
		}

		if (in_array($name, ['start.dateTime', 'end.dateTime']) && $value) {
			$value = Carbon::createFromFormat(DateTimeInterface::RFC3339, $value);
		}

		return $value;
	}

	/**
	 * @param $name
	 * @param $value
	 */
	public function __set($name, $value)
	{
		$name = $this->getFieldName($name);

		if (in_array($name, ['start.date', 'end.date', 'start.dateTime', 'end.dateTime'])) {
			$this->setDateProperty($name, $value);

			return;
		}

		if ($name == 'source') {
			$this->setSourceProperty($value);

			return;
		}
		if ($name == 'conferenceData'){
			#Try to create a Google Meet and attach it to event
			/*$solution_key = new Google_Service_Calendar_ConferenceSolutionKey();
			$solution_key->setType("hangoutsMeet");
			$confrequest = new Google_Service_Calendar_CreateConferenceRequest();
			$confrequest->setRequestId(Str::random(10));
			$confrequest->setConferenceSolutionKey($solution_key);
			$confdata = new Google_Service_Calendar_ConferenceData();
			$confdata->setCreateRequest($confrequest);
			$this->googleEvent->setConferenceData($confdata);
			return;*/
		}

		Arr::set($this->googleEvent, $name, $value);
	}

	/**
	 * @return bool
	 */
	public function exists(): bool
	{
		return $this->id != '';
	}

	/**
	 * @return bool
	 */
	public function isAllDayEvent(): bool
	{
		return is_null($this->googleEvent['start']['dateTime']);
	}

	/**
	 * @param string|null $method
	 * @param array $optParams
	 * @return $this
	 * @throws Exception
	 * @throws GoogleClientInvalidConfiguration
	 */
	public function save(string $method = null, array $optParams = []): self
	{
		$method = $method ?? ($this->exists() ? 'updateEvent' : 'insertEvent');

		$googleCalendar = $this->getGoogleCalendar($this->calendarId);

		#Try to create a Google Meet and attach it to event

		/* $solution_key = new Google_Service_Calendar_ConferenceSolutionKey();
		$solution_key->setType("hangoutsMeet");
		$confrequest = new Google_Service_Calendar_CreateConferenceRequest();
		$confrequest->setRequestId("3whatisup3");
		$confrequest->setConferenceSolutionKey($solution_key);
		$confdata = new Google_Service_Calendar_ConferenceData();
		$confdata->setCreateRequest($confrequest);
		$this->googleEvent->setConferenceData($confdata);*/

		/*$conference = new \Google_Service_Calendar_ConferenceData();
		$conferenceRequest = new \Google_Service_Calendar_CreateConferenceRequest();
		$conferenceRequest->setRequestId(Str::uuid());
		$conference->setCreateRequest($conferenceRequest);
		$this->googleEvent->setConferenceData($conference);*/

		$googleEvent = $googleCalendar->$method($this, $optParams);
//dd($googleEvent);
		return static::createFromGoogleCalendarEvent($googleEvent, $googleCalendar->getCalendarId());
	}

	/**
	 * @param string $text
	 * @return $this
	 * @throws Exception
	 * @throws GoogleClientInvalidConfiguration
	 */
	public function quickSave(string $text): self
	{
		$googleCalendar = $this->getGoogleCalendar($this->calendarId);

		$googleEvent = $googleCalendar->insertEventFromText($text);

		return static::createFromGoogleCalendarEvent($googleEvent, $googleCalendar->getCalendarId());
	}

	/**
	 * @param array $attributes
	 * @param array $optParams
	 * @return $this
	 */
	public function update(array $attributes, $optParams = []): self
	{
		foreach ($attributes as $name => $value) {
			$this->$name = $value;
		}

		return $this->save('updateEvent', $optParams);
	}

	/**
	 * @param string|null $eventId
	 * @param array $optParams
	 */
	public function delete(string $eventId = null, array $optParams = [])
	{
		try {
			$this->getGoogleCalendar($this->calendarId)->deleteEvent($eventId ?? $this->id, $optParams);
		} catch (GoogleClientInvalidConfiguration | Exception $e) {
		}
	}

	/**
	 * @param array $attendee
	 */
	public function addAttendee(array $attendee)
	{
		$this->attendees[] = new Google_Service_Calendar_EventAttendee([
			'email' => $attendee['email'],
			'comment' => $attendee['comment'] ?? null,
			'displayName' => $attendee['name'] ?? null,
		]);

		$this->googleEvent->setAttendees($this->attendees);
	}

	/**
	 * @return string
	 */
	public function getSortDate(): string
	{
		if ($this->startDate) {
			return $this->startDate;
		}

		if ($this->startDateTime) {
			return $this->startDateTime;
		}

		return '';
	}

	public function getCalendarId(): string
	{
		return $this->calendarId;
	}

	/**
	 * @param string|null $calendarId
	 * @return GoogleCalendar
	 * @throws GoogleClientInvalidConfiguration
	 * @throws Exception
	 */
	protected static function getGoogleCalendar(string $calendarId = null): GoogleCalendar
	{
		$calendarId = $calendarId ?? config('services.google.calendar_id');

		return new GoogleCalendar($calendarId);
	}

	/**
	 * @param string $name
	 * @param CarbonInterface $date
	 */
	protected function setDateProperty(string $name, CarbonInterface $date)
	{
		$eventDateTime = new Google_Service_Calendar_EventDateTime;

		if (in_array($name, ['start.date', 'end.date'])) {
			$eventDateTime->setDate($date->format('Y-m-d'));
			$eventDateTime->setTimezone($date->getTimezone());
		}

		if (in_array($name, ['start.dateTime', 'end.dateTime'])) {
			$eventDateTime->setDateTime($date->format(DateTimeInterface::RFC3339));
			$eventDateTime->setTimezone($date->getTimezone());
		}

		if (Str::startsWith($name, 'start')) {
			$this->googleEvent->setStart($eventDateTime);
		}

		if (Str::startsWith($name, 'end')) {
			$this->googleEvent->setEnd($eventDateTime);
		}
	}

	/**
	 * @param array $value
	 */
	protected function setSourceProperty(array $value)
	{
		$source = new Google_Service_Calendar_EventSource([
			'title' => $value['title'],
			'url' => $value['url'],
		]);

		$this->googleEvent->setSource($source);
	}

	/**
	 * @param string $name
	 * @return string
	 */
	protected function getFieldName(string $name): string
	{
		return [
				'name' => 'summary',
				'startDate' => 'start.date',
				'endDate' => 'end.date',
				'startDateTime' => 'start.dateTime',
				'endDateTime' => 'end.dateTime',
			][$name] ?? $name;
	}

	/**
	 * converts carbon object to an array with 'dateTime' and 'timeZone' properties
	 *
	 * @param Carbon $carbon
	 * @return array
	 */
	private static function carbonToArray(Carbon $carbon): array
	{
		return [
			'dateTime' => $carbon->toDateTimeLocalString(),
			'timeZone' => $carbon->getTimezone()->getName()
		];
	}

	/**
	 * @param string $name
	 * @param Carbon $start
	 * @param Carbon $end
	 * @param RRule $rrule
	 * @param array $optParams
	 * @return mixed
	 * @throws Exception
	 * @throws GoogleClientInvalidConfiguration
	 */
	public static function createRecurringEvent(string $name, Carbon $start, Carbon $end, RRule $rrule, array $optParams = []): mixed
	{
		$newEvent = [
			'summary' => $name,
			'start' => static::carbonToArray($start),
			'end' => static::carbonToArray($end),
			'recurrence' => ['RRULE:' . $rrule->rfcString()]
		];

		foreach ($optParams as $key => $value) {
			$newEvent[$key] = $value;
		}

		return static::create($newEvent);
	}
}
