FROM ubuntu:18.04

ENV TZ=Europe/Minsk
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

## Install tools
RUN apt-get update --fix-missing \
    && apt-get upgrade -y \
    && apt-get dist-upgrade -y \
    && apt-get install -y software-properties-common curl language-pack-en-base

# Add nginx mainline Repository
RUN add-apt-repository ppa:nginx/development

RUN apt-get update && apt-get -y upgrade

RUN apt-get install -y ca-certificates ssl-cert nginx

COPY config/api.boty.dev /etc/nginx/sites-available/
COPY config/www.boty.dev /etc/nginx/sites-available/

RUN make-ssl-cert generate-default-snakeoil --force-overwrite

RUN ln -s /etc/nginx/sites-available/api.boty.dev /etc/nginx/sites-enabled/api.boty.dev
RUN ln -s /etc/nginx/sites-available/www.boty.dev /etc/nginx/sites-enabled/www.boty.dev
RUN rm /etc/nginx/sites-enabled/default

RUN usermod -u 1000 www-data

EXPOSE 80
EXPOSE 443
EXPOSE 8080

CMD ["nginx", "-g", "daemon off;"]