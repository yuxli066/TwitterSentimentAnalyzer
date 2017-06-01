#!/usr/bin/env python
import sys
import re

def extract_tags(s):
    return set([re.sub(r"(\W+)$", "", j)[1:] for j in set([i for i in s.split() if i.startswith("#")])])

def extract_users(s):
    return set(part[1:] for part in s.split() if part.startswith('@'))

for line in sys.stdin:
    line = line.strip()
    line = line.split("\t")
    if len(line) == 12 and line[4] == "en":
        day = line[1].split(" ", 1)[0]
        tags = extract_tags(line[5].replace(",", ""))
        pol = line[10]
        sub = line[11]
    else:
        continue
    for tag in tags:
        print "%s,%s,%s,%s,%s" % (day, tag.lower(), 1, pol, sub)
