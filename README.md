# 🦅 Portal Goval - Mundial de Voo Livre

[![WordPress](https://img.shields.io/badge/CMS-WordPress-21759b?style=flat-square&logo=wordpress)](https://wordpress.org)
[![Clean Code](https://img.shields.io/badge/Code_Style-Clean_Code-brightgreen?style=flat-square)](https://en.wikipedia.org/wiki/Solid)
[![WebGL](https://img.shields.io/badge/Engine-MapLibre_GL-007A53?style=flat-square)](https://maplibre.org/)
[![AI Powered](https://img.shields.io/badge/AI-DeepSeek-blue?style=flat-square)](https://deepseek.com)

O **Portal Goval** é uma plataforma de alta performance desenvolvida para o campeonato mundial de voo livre no icônico **Pico da Ibituruna**, em Governador Valadares. O projeto une a robustez do WordPress com tecnologias de ponta em visualização 3D e inteligência artificial.

---

## 🏗️ Arquitetura Clean Code

Recentemente, o projeto passou por uma refatoração estrutural completa para garantir escalabilidade e manutenção simplificada. A lógica foi modularizada seguindo os princípios **SOLID**:

*   **Logic Isolation (`/inc`):** Todo o backend (CPTs, Hooks e Integrações) está separado do design.
*   **Modular Shortcodes:** Componentes visuais complexos são encapsulados em Shortcodes nativos, permitindo uso fluido com **Elementor** ou **Gutenberg**.
*   **Asset Optimization:** CSS e JS são enfileirados de forma independente, otimizando o cache do navegador e a velocidade de carregamento (LCP).
*   **Persistent Data:** Gestão inteligente de cache de mapas integrada ao diretório de uploads do WordPress.

---

## ⚡ Funcionalidades Exploráveis

### 🛰️ Simulador Comparativo 3D
**Uso:** `[goval_3d_map]`
Utiliza o motor **MapLibre GL v3** para renderização de terrenos em 3D real com texturas fotorealistas.
- **Diferencial:** Trajetórias em fita suspensa com extrusão 3D, simulando o voo real no espaço tridimensional.
- **Offline First:** Sistema de Proxy Cache para tiles de satélite e elevação.

### 🏆 Hall de Campeões Interativo
**Uso:** `[goval_champions_tabs]`
Interface premium que agrega dados históricos dos campeonatos.
- Rankings mundiais, brasileiros e locais (Circuito GV).
- Biografias dinâmicas e telemetria de voos históricos integradas.

### 📰 Portal de Notícias "News Force"
**Uso:** `[goval_news_grid]`
Grade de notícias de alto impacto com foco em UX, utilizando design premium e animações de micro-interação.

---

## 🧠 Integração com IA (DeepSeek)

O ambiente está configurado para utilizar o **DeepSeek R1/Chat** como cérebro auxiliar:
- **Agente OpenClaw:** Automação de tarefas e execução de comandos via IA.
- **Antigravity MCP:** Suporte nativo ao desenvolvedor diretamente na IDE para raciocínio lógico e refatoração.

---

## 🚀 Como Rodar o Projeto

1.  Certifique-se de ter o **Docker** e **Docker Compose** instalados.
2.  Clone o repositório e navegue até a pasta raiz.
3.  Inicie os containers:
    ```bash
    docker compose up -d
    ```
4.  Acesse o portal em: `http://localhost:8085`
5.  Acesse o painel do OpenClaw em: `http://localhost:18789`

---

## 🛠️ Stack Tecnológica

- **Backend:** PHP 8+, WordPress REST API.
- **Frontend:** Vanilla CSS (Refined UI), JavaScript ES6+.
- **Maps:** MapLibre GL, Amazon S3 Elevation Tiles, Google Satellite Assets.
- **DevOps:** Docker, WP-CLI, Bash Seeding Scripts.

---
*Este projeto é mantido com os mais altos padrões de higiene de código e documentação técnica.*
