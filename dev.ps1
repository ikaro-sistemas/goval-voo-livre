# INICIADOR DE AMBIENTE: GOVAL VOO LIVRE
Write-Host "===============================================" -ForegroundColor Cyan
Write-Host "   Iniciando Ambiente de Desenvolvimento..." -ForegroundColor Cyan
Write-Host "===============================================" -ForegroundColor Cyan

# 1. Dá a partida no Docker
Write-Host "[1/3] Ligando as máquinas do Docker (Banco e WordPress)..." -ForegroundColor Yellow
docker compose up -d

# Aguarda um segundinho para o WP carregar
Start-Sleep -Seconds 3

# 2. Abre o projeto no navegador principal
Write-Host "[2/3] Abrindo o seu Localhost..." -ForegroundColor Yellow
Start-Process "http://localhost:8085"

# 3. Abre o Notion
Write-Host "[3/3] Abrindo o seu painel de produtividade no Notion..." -ForegroundColor Yellow
Start-Process "https://notion.so"

Write-Host "===============================================" -ForegroundColor Green
Write-Host " TUDO PRONTO! Bora codar!" -ForegroundColor Green
Write-Host "===============================================" -ForegroundColor Green
Start-Sleep -Seconds 3
