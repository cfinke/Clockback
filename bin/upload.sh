#!/bin/bash
set -e

function usage {
	echo "Usage: ./upload.sh -p /path/to/photos -t timezone -u upload_path"
	echo "  You may specify multiple photo directories by including multiple '-p /path/to/photo/dir' pairs."
	echo "  The -u option's value should be valid as an rsync target. Example: example.com:~/photos/"
	echo "  The -t timezone string should be a timezone identifier recognized by the date command."
}

IFS=$'\n'

timezone="UTC"
photo_dirs=()
upload_path=""

while getopts "t:p:u:" opt; do
    case "$opt" in
    t)
        timezone="$OPTARG"
        ;;
    p)  photo_dirs+=("$OPTARG")
        ;;
    u)  upload_path="$OPTARG"
        ;;
    esac
done

shift "$((OPTIND-1))"

if [ ${#photo_dirs[@]} -eq 0 ]; then
    echo "Error: You must specify at least one photo directory."
	echo ""
	usage
	exit 1
fi

if [ "$upload_path" = "" ]; then
	echo "Error: You must supply an upload path."
	echo ""
	usage
	exit 1
fi

temp_dir=`mktemp -d 2>/dev/null || mktemp -d -t 'clockback'`

for i in `seq 0 6`;
do
	DATE=$(TZ="$timezone" date -v+${i}d '+%m-%d' )

	for photo_dir in $photo_dirs;
	do
		for f in `find "$photo_dir" -name "*" -type f | grep "/....-$DATE *" | grep -v /thumbnails/`;
		do
			cp "$f" "$temp_dir/"
		done
	done
done

for f in `find "$temp_dir" -type f`;
do
	sips -Z 500 "$f" --out "$temp_dir/.resizing" > /dev/null 2> /dev/null
	mv "$temp_dir/.resizing" "$f"
done

chmod -R 0755 "$temp_dir"
rsync -avz --delete "$temp_dir/" "$upload_path" > /dev/null
rm -rf "$temp_dir"
