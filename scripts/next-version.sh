#!/bin/bash

VERSION_FILE="VERSION"

if [[ ! -f "$VERSION_FILE" ]]; then
  echo "0.0.0" > "$VERSION_FILE"
fi

VERSION=$(cat "$VERSION_FILE")
IFS='.' read -r -a parts <<< "$VERSION"

# Ensure we have 3 version parts
while [ "${#parts[@]}" -lt 3 ]; do
  parts+=("0")
done

# Increment PATCH version
parts[2]=$((parts[2]+1))

NEW_VERSION="${parts[0]}.${parts[1]}.${parts[2]}"

# Output version and write to file
echo "$NEW_VERSION" > "$VERSION_FILE"
echo "$NEW_VERSION"
