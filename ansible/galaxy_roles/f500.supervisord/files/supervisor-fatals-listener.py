#!/usr/bin/env python
import socket
import sys
import syslog

from supervisor import childutils

def payloaddata(payload):
  return dict([ x.split(':') for x in payload.split() ])

def main():
  syslog.openlog(logoption=syslog.LOG_PID, facility=syslog.LOG_USER)
  syslog.syslog(syslog.LOG_INFO, 'Monitoring supervisor FATALS')
  hostname = socket.gethostname()

  while True:
    headers, payload = childutils.listener.wait()
    payload = payloaddata(payload)

    processname = payload['processname']
    if (payload['groupname'] != payload['processname']):
      processname = payload['groupname'] + '.' + processname

    syslog.syslog(syslog.LOG_CRIT, '[SUPERVISOR] Process entered FATAL state: ' + processname)

    childutils.listener.ok()

if __name__ == '__main__':
  main()
