FROM alpine:latest

COPY docker-entrypoint.sh /bin/docker-entrypoint.sh
RUN chmod +x /bin/docker-entrypoint.sh
RUN apk add --update rsync dos2unix
RUN dos2unix /bin/docker-entrypoint.sh

## cleanup
RUN apk del dos2unix && rm -rf /var/cache/apk/*

## start container
ENTRYPOINT ["/bin/docker-entrypoint.sh"]