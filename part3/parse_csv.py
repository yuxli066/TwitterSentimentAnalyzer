#!/usr/bin/env python
import sys

from cassandra.cluster import Cluster

cluster = Cluster()
session = cluster.connect("csproj")

rows = session.execute("SELECT * FROM tagpol WHERE tag=%s ALLOW FILTERING", (sys.argv[1], ))

for row in rows:
    print "tag: %s, data: %s\n" % (row.tag, row.data)
