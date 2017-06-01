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
d = defaultdict(list)
with open("total_pol.txt", "r") as src:
    #with open("total_pol.csv", "w") as dest:
        #dest.write("\"hashtag\",\"pol\",\"lat\",\"lon\"\n")
    for line in src:
        line = line.strip()
        line = line.split("\t")
        if len(line) == 12 and line[4] == "en" and line[8] != "None":
            tweet = line[5].replace(";", "")
            tags = extract_tags(tweet)
            geo = line[8]
            geo = geo[geo.find("["):geo.find("]") + 1]
            lat = geo[geo.find(",") + 2:geo.find("]")]
            lon = geo[geo.find("[") + 1:geo.find(",")]
            pol = line[10]
        else:
            continue

        #print tags
        for tag in tags:
            tag = tag.lower().strip()
            if tag:
                #dest.write("%s;%s;%s;%s\n" % (tag, pol, lat, lon))
                #print "current tag: %s\n" % tag
                d[tag].append("%s,%s,%s" % (pol, lat, lon))

        i += 1
        #if i > 2:
        #    break
#print d

with open("total_pol.csv", "w") as dest:
    for key in d:
        dest.write("%s;%s\n" % (key, d[key]))
        #print "%s,%s" % (key, d[key])

'''
def extract_tags(s):
    return set([re.sub(r"(\W+)$", "", j)[1:] for j in set([i for i in s.split() if i.startswith("#")])])

def extract_users(s):
    return set(part[1:] for part in s.split() if part.startswith('@'))

i = 0
with open("total_pol.txt", "r") as src:
    with open("total_pol.csv", "w") as dest:
        dest.write("\"hashtag\",\"pol\",\"lat\",\"lon\"\n")
        for line in src:
            line = line.strip()
            line = line.split("\t")
            if len(line) == 12 and line[4] == "en" and line[8] != "None":
                tweet = line[5].replace(";", "")
                tags = extract_tags(tweet)
                geo = line[8]
                geo = geo[geo.find("["):geo.find("]") + 1]
                lat = geo[geo.find(",") + 2:geo.find("]")]
                lon = geo[geo.find("[") + 1:geo.find(",")]
                pol = line[10]
            else:
                continue

            for tag in tags:
                tag = tag.lower().strip()
                if tag:
                    dest.write("%s;%s;%s;%s\n" % (tag, pol, lat, lon))

            #i += 1
            #if i > 2:
            #    break
'''