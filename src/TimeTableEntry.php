<?php

namespace UclAttendance\TimetableParser;



class TimeTableEntry {

	/**
	 * The day of the entry
	 *
	 * @var \Carbon\Carbon
	 */
	protected $date;

	/**
	 * 4 digit course code
	 *
	 * @example 2222
	 * @var int
	 */
	protected $courseCode;

	/**
	 * Course title
	 *
	 * @var string
	 */
	protected $courseTitle;

	/**
	 * Time of the beginning of the entry
	 * @var \Carbon\Carbon
	 */
	protected $start;

	/**
	 * Time of the end of the entry
	 * @var \Carbon\Carbon
	 */
	protected $end;

	/**
	 * Type of the entry
	 *
	 * @example 'Lecture'
	 * @var string|null
	 */
	protected $type;

	/**
	 * Mostly empty for lectures
	 *
	 * @example 'Problem Tutorial 7'
	 * @var string|null
	 */
	protected $group;

	/**
	 * @var string|null
	 */
	protected $lecturer;

	/**
	 * @var string|null
	 */
	protected $room;



	public function __construct($params){
		foreach($params as $key => $value){
			if(property_exists($this, $key)){
				$this->{$key} = $value;
			}
		}
	}

	public function __get($key){
		return $this->{$key};
	}
}