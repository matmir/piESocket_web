#!/bin/sh

# Path to the server application logs directory
PTH=$1logs/
LOGS="mainProg parser process socket taglogger alarming script scriptOutput driver cmd"

for LOG in $LOGS; do

	# One module
	FL=$PTH$LOG

	cd $FL

	ENN=$(ls *.log);
	for entry in $ENN; do

	    US=$(lsof $entry | grep -c -i $entry);
	    
	    if [ $US -eq 0 ]
		then
			rm $entry
		fi

	done

done