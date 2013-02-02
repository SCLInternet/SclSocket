BasicSocket
===========

A simple wrapper for a reading & writing to sockets. The idea is to provide
an interface which hides away fsockopen(), fread(), fwrite(), etc. so that
code that uses sockets can be tested easily.
