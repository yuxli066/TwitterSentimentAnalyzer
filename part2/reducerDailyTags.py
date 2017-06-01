#!/usr/bin/env python
from operator import itemgetter
from decimal import Decimal
import sys

curr_count = 0
curr_pol = 0.0
curr_sub = 0.0
curr_day = None
curr_tag = None
tag = None
day = None

for line in sys.stdin:
    line = line.strip()

    if len(line) > 3:
        day, tag, count, pol, sub = line.split(',', 4)
    else:
        continue

    try:
        count = int(count)
        pol = Decimal(pol)
        sub = Decimal(sub)
    except ValueError:
        continue

    if curr_day == day and curr_tag == tag:
        curr_count += count
        curr_pol = Decimal(curr_pol + pol)
        curr_pol = Decimal(curr_pol / 2)
        curr_sub = Decimal(curr_sub + sub)
        curr_sub = Decimal(curr_sub / 2)
    else:
        if curr_day and curr_tag:
            print "%s,%s,%s,%f,%f" % (curr_day, curr_tag, curr_count, curr_pol, curr_sub)
        curr_count = count
        curr_day = day
        curr_tag = tag
        curr_pol = pol
        curr_sub = sub

if curr_tag == tag:
    print "%s,%s,%s,%f,%f" % (curr_day, curr_tag, curr_count, curr_pol, curr_sub)
