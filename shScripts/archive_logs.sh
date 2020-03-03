#!/bin/sh

# Path to the server application
PTH=$1
ADIR=archive
LDIR=logs

APTH=$PTH$ADIR

# Current date
DT=$(date +%Y-%m-%d_%H-%M-%S)

# Backup file
AFILE=backup_$DT.tar.gz

# Create directory if not exist
if [ ! -d $APTH ]; then
	mkdir $APTH
fi

# Make archive
cd $PTH
tar -czvf $APTH/$AFILE $LDIR
