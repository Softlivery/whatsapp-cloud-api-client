#!/bin/bash
command=$(python3 -c "import sys,json; d=json.load(sys.stdin); print(d.get('tool_input',{}).get('command',''))" 2>/dev/null <<< "$(cat)")
if echo "$command" | grep -qE "git push.*(origin\s+)?(main|master)"; then
  echo "Blocked: pushing to main requires explicit user confirmation. Ask the user first."
  exit 2
fi
exit 0
