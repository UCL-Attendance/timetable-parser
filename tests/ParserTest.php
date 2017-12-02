<?php

namespace UclAttendance\Tests\TimetableParser;

use PHPUnit\Framework\TestCase;
use UclAttendance\TimetableParser\HtmlParser;
use UclAttendance\TimetableParser\TimeTableEntry;

class ParserTest extends TestCase {

	/** @test */
	public function it_returns_entries_in_expected_format() {
		$rawHtml = file_get_contents(__DIR__ . '/fixtures/CommonTimetable.html');

		$parser = new HtmlParser($rawHtml);

		$entries = $parser->entries();

		$this->assertCount(36, $entries);

		$firstEntry = $entries[0];

		$this->assertInstanceOf(TimeTableEntry::class, $firstEntry);

		$this->assertEquals('2017-11-27', $firstEntry->date->toDateString());
		$this->assertEquals('2222', $firstEntry->courseCode);
		$this->assertEquals('Quantum Physics', $firstEntry->courseTitle);
		$this->assertEquals('2017-11-27 09:00:00', $firstEntry->start->toDateTimeString());
		$this->assertEquals('2017-11-27 10:00:00', $firstEntry->end->toDateTimeString());
		$this->assertEquals('Problem Based Learning', $firstEntry->type);
		$this->assertEquals('Problem Tutorial 9', $firstEntry->group);
		$this->assertEquals('FENTON, Jonathan (Dr)', $firstEntry->lecturer);
		$this->assertEquals('South Quad Pop Up Learning Hub 103', $firstEntry->room);
	}
    
}