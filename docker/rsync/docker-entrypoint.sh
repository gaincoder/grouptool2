#!/bin/sh
set -e
echo "Syncing vendor directory..."
rsync -a -u --stats -h /app-vendor/ /var/www/vendor