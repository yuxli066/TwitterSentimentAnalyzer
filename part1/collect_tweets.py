import sys, traceback
import time, datetime
import tweepy

consumer_key = ""
consumer_secret = ""
access_token = ""
access_secret = ""

auth = tweepy.OAuthHandler(consumer_key, consumer_secret)
auth.set_access_token(access_token, access_secret)
api = tweepy.API(auth)

tweets = ""
i = 0

class CustomStreamListener(tweepy.StreamListener):
    def on_status(self, status):
        global tweets
        global i
        try:
            tweets += "%d;;;;;%s;;;;;%s;;;;;%s;;;;;%s;;;;;%s;;;;;%s;;;;;%s;;;;;%s;;;;;%s\n" % (i, status.created_at, status.author.screen_name, status.user.followers_count, status.user.lang, status.text.replace("\n", ""), status.user.location, status.place.full_name, status.coordinates,  status.source)
            i += 1
        except:
            pass
        if i % 1000 == 0:
            f = open("./tweets/" + str(i), "a")
            f.write(tweets.encode("utf-8"))
            print datetime.datetime.fromtimestamp(time.time()).strftime("%m-%d-%Y %H:%M:%S"), " Wrote tweets to file: ", f
            f.close()
            tweets = ""

    def on_error(self, status_code):
        print datetime.datetime.fromtimestamp(time.time()).strftime("%m-%d-%Y %H:%M:%S"), " Error with status code:", status_code
        return True

    def on_timeout(self):
        print datetime.datetime.fromtimestamp(time.time()).strftime("%m-%d-%Y %H:%M:%S"), " Timeout..."
        return True

def start_stream():
    while True:
        try:
            sapi = tweepy.streaming.Stream(auth, CustomStreamListener())
            #Filter by 4 different regions of United States for less overlap with geo coordiantes of bounding box
            sapi.filter(locations=[-124.78,32.54,-114.05,49.0,-114.05,31.33,-102.33,49.0,-100.13,25.84,-84.24,49.0,-84.23,23.77,-67.32,47.46])#ALL  
            #sapi.filter(locations=[-124.78,32.54,-114.05,49.0])#WEST
            #sapi.filter(locations=[-114.05,31.33,-102.33,49.0])#MOUN
            #sapi.filter(locations=[-100.13,25.84,-84.24,49.0])#CENT
            #sapi.filter(locations=[-84.23,23.77,-67.32,47.46])#EAST
        except KeyboardInterrupt:
            print "\n", datetime.datetime.fromtimestamp(time.time()).strftime("%m-%d-%Y %H:%M:%S"), " Exiting program..."
            sys.exit(0)
        except:
            traceback.print_exc(file=sys.stdout)
            continue

start_stream()