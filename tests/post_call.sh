#!/usr/bin/env bash

if [ ! -f "./.env" ]; then
    echo "Missing .env file."

    exit;
fi

source "./.env"

echo "Testing with the wrong password..."
curl --request POST --url "$PROXY_URL" -u "WRONG_PASSWORD:" -H 'Content-Type: application/json' -H "Accept: application/json" -d '{ "links": { "share_url": { "href": "http://test/" } } }'

echo "Testing with the correct password..."
curl --request POST --url "$PROXY_URL" -u "$PASSED_API_TOKEN:" -H 'Content-Type: application/json' -H "Accept: application/json" -d '{ "links": { "share_url": { "href": "http://test/" } } }'
