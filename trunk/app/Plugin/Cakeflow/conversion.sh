#!/bin/sh
#
#
# description:  encode un répertoire récursivement dans un encodage de fichier
#
ENCODAGE_DEB="UTF-8"
ENCODAGE_FIN="ISO-8859-1"
DIR="$1"

for i in `ls -R $DIR/*`
do
  IS_BINARY=''
  RESULT=''
  if [  -f $i ] 
  then
    IS_BINARY=$(file --mime "$i" | grep -i binary)
    if [ "$IS_BINARY" = "" ]
    then 
      RESULT=$(file --mime "$i" | grep -i $ENCODAGE_DEB)
      if [ "$RESULT" != "" ]
      then
        echo "$i a ré-encoder"
        mv $i $i.$ENCODAGE_DEB
        iconv -f $ENCODAGE_DEB -t $ENCODAGE_FIN $i.$ENCODAGE_DEB > $i
      fi
    fi
  fi
done
