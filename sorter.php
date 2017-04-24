<?php

// sorter quiz
// Copyright (C) 2017 Chris Hendrickson

/** GPLV3 License
  * This program is free software: you can redistribute it and/or modify
  * it under the terms of the GNU General Public License as published by
  * the Free Software Foundation, either version 3 of the License, or
  * (at your option) any later version.
  * 
  * This program is distributed in the hope that it will be useful,
  * but WITHOUT ANY WARRANTY; without even the implied warranty of
  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  * GNU General Public License for more details.
  *
  * You should have received a copy of the GNU General Public License
  * along with this program.  If not, see <http://www.gnu.org/licenses/>.
  */

// This code should only run if the script is ran from the commandline
if (!debug_backtrace()) {
	$units = UnitParser::parse("units.txt", " - ");
	foreach($units as $unit) {
		echo $unit->getUnitDisplayString() . PHP_EOL;
	}
}


// Simple data class to encapsulate data
class Unit {
	private $unitNumber;
	private $tenant;

	public function __construct($unitNumber = "", $tenant = "") {
		$this->unitNumber = $unitNumber;
		$this->tenant = $tenant;
	}

	public function getUnitNumber() {
		return $this->unitNumber;
	}

	public function setUnitNumber($value) {
		$this->unitNumber = $value;
	}

	public function getTenantName() {
		return $this->tenant;
	}

	public function setTenantName($value) {
		$this->tenant = $value;
	}

	public function getUnitDisplayString() {
		return $this->unitNumber . " - " . $this->tenant;
	}
}

// Parser class
class UnitParser {

	// Function that returns an array of Units when given a filename and delimiter
	public static function parse($filename, $delimiter) {
		try {
			$file = fopen($filename, 'r');
			$data = [];
			while(!feof($file)) {
				$line = trim(fgets($file));
				
				if($line === "") {
					continue;
				}

				$row = explode($delimiter, $line);
				$unit = trim($row[0], " \t\n\r\0\x0B");
				$tenant = trim($row[1]);
				$data[] = new Unit($unit, $tenant);
			}

			usort($data, array("UnitParser", "sorter"));

			return $data;
		} finally {
			fclose($file);
		}
	}

	// Used to sanitize the apartment number for sorting.
	private static function sanitizeUnitNumber($value) {
		return intval(filter_var($value, FILTER_SANITIZE_NUMBER_INT));
	}

	// Sorter function to compare two unit numbers
	private static function sorter(Unit $a, Unit $b) {
		$aValue = static::sanitizeUnitNumber($a->getUnitNumber());
		$bValue = static::sanitizeUnitNumber($b->getUnitNumber());

		$cmpValue = $aValue - $bValue;

		// If the integer values of the unit numbers are the same, compare the unit numbers as a string.
		if($cmpValue === 0) {
			$cmpValue = strcmp($a->getUnitNumber(), $b->getUnitNumber());
		}

		return $cmpValue;
	}
}
