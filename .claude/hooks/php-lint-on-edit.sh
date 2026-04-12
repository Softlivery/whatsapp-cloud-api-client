#!/bin/bash
file_path=$(python3 -c "import sys,json; d=json.load(sys.stdin); print(d.get('tool_input',{}).get('file_path',''))" 2>/dev/null <<< "$(cat)")
[[ "$file_path" == *.php ]] || exit 0
[[ "$file_path" == src/* || "$file_path" == tests/* ]] || exit 0
php -l "$file_path" 2>&1
