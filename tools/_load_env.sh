#!/usr/bin/env bash
set -euo pipefail
ENV_FILE="${1:-.env}"
# Charge uniquement les clés DB_ en ignorant les commentaires
while IFS='=' read -r k v; do
  [[ "$k" =~ ^DB_(HOST|PORT|DATABASE|USERNAME|PASSWORD)$ ]] || continue
  v="${v%\"}"; v="${v#\"}"; v="${v%\'}"; v="${v#\'}"
  export "$k"="$v"
done < <(grep -E '^(DB_HOST|DB_PORT|DB_DATABASE|DB_USERNAME|DB_PASSWORD)=' "$ENV_FILE" | grep -v '^\s*#')
: "${DB_HOST:=127.0.0.1}" ; : "${DB_PORT:=3306}"
