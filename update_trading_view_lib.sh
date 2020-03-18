#!/usr/bin/env bash
cd public
git clone https://github.com/tradingview/charting_library.git
mv charting_library trading_view_lib
cd trading_view_lib
git checkout master
git branch
git pull
cp -r charting_library ../
cp -r datafeeds ../
cp -r charting_library ../../resources/js/
