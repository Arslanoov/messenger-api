FROM nginx:1.19-alpine

RUN apk add --no-cache curl

COPY ./dev/nginx/conf.d /etc/nginx/conf.d

WORKDIR /app
