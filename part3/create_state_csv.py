#!/usr/bin/env python
import sys
import csv
import json
import re

from collections import defaultdict

def extract_tags(s):
    return set([re.sub(r"(\W+)$", "", j)[1:] for j in set([i for i in s.split() if i.startswith("#")])])

def extract_users(s):
    return set(part[1:] for part in s.split() if part.startswith('@'))

i = 0
states = dict()
d_tags = defaultdict(int)
d_pols = defaultdict(int)
with open("state_latlon.csv", "r") as src:
    next(src)
    for line in src:
        line = line.strip()
        line = line.split(",")
        #print "%s\t%s\t%s" % (line[0], line[1], line[2])
        states[line[0]] = line[1] + "_" + line[2]

i = 0       
with open("total_pol.txt", "r") as src:
    #with open("total_pol.csv", "w") as dest:
        #dest.write("\"hashtag\",\"pol\",\"lat\",\"lon\"\n")
    for line in src:
        line = line.strip()
        line = line.split("\t")
        if len(line) == 12 and line[4] == "en" and line[8] == "None":
            tweet = line[5].replace(",", "")
            tags = extract_tags(tweet)
            state = line[7]
            state = state[-2:]
            #sys.exit(0)
            pol = line[10]
        else:
            continue

        #print tags
        for tag in tags:
            tag = tag.lower().strip()
            if tag:
                #dest.write("%s;%s;%s;%s\n" % (tag, pol, lat, lon))
                #print "current tag: %s\n" % tag
                #d[tag].append("%s;%s;%s" % (pol, lat, lon))
                d_tags[tag] += 1
                if state in states:
                    if float(pol) > 0:
                        d_pols[tag + "_" + state + "_pos"] += 1
                    elif float(pol) == 0:
                        d_pols[tag + "_" + state + "_neu"] += 1
                    elif float(pol) < 0:
                        d_pols[tag + "_" + state + "_neg"] += 1
                else:
                    continue
        i += 1
        #if i > 100:
        #    break
#print d

with open("total_states_pol.csv", "w") as dest:
    for tag in d_tags:
        dest.write("%s;" % (tag))
        for state in states:
            dest.write("%s_" % (state))
            dest.write("%s_" % (d_pols[tag + "_" + state + "_pos"]))
            dest.write("%s_" % (d_pols[tag + "_" + state + "_neu"]))
            dest.write("%s"  % (d_pols[tag + "_" + state + "_neg"]))
            dest.write(";")
        dest.write("\n")
        #print "%s,%s" % (key, d[key])
