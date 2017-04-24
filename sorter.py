#!/usr/bin/env python3

# sorter quiz
# Copyright (C) 2017 Chris Hendrickson

# GPLV3 License
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

class Unit:
  def __init__(self, unit_number: str, tenant: str):
    self.unit_number = unit_number
    self.tenant = tenant

  @property
  def unit_number(self):
    return self._unit_number

  @unit_number.setter
  def unit_number(self, value: str):
    self._unit_number = value

  @property
  def tenant(self):
    return self._tenant

  @tenant.setter
  def tenant(self, value: str):
    self._tenant = value

  @property
  def display_string(self):
    return "%s - %s" % (self.unit_number, self.tenant)

  def __lt__(self, other):
    a_value = Unit._sanitize_unit_number(self.unit_number)
    b_value = Unit._sanitize_unit_number(other.unit_number)

    compare_value = a_value - b_value;

    if compare_value == 0:
      if self.unit_number < other.unit_number:
        compare_value = -1
      elif self.unit_number > other.unit_number:
        compare_value = 1
      else:
        compare_value = 0

    return compare_value < 0

  @staticmethod
  def _sanitize_unit_number(value: str):
    return int(''.join(x for x in value if x.isdigit()))

class UnitParser:
  @staticmethod
  def parse(filename: str, delimeter: str):
    data = []
    with open(filename, 'r') as file:
      for line in file:
        line = line.strip()
         
        if line == "":
          continue

        row = line.split(delimeter)
        unit = row[0].strip()
        tenant = row[1].strip()
        data.append(Unit(unit, tenant))

    return sorted(data)

def main():
  for unit in UnitParser.parse("units.txt", " - "):
    print(unit.display_string);

if __name__ == '__main__':
  main()