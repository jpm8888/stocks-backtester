#!/usr/bin/env bash
cd public
rm -rf charting_library
cd trading_view_lib
git checkout unstable
git branch
git pull
cp -r charting_library ../
cp -r datafeeds ../
cp -r charting_library ../../resources/js/
echo "everything is done.."
echo "make sure to yarn run !!!"
