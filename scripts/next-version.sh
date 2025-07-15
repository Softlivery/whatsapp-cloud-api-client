#!/bin/bash
LATEST_TAG=$(git tag --list 'v*' --sort=-creatordate | head -n 1)
VERSION=${LATEST_TAG#v}

IFS='.' read -r -a parts <<< "$VERSION"
parts[2]=$((parts[2]+1))
echo "${parts[0]}.${parts[1]}.${parts[2]}"
