#!/usr/bin/env bash

BuildDir="./build/"
SvnTrunkDir="../parler/trunk/"
cp -r ${BuildDir} ${SvnTrunkDir}
echo "Done"