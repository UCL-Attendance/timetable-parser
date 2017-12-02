<?php

namespace UclAttendance\TimetableParser;
use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;
use UclAttendance\TimetableParser\Exceptions\HtmlTimetableUnparsable;

class HtmlParser implements Parser{

	/**
	 * Parsed entries
	 *
	 * @var array
	 */
	protected $entries = [];

	public function __construct($html){
		$this->parse($html);
	}

	/**
	 * The actual parser
	 *
	 * @link https://symfony.com/doc/3.4/components/dom_crawler.html
	 * @param $html string
	 * @throws \UclAttendance\TimetableParser\Exceptions\HtmlTimetableUnparsable
	 */
	protected function parse($html){
		$entries = [];
		$crawler = new Crawler($html);

		try{

			$days = $crawler->filter('#mainTimetContainer #listview .listday');
			$days->each(function(Crawler $day) use (&$entries){
				$date = $day->filter('.daydate')->first()->text();
				// https://stackoverflow.com/a/19338932
				$date = preg_replace('~\x{00a0}~u', ' ', trim($date));
				$date = Carbon::createFromFormat('j F Y', trim($date))->startOfDay();

				$events = $day->filter('.dayevts > .dayevt');

				$events->each(function(Crawler $event) use (&$entries, &$date){
					list($start, $end) = explode(' - ', $event->filter('.evttime')->first()->text());
					$start = $date->copy()->setTimeFromTimeString($start);
					$end = $date->copy()->setTimeFromTimeString($end);

					$type = trim($event->filter('.evttype')->first()->text());
					$courseTitle = trim($event->filter('.evtname')->first()->attr('title'));
					$group = trim($event->filter('.evtgroups')->first()->text());
					$lecturer = trim($event->filter('.evtlecturer')->first()->text());
					$room = trim($event->filter('.evtroom')->first()->text());

					// there can be multiple coursecodes eg.: 'PHAS2130, PHAS2440'
					$courseCodes = explode(', ', trim($event->filter('.evtcode')->first()->text()));
					foreach($courseCodes as $courseCode){
						$courseCode = substr($courseCode, -4);

						$entries[] = new TimeTableEntry(compact(
							'date', 'start', 'end', 'type', 'courseTitle',
							'courseCode', 'group', 'lecturer', 'room'
						));
					}

				});


			});
		} catch(\Exception $exception){
			throw new HtmlTimetableUnparsable;
		}

		$this->entries = $entries;
	}

	/**
	 * @return array
	 */
	public function entries(){
		return $this->entries;
	}
}