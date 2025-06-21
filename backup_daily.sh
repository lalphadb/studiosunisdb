#!/bin/bash
# Sauvegarde quotidienne StudiosUnisDB
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u root -pLkmP0km1 studiosdb > /home/studiosdb/backups/studiosdb_$DATE.sql
# Garder 7 jours
find /home/studiosdb/backups/ -name "*.sql" -mtime +7 -delete
