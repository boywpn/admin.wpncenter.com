#!/bin/sh

#id > 1987043

(sleep 0 && wget http://report.hiwpn.com/games/report/bet-items/sboapi?portfolio=SportsBook --delete-after >/dev/null 2>&1) & ## SportsBook
(sleep 30 && wget http://report.hiwpn.com/games/report/bet-items/sboapi?portfolio=SportsBook --delete-after >/dev/null 2>&1) & ## SportsBook

(sleep 0 && wget http://report.hiwpn.com/games/report/bet-items/sboapi?portfolio=Casino --delete-after >/dev/null 2>&1) & ## Casino
(sleep 0 && wget http://report.hiwpn.com/games/report/bet-items/sboapi?portfolio=Games --delete-after >/dev/null 2>&1) & ## Games
(sleep 0 && wget http://report.hiwpn.com/games/report/bet-items/sboapi?portfolio=VirtualSports --delete-after >/dev/null 2>&1) & ## VirtualSports
(sleep 0 && wget http://report.hiwpn.com/games/report/bet-items/sboapi?portfolio=SeamlessGame --delete-after >/dev/null 2>&1) & ## SeamlessGame

(sleep 15 && wget http://report.hiwpn.com/games/report/bet-items/sboapi_save --delete-after >/dev/null 2>&1) & ## Save to BetList
#(sleep 25 && wget http://admin.hiwpn.com/games/report/bet-items/sboapi_save --delete-after >/dev/null 2>&1) & ## Save to BetList
#(sleep 40 && wget http://admin.hiwpn.com/games/report/bet-items/sboapi_save --delete-after >/dev/null 2>&1) & ## Save to BetList
#(sleep 45 && wget http://report.hiwpn.com/games/report/bet-items/sboapi_save --delete-after >/dev/null 2>&1) & ## Save to BetList
#(sleep 55 && wget http://admin.hiwpn.com/games/report/bet-items/sboapi_save --delete-after >/dev/null 2>&1) & ## Save to BetList