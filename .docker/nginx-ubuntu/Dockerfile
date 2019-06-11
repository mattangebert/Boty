FROM nginx:1-alpine

RUN apk add --update openssl

COPY files/makecert.sh /usr/local/bin/makecert
RUN chmod +x /usr/local/bin/makecert
RUN makecert boty.local

COPY files/boty.conf /etc/nginx/conf.d/
RUN rm /etc/nginx/conf.d/default.conf