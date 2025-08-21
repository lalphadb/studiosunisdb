#!/bin/bash

# Service Manager pour StudiosDB
# ===============================

PROJECT_DIR="/home/studiosdb/studiosunisdb"
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# PIDs files
LARAVEL_PID="/tmp/laravel.pid"
VITE_PID="/tmp/vite.pid"

function start_laravel() {
    echo -e "${BLUE}Starting Laravel...${NC}"
    
    # Check if already running
    if [ -f "$LARAVEL_PID" ] && kill -0 $(cat "$LARAVEL_PID") 2>/dev/null; then
        echo -e "${YELLOW}Laravel already running with PID $(cat $LARAVEL_PID)${NC}"
        return
    fi
    
    cd $PROJECT_DIR
    nohup php artisan serve --host=127.0.0.1 --port=8001 > /tmp/laravel.log 2>&1 &
    echo $! > $LARAVEL_PID
    
    sleep 2
    if kill -0 $(cat "$LARAVEL_PID") 2>/dev/null; then
        echo -e "${GREEN}✅ Laravel started on http://127.0.0.1:8001${NC}"
    else
        echo -e "${RED}❌ Failed to start Laravel${NC}"
        rm -f $LARAVEL_PID
    fi
}

function stop_laravel() {
    echo -e "${BLUE}Stopping Laravel...${NC}"
    
    if [ -f "$LARAVEL_PID" ]; then
        if kill -0 $(cat "$LARAVEL_PID") 2>/dev/null; then
            kill $(cat "$LARAVEL_PID")
            rm -f $LARAVEL_PID
            echo -e "${GREEN}✅ Laravel stopped${NC}"
        else
            echo -e "${YELLOW}Laravel not running${NC}"
            rm -f $LARAVEL_PID
        fi
    else
        # Try to find and kill by port
        PID=$(lsof -ti:8001)
        if [ ! -z "$PID" ]; then
            kill $PID
            echo -e "${GREEN}✅ Laravel stopped (found on port 8001)${NC}"
        else
            echo -e "${YELLOW}Laravel not running${NC}"
        fi
    fi
}

function start_vite() {
    echo -e "${BLUE}Starting Vite...${NC}"
    
    # Check if already running
    if [ -f "$VITE_PID" ] && kill -0 $(cat "$VITE_PID") 2>/dev/null; then
        echo -e "${YELLOW}Vite already running with PID $(cat $VITE_PID)${NC}"
        return
    fi
    
    cd $PROJECT_DIR
    nohup npm run dev > /tmp/vite.log 2>&1 &
    echo $! > $VITE_PID
    
    sleep 3
    if kill -0 $(cat "$VITE_PID") 2>/dev/null; then
        echo -e "${GREEN}✅ Vite started on http://127.0.0.1:5173${NC}"
    else
        echo -e "${RED}❌ Failed to start Vite${NC}"
        rm -f $VITE_PID
    fi
}

function stop_vite() {
    echo -e "${BLUE}Stopping Vite...${NC}"
    
    # Kill all vite processes
    pkill -f "vite" 2>/dev/null
    rm -f $VITE_PID
    echo -e "${GREEN}✅ Vite stopped${NC}"
}

function status() {
    echo -e "${BLUE}═══════════════════════════════════════${NC}"
    echo -e "${BLUE}   SERVICE STATUS${NC}"
    echo -e "${BLUE}═══════════════════════════════════════${NC}"
    
    # Laravel
    if [ -f "$LARAVEL_PID" ] && kill -0 $(cat "$LARAVEL_PID") 2>/dev/null; then
        echo -e "${GREEN}✅${NC} Laravel: Running (PID: $(cat $LARAVEL_PID))"
    else
        PID=$(lsof -ti:8001 2>/dev/null)
        if [ ! -z "$PID" ]; then
            echo -e "${GREEN}✅${NC} Laravel: Running on port 8001 (PID: $PID)"
        else
            echo -e "${RED}❌${NC} Laravel: Not running"
        fi
    fi
    
    # Vite
    VITE_PROCESS=$(ps aux | grep -E "node.*vite" | grep -v grep | head -1)
    if [ ! -z "$VITE_PROCESS" ]; then
        VITE_PID_ACTUAL=$(echo "$VITE_PROCESS" | awk '{print $2}')
        echo -e "${GREEN}✅${NC} Vite: Running (PID: $VITE_PID_ACTUAL)"
    else
        echo -e "${RED}❌${NC} Vite: Not running"
    fi
    
    # MySQL
    if systemctl is-active --quiet mysql; then
        echo -e "${GREEN}✅${NC} MySQL: Running"
    else
        echo -e "${RED}❌${NC} MySQL: Not running"
    fi
    
    # Nginx
    if systemctl is-active --quiet nginx; then
        echo -e "${GREEN}✅${NC} Nginx: Running"
    else
        echo -e "${RED}❌${NC} Nginx: Not running"
    fi
    
    # PHP-FPM
    if systemctl is-active --quiet php8.3-fpm; then
        echo -e "${GREEN}✅${NC} PHP-FPM: Running"
    else
        echo -e "${RED}❌${NC} PHP-FPM: Not running"
    fi
}

function restart_all() {
    echo -e "${BLUE}Restarting all services...${NC}"
    stop_laravel
    stop_vite
    sleep 2
    start_laravel
    start_vite
}

function logs() {
    echo -e "${BLUE}═══════════════════════════════════════${NC}"
    echo -e "${BLUE}   SERVICE LOGS${NC}"
    echo -e "${BLUE}═══════════════════════════════════════${NC}"
    
    echo -e "\n${YELLOW}Laravel logs:${NC}"
    if [ -f "/tmp/laravel.log" ]; then
        tail -10 /tmp/laravel.log
    else
        echo "No Laravel logs found"
    fi
    
    echo -e "\n${YELLOW}Vite logs:${NC}"
    if [ -f "/tmp/vite.log" ]; then
        tail -10 /tmp/vite.log
    else
        echo "No Vite logs found"
    fi
}

# Menu principal
case "${1:-help}" in
    start)
        case "${2:-all}" in
            laravel)
                start_laravel
                ;;
            vite)
                start_vite
                ;;
            all)
                start_laravel
                start_vite
                ;;
            *)
                echo "Usage: $0 start [laravel|vite|all]"
                ;;
        esac
        ;;
    stop)
        case "${2:-all}" in
            laravel)
                stop_laravel
                ;;
            vite)
                stop_vite
                ;;
            all)
                stop_laravel
                stop_vite
                ;;
            *)
                echo "Usage: $0 stop [laravel|vite|all]"
                ;;
        esac
        ;;
    restart)
        restart_all
        ;;
    status)
        status
        ;;
    logs)
        logs
        ;;
    *)
        echo -e "${BLUE}═══════════════════════════════════════${NC}"
        echo -e "${BLUE}   SERVICE MANAGER${NC}"
        echo -e "${BLUE}═══════════════════════════════════════${NC}"
        echo ""
        echo "Usage: $0 [command] [service]"
        echo ""
        echo "Commands:"
        echo "  start [laravel|vite|all]  - Start services"
        echo "  stop [laravel|vite|all]   - Stop services"
        echo "  restart                   - Restart all services"
        echo "  status                    - Show service status"
        echo "  logs                      - Show service logs"
        echo ""
        echo "Examples:"
        echo "  $0 start all"
        echo "  $0 stop laravel"
        echo "  $0 status"
        ;;
esac
