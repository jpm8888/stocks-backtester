#!/usr/bin/env bash
cd public/trading_view_lib
git checkout unstable
git branch
git pull
cp -r charting_library ../
cp -r datafeeds ../
cp -r charting_library ../../resources/js/
