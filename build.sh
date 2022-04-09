#!/usr/bin/env bash

source ./config.sh

docker build -t ${FULL_NAME}:${VERSION} .
