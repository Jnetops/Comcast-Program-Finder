# Comcast-Program-Finder

Description:
  Laravel application designed to allow users to easily search for currently airing programming on Comcast networks. The goal is for users to be able to easily locate programming that could be broadcasted on multiple channels (some blacked out).

Rovi API:
  This app utilizes RoviCorps API to locate programming across the US (http://developer.rovicorp.com/). This API is not free beyond development stages and would require an API key to utilize.
  
Functionality:
  Allows users to search terms such as channel names or specific shows. Partial names are valid and will be match all that contain the sub-string. Channel searchs return 4 hour block segments of programming and show searchs return only matching.
