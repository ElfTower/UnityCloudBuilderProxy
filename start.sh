#!/usr/bin/env bash

PORT=${1:-"8080"}
VERSION="$(cat ./VERSION)"
VOLUME="-v /mnt/hgfs/dogmage/data/editing/projects/UnityCloudBuilderProxy/src:/var/www/html"

if [ ! -f "./.env" ]; then
    echo "Missing .env file."

    exit;
fi

source ./config.sh
source ./.env

echo "Exposing port $PORT..."

docker run -d -p ${PORT}:80 --restart unless-stopped $VOLUME \
    -e "PASSED_API_TOKEN=$PASSED_API_TOKEN" \
    -e "PROXY_API_TOKEN=$PROXY_API_TOKEN" \
    -e "PROXY_VCS=$PROXY_VCS" \
    -e "PROXY_ORG=$PROXY_ORG" \
    -e "PROXY_REPO=$PROXY_REPO" \
    -e "PROXY_BRANCH=$PROXY_BRANCH" \
    --name dogmage_runeguard_tools_unity_proxy \
    ${FULL_NAME}:${VERSION}
