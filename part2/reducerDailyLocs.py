#!/usr/bin/env python
from operator import itemgetter
from decimal import Decimal
import sys

curr_count = 0
curr_day = None
curr_tag = None
curr_loc = None
tag = None
day = None

for line in sys.stdin:
    line = line.strip()

    if len(line) > 3:
        day, tag, count, loc = line.split(',', 3)
    else:
        continue

    try:
        count = int(count)
    except ValueError:
        continue

    if curr_day == day and curr_tag == tag:
        curr_count += count
        curr_loc = curr_loc + "," + loc
    else:
        if curr_tag:
            print "%s,%s,%s,%s" % (curr_day, curr_tag, curr_count, curr_loc)
        curr_count = count
        curr_day = day
        curr_tag = tag
        curr_loc = loc

if curr_tag == tag:
    print "%s,%s,%s,%s" % (curr_day, curr_tag, curr_count, curr_loc)
