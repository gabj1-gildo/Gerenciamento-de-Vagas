# 🎯 PROMPT — Apresentação Interativa: Padrões GoF e Boas Práticas

---

## CONTEXTO E OBJETIVO

Você é um engenheiro de software sênior e designer de interfaces experiente. Sua tarefa é criar uma **apresentação interativa em HTML/CSS/JS puro** (arquivo único, sem dependências externas exceto Google Fonts e CDNs públicas) sobre **Padrões de Projeto GoF (Gang of Four)** e as boas práticas aplicadas no sistema.

A apresentação deve ter a **aparência e experiência de um site moderno**, mas funcionar no **formato de slides navegáveis**. Pense em algo entre um site de produto e uma keynote da Apple — elegante, animado, interativo e memorável.

---

## REQUISITOS VISUAIS E DE EXPERIÊNCIA

### Estética
- Tema **dark** com paleta sofisticada: fundo `#0a0a0f`, tons de `#1a1a2e`, `#16213e`, com acentos em **ciano elétrico** `#00f5d4` e **roxo neon** `#7b2fff`
- Tipografia: use **"Syne"** para títulos (Google Fonts) e **"IBM Plex Mono"** para código/termos técnicos
- Efeito de **glassmorphism** nos cards e painéis
- Partículas ou grid animado de fundo (CSS puro)
- Micro-interações em todos os elementos clicáveis (hover, focus, active)

### Navegação
- Setas laterais (`←` `→`) e teclas do teclado para navegar entre slides
- Barra de progresso animada no topo indicando o slide atual
- Índice flutuante lateral (dots) mostrando a posição no deck
- Contador de slides no canto inferior direito (`03 / 12`)
- Transição entre slides com efeito de **fade + slide suave** (CSS transitions)

---

## ESTRUTURA DOS SLIDES

### SLIDE 1 — Abertura / Hero
- Título impactante: **"Design Patterns GoF"** com efeito de digitação animado (typewriter)
- Subtítulo: *"Como padrões de projeto transformaram a arquitetura do nosso sistema"*
- Partículas flutuando no fundo
- Botão "Iniciar" com animação de pulso

### SLIDE 2 — O Problema: Antes dos Padrões
- Título: **"O Caos Antes da Ordem"**
- Layout em 2 colunas: lado esquerdo mostra código "ruim" (acoplado, duplicado, sem contrato), lado direito mostra os sintomas do problema
- Ícones animados representando: código duplicado 🔁, alto acoplamento ⛓️, difícil manutenção 🔧
- Efeito de "glitch" no código ruim para reforçar a ideia de problema

### SLIDE 3 — O Que São os Padrões GoF
- Título: **"Gang of Four — A Solução"**
- Card central com definição elegante
- 3 categorias em cards animados que aparecem em sequência (stagger):
  - 🏗️ **Criacionais** — Como criar objetos
  - 🧱 **Estruturais** — Como compor estruturas
  - 🔄 **Comportamentais** — Como objetos se comunicam
- Cada card tem hover com flip 3D revelando exemplos de padrões dentro

### SLIDE 4 — 🌟 SLIDE PRINCIPAL: Fluxo de Processo Animado
> **Este é o slide mais importante. Caprichar ao máximo.**

- Título: **"Jornada de uma Requisição pelo Sistema"**
- Animação de um **processo/pacote** (representado por um nó brilhante, ex: bolinha com ícone ⚡) percorrendo um fluxo de etapas da esquerda para a direita
- O fluxo deve conter pelo menos **6 etapas**, cada uma representando um padrão GoF aplicado:
  1. `Entry Point` → **Facade** (simplifica a interface)
  2. `Autenticação` → **Decorator** (adiciona comportamento)
  3. `Roteamento` → **Chain of Responsibility** (encadeia handlers)
  4. `Criação de Serviço` → **Factory Method** (instância correta)
  5. `Execução` → **Strategy** (algoritmo intercambiável)
  6. `Notificação` → **Observer** (eventos desacoplados)
- O nó animado deve **pausar em cada etapa**, exibir um tooltip com nome do padrão + descrição de 1 linha
- Linha de fluxo animada com efeito de traço (stroke-dashoffset)
- Botão para **replay da animação**
- Abaixo do fluxo: legenda interativa — clique em um padrão para destacá-lo no fluxo

### SLIDE 5 — Padrão em Destaque: Factory Method
- Título + badge "Criacional"
- **Diagrama de classes animado** (caixas com bordas aparecendo progressivamente, setas surgindo)
- Antes vs Depois: trecho de pseudo-código comparando criação direta vs Factory
- Metric badge: *"Redução de 60% no acoplamento entre módulos"*

### SLIDE 6 — Padrão em Destaque: Strategy
- Título + badge "Comportamental"
- Animação mostrando **troca de algoritmo em runtime** (3 botões selecionáveis que alteram o comportamento visualizado)
- Exemplo de uso real no sistema
- Vantagens listadas com ícones surgindo com delay escalonado

### SLIDE 7 — Padrão em Destaque: Observer
- Título + badge "Comportamental"
- **Diagrama pub/sub animado**: publisher central emitindo pulsos, observers periféricos recebendo o evento com animação de onda
- Código de exemplo com syntax highlight (use `highlight.js` via CDN)
- Caso de uso real: "Notificações de mudança de estado do pedido"

### SLIDE 8 — Padrão em Destaque: Decorator
- Título + badge "Estrutural"
- Animação de **camadas sendo empilhadas** (ex: objeto base → adiciona logging → adiciona auth → adiciona cache)
- Cada camada aparece como um "wrapper" visual em cima da anterior
- Antes vs Depois com métricas

### SLIDE 9 — Boas Práticas Aplicadas no Sistema
- Título: **"Além dos Padrões: Boas Práticas"**
- Grid de cards (3x2) aparecendo com stagger animation:
  - ✅ SOLID Principles
  - ✅ Injeção de Dependência
  - ✅ Separação de Responsabilidades
  - ✅ Testes Unitários por contrato
  - ✅ Contratos via Interfaces
  - ✅ Documentação viva (código legível)
- Hover em cada card expande e mostra como foi aplicado no sistema

### SLIDE 10 — O Que Mudou: Antes vs Depois
- Título: **"A Transformação"**
- Layout dividido ao meio com linha animada separando os lados
- **ANTES** (esquerda, tema vermelho/quente):
  - Código monolítico
  - Acoplamento alto
  - Difícil de testar
  - Reuso zero
- **DEPOIS** (direita, tema verde/ciano):
  - Módulos desacoplados
  - Padrões aplicados
  - Cobertura de testes alta
  - Reuso e extensibilidade
- Métricas animadas com contador (ex: "Redução de bugs: +67%", "Velocidade de onboarding: +3x")

### SLIDE 11 — Resultados e Vantagens
- Título: **"Resultados Concretos"**
- 4 grandes métricas em cards com animação de contagem numérica (counter animation)
- Gráfico simples (barras ou linha) feito em CSS/SVG mostrando evolução
- Depoimento/quote fictício de um dev do time

### SLIDE 12 — Encerramento
- Título: **"Padrões não são regras — são vocabulário"**
- Quote de um dos autores GoF
- Links para próximos passos (ex: "Explore o código", "Próximos padrões a implementar")
- Animação de partículas final
- Botão "Reiniciar apresentação"

---

## REQUISITOS TÉCNICOS

```
- Arquivo: único HTML com CSS e JS embutidos
- Sem frameworks JS (React, Vue etc.) — apenas vanilla JS
- Google Fonts: Syne + IBM Plex Mono (via @import no CSS)
- CDNs permitidas: highlight.js (syntax highlight de código)
- Animações: CSS keyframes + JS para controle de fluxo
- Responsivo: funcionar bem em telas 1280px+ (foco em apresentação desktop)
- Acessibilidade: navegação por teclado (← →), aria-labels nos elementos interativos
- Performance: animações com will-change e transform/opacity apenas
```

---

## INSTRUÇÕES FINAIS PARA O AGENTE

1. **Comece pelo CSS** — defina todas as variáveis, tipografia e animações base antes de montar os slides
2. **Construa o sistema de slides** — lógica JS de navegação, transições e estado antes do conteúdo
3. **Monte cada slide** em ordem, garantindo que a animação do slide 4 (fluxo) funcione perfeitamente
4. **Revise a coesão visual** — todos os slides devem parecer da mesma "marca"
5. **Teste a navegação** por teclado e por clique antes de entregar
6. **O slide 4 é o coração da apresentação** — dedique pelo menos 40% do esforço de animação nele

> 💡 O resultado deve parecer que foi feito por um designer de produto experiente, não um slide de PowerPoint. Cada detalhe importa.
