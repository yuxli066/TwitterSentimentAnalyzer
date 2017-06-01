#!/usr/bin/env python
import sys
reload(sys)
sys.setdefaultencoding('utf8')
sys.path.append("/home/jgome026/textblob/sloria-TextBlob-93d5896/")

from textblob import TextBlob

i = 0
with open("fourth1.txt", "r") as src:
    with open("fourthpol1.txt", "w") as dest:
        for line in src:
            tweet = line.strip()
            tweet = tweet.split("\t")
            if len(tweet) == 10 and tweet[4] == "en":
                tweet = TextBlob(tweet[5])
            else:
                continue

            dest.write("%s\t%s\t%s\n" % (line.strip(), tweet.sentiment.polarity, tweet.sentiment.subjectivity))
