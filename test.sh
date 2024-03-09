#!/bin/bash
test "$1" || { echo -e "Didn't receive data..."; echo "beget_wget ftp://login:pass@host [path]"; echo 'You can wget several dirs in [path]. For example: "beget_wget ftp://login:pass@host/ftp_root ftp_root/{dir1,dir2,dir3}"'; exit 1; }


a=`echo ${@:2} | sed -e 's/ /,/g'`

if [ -z $a ]; then
    nohup wget -crNl0 --restrict-file-names=nocontrol $1 > wget.`date '+%m.%d_%T'`.log &
else
    if [ ${1:(-1)} != "/" ]; then
        b=`echo "$1" | sed -e 's#$#/#'`
        nohup wget -crNl0 --restrict-file-names=nocontrol $b -I $a > wget.`date '+%m.%d_%T'`.log &
    fi
fi
