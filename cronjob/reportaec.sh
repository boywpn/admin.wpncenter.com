#!/bin/sh
(sleep 0 && wget http://report.hiwpn.com/games/report/bet-items/aec_tmp --delete-after >/dev/null 2>&1) &
(sleep 30 && wget http://report.hiwpn.com/games/report/bet-items/aec_save --delete-after >/dev/null 2>&1) &