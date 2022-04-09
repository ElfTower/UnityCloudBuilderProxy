#!/usr/bin/env bash

source ./config.sh

docker push ${FULL_NAME}:${VERSION}
