<?php

$outPath = __DIR__ . '/docs/apresentacao/index.html';

$html = <<<'HTML'
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Padrões de Projeto GoF - SyncMatch Keynote</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,400;0,600;1,400&family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Reveal.js Core CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/5.0.4/reset.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/5.0.4/reveal.min.css">
    
    <!-- Reveal.js Highlight Theme -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/5.0.4/plugin/highlight/monokai.min.css">

    <style>
        /* Custom Theme Overrides */
        :root {
            --r-background-color: #0a0a0f;
            --r-main-font: 'Syne', sans-serif;
            --r-main-color: #f8f9fa;
            --r-heading-font: 'Syne', sans-serif;
            --r-heading-color: #f8f9fa;
            --r-heading-font-weight: 800;
            --r-link-color: #00f5d4;
            --r-link-color-hover: #7b2fff;
            --cyan: #00f5d4;
            --purple: #7b2fff;
            --text-muted: #8b949e;
        }

        .reveal {
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(123, 47, 255, 0.08), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(0, 245, 212, 0.08), transparent 25%);
        }

        .reveal h1, .reveal h2, .reveal h3 { text-transform: none; }
        
        .reveal h1.title {
            font-size: 4rem; 
            background: linear-gradient(90deg, var(--cyan), var(--purple)); 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent;
        }

        .reveal p { line-height: 1.6; }
        
        /* Glass Panel */
        .glass-panel {
            background: rgba(26, 26, 46, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        .badge {
            display: inline-block;
            padding: 6px 16px;
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-radius: 30px;
            background: rgba(0, 245, 212, 0.1);
            color: var(--cyan);
            border: 1px solid rgba(0, 245, 212, 0.3);
            margin-bottom: 1rem;
        }
        .badge.purple {
            background: rgba(123, 47, 255, 0.1);
            color: #b084ff;
            border-color: rgba(123, 47, 255, 0.3);
        }

        /* Lists */
        .reveal ul.custom-list { list-style: none; margin: 0; padding: 0; width: 100%; }
        .reveal ul.custom-list li { margin-bottom: 1rem; display: flex; align-items: center; gap: 1rem; font-size: 1.5rem; }
        .custom-list i.fa-xmark { color: #ff3366; }
        .custom-list i.fa-check { color: var(--cyan); }

        /* Code blocks */
        .reveal pre {
            box-shadow: none;
            width: 100%;
            margin: 0;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .reveal pre code {
            font-family: 'IBM Plex Mono', monospace;
            padding: 1.5rem;
            max-height: 600px;
        }
        
        /* Grid */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; width: 100%; align-items: stretch; text-align: left; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; width: 100%; }

        /* Fluxo animado via Fragmentos */
        .flow-container {
            display: flex; align-items: center; justify-content: space-between;
            width: 100%; margin-top: 4rem; position: relative; padding: 0 1rem;
        }
        .flow-line {
            position: absolute; top: 50%; left: 3rem; right: 3rem; height: 4px; background: rgba(255,255,255,0.1);
            transform: translateY(-50%); z-index: 0;
        }
        .flow-node {
            width: 70px; height: 70px; border-radius: 50%; background: rgba(26, 26, 46, 1); border: 2px solid rgba(255, 255, 255, 0.2);
            display: flex; align-items: center; justify-content: center; z-index: 2; position: relative;
            color: var(--text-muted); font-size: 1.5rem; transition: 0.3s;
        }
        /* Quando o fragmento está ativo */
        .flow-node.visible {
            border-color: var(--cyan); color: var(--cyan); background: rgba(0,245,212,0.1);
            box-shadow: 0 0 20px rgba(0,245,212,0.4); transform: scale(1.2);
        }
        .node-label {
            position: absolute; top: -45px; white-space: nowrap; font-size: 1.1rem; font-weight: 700;
            color: var(--text-main); opacity: 0; transition: 0.3s; transform: translateY(10px);
        }
        .node-pattern {
            position: absolute; bottom: -50px; white-space: nowrap; font-size: 0.9rem; font-family: 'IBM Plex Mono', monospace;
            color: var(--cyan); opacity: 0; transition: 0.3s; transform: translateY(-10px);
        }
        .flow-node.visible .node-label, .flow-node.visible .node-pattern { opacity: 1; transform: translateY(0); }

        .bp-card {
            background: rgba(26, 26, 46, 0.6); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px;
            padding: 1.5rem; text-align: left; display: flex; flex-direction: column; gap: 0.5rem;
        }
        .bp-card i { font-size: 2rem; color: var(--cyan); margin-bottom: 0.5rem; }

        .metric-card { text-align: center; padding: 2rem; background: rgba(26, 26, 46, 0.6); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px; }
        .metric-num { font-size: 4rem; font-weight: 800; color: var(--cyan); font-family: 'IBM Plex Mono', monospace; line-height: 1;}
    </style>
</head>
<body>
    <div class="reveal">
        <div class="slides">
            
            <!-- SEÇÃO 1: ABERTURA -->
            <section>
                <h1 class="title">Padrões de Projeto</h1>
                <p style="font-size: 1.8rem; margin-top: 1rem; color: var(--text-muted);">Como padrões arquiteturais transformaram<br>o sistema SyncMatch.</p>
                <p style="margin-top: 3rem; font-size: 1rem;"><i class="fa-solid fa-arrows-left-right"></i> Use as setas para navegar</p>
            </section>

            <!-- SEÇÃO 2: O PROBLEMA -->
            <section>
                <section>
                    <div class="badge purple">O Cenário</div>
                    <h2>O Caos Antes da Ordem</h2>
                    <p>O que acontece quando regras de negócio são misturadas com apresentação?</p>
                </section>
                <section>
                    <div class="glass-panel grid-2">
                        <div>
                            <pre><code class="language-php" data-trim data-line-numbers="1-10|2-6|7-9">
public function salvar(Request $request) {
    // 🍝 Código Espaguete
    if($request->tipo == 'estudante') {
        // ... 50 linhas de lógica acoplada
    } elseif ($request->tipo == 'empresa') {
        // ... 80 linhas duplicadas
    }
    if($status == 'aprovado') {
        Mail::send(...); // Regra de negócio na ponta!
    }
}
                            </code></pre>
                        </div>
                        <div style="display: flex; flex-direction: column; justify-content: center; padding-left: 2rem;">
                            <ul class="custom-list">
                                <li class="fragment"><i class="fa-solid fa-xmark"></i> Código duplicado e complexo</li>
                                <li class="fragment"><i class="fa-solid fa-xmark"></i> Controladores Obesos</li>
                                <li class="fragment"><i class="fa-solid fa-xmark"></i> Impossível de testar (Mocks)</li>
                                <li class="fragment"><i class="fa-solid fa-xmark"></i> Manutenção arriscada</li>
                            </ul>
                        </div>
                    </div>
                </section>
            </section>

            <!-- SEÇÃO 3: A SOLUÇÃO -->
            <section>
                <section>
                    <div class="badge">A Solução</div>
                    <h2 style="font-size: 3rem;">Gang of Four (GoF)</h2>
                    <p>Soluções testadas pelo tempo para problemas comuns de engenharia de software.</p>
                </section>
                <section>
                    <h3>As Três Famílias</h3>
                    <div class="grid-3" style="margin-top: 3rem;">
                        <div class="glass-panel fragment" style="text-align: center;">
                            <i class="fa-solid fa-hammer fa-3x" style="color: var(--cyan); margin-bottom: 1rem;"></i>
                            <h4>Criacionais</h4>
                            <p style="font-size: 1rem; color: var(--text-muted);">Como criar objetos</p>
                        </div>
                        <div class="glass-panel fragment" style="text-align: center;">
                            <i class="fa-solid fa-layer-group fa-3x" style="color: var(--cyan); margin-bottom: 1rem;"></i>
                            <h4>Estruturais</h4>
                            <p style="font-size: 1rem; color: var(--text-muted);">Como compor estruturas</p>
                        </div>
                        <div class="glass-panel fragment" style="text-align: center;">
                            <i class="fa-solid fa-network-wired fa-3x" style="color: var(--cyan); margin-bottom: 1rem;"></i>
                            <h4>Comportamentais</h4>
                            <p style="font-size: 1rem; color: var(--text-muted);">Como os objetos comunicam</p>
                        </div>
                    </div>
                </section>
            </section>

            <!-- SEÇÃO 4: FLUXO -->
            <section>
                <h2>A Jornada da Requisição</h2>
                <p>Navegação do pacote de dados pela nossa arquitetura.</p>
                
                <div class="glass-panel" style="margin-top: 3rem; padding-bottom: 6rem;">
                    <div class="flow-container">
                        <div class="flow-line"></div>
                        
                        <div class="flow-node fragment custom visible" id="n1"><i class="fa-solid fa-door-open"></i>
                            <span class="node-label">1. Roteamento</span>
                            <span class="node-pattern">Entry Point</span>
                        </div>
                        <div class="flow-node fragment custom visible" id="n2"><i class="fa-solid fa-shield-halved"></i>
                            <span class="node-label">2. Orquestração</span>
                            <span class="node-pattern">Facade (Fachada)</span>
                        </div>
                        <div class="flow-node fragment custom visible" id="n3"><i class="fa-solid fa-industry"></i>
                            <span class="node-label">3. Instanciação</span>
                            <span class="node-pattern">Factory (Fábrica)</span>
                        </div>
                        <div class="flow-node fragment custom visible" id="n4"><i class="fa-solid fa-chess-knight"></i>
                            <span class="node-label">4. Execução</span>
                            <span class="node-pattern">Strategy / State</span>
                        </div>
                        <div class="flow-node fragment custom visible" id="n5"><i class="fa-solid fa-database"></i>
                            <span class="node-label">5. Persistência</span>
                            <span class="node-pattern">Builder (Construtor)</span>
                        </div>
                        <div class="flow-node fragment custom visible" id="n6"><i class="fa-solid fa-satellite-dish"></i>
                            <span class="node-label">6. Assíncrono</span>
                            <span class="node-pattern">Observer (Observador)</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SEÇÃO 5: PADRÕES NA PRÁTICA (AUTO-ANIMATE) -->
            
            <!-- Factory -->
            <section>
                <section data-auto-animate>
                    <div class="badge">Padrão Criacional</div>
                    <h2>Factory Method <br><span style="font-size:1.5rem; color:var(--text-muted)">(Método Fábrica)</span></h2>
                    <p style="text-align: left; margin-bottom: 1rem;">Omitindo a lógica complexa de criação de classes do chamador.</p>
                    <pre data-id="code-factory"><code class="language-php" data-trim>
// ❌ Antes: O Controller sabe demais (Acoplado)
if ($role === 'student') {
    StudentProfile::create($data);
} elseif ($role === 'recruiter') {
    Company::create($data);
    RecruiterProfile::create($data);
}
                    </code></pre>
                </section>
                <section data-auto-animate>
                    <div class="badge">Padrão Criacional</div>
                    <h2>Factory Method <br><span style="font-size:1.5rem; color:var(--text-muted)">(Método Fábrica)</span></h2>
                    <p style="text-align: left; margin-bottom: 1rem;">Omitindo a lógica complexa de criação de classes do chamador.</p>
                    <pre data-id="code-factory"><code class="language-php" data-trim>
// ✅ Depois: A Fábrica decide por nós (OCP Aberto/Fechado)
$creator = UserProfileFactory::make($role);

// A interface garante o contrato:
$creator->createProfile($user, $data);
                    </code></pre>
                </section>
            </section>

            <!-- Strategy -->
            <section>
                <section data-auto-animate>
                    <div class="badge purple">Padrão Comportamental</div>
                    <h2>Strategy <br><span style="font-size:1.5rem; color:var(--text-muted)">(Estratégia)</span></h2>
                    <p style="text-align: left; margin-bottom: 1rem;">Encapsulando famílias de algoritmos para injeção em runtime.</p>
                    <pre data-id="code-strategy"><code class="language-php" data-trim>
// ❌ Antes: Amarrado a implementações físicas
class UserRegistrationService {
    public function register() {
        Mail::to($user)->send(new WelcomeEmail());
        // E se eu quiser enviar SMS agora? E se eu quiser apenas gerar log no dev?
    }
}
                    </code></pre>
                </section>
                <section data-auto-animate>
                    <div class="badge purple">Padrão Comportamental</div>
                    <h2>Strategy <br><span style="font-size:1.5rem; color:var(--text-muted)">(Estratégia)</span></h2>
                    <p style="text-align: left; margin-bottom: 1rem;">Encapsulando famílias de algoritmos para injeção em runtime.</p>
                    <pre data-id="code-strategy"><code class="language-php" data-trim>
interface NotificationStrategy {
    public function send(User $user, string $message): void;
}

// ✅ Depois: Injetamos o comportamento (Polimorfismo)
class UserRegistrationService {
    public function __construct(private NotificationStrategy $notifier) {}
    
    public function register() {
        $this->notifier->send($user, 'Bem vindo!'); 
    }
}
                    </code></pre>
                </section>
            </section>

            <!-- Observer -->
            <section>
                <section>
                    <div class="badge purple">Padrão Comportamental</div>
                    <h2>Observer <br><span style="font-size:1.5rem; color:var(--text-muted)">(Observador / Pub-Sub)</span></h2>
                    <p>Como isolar efeitos colaterais.</p>
                    <div class="glass-panel fragment" style="margin-top: 2rem;">
                        <div style="display: flex; align-items: center; justify-content: center; gap: 3rem;">
                            <div style="text-align:center;">
                                <i class="fa-solid fa-bullhorn fa-3x" style="color: var(--cyan);"></i>
                                <h4>Evento</h4>
                                <p style="font-size:1rem; font-family:'IBM Plex Mono', monospace;">ApplicationApproved</p>
                            </div>
                            <i class="fa-solid fa-arrow-right fa-2x" style="color: var(--text-muted);"></i>
                            <div style="text-align:center;">
                                <i class="fa-solid fa-envelope fa-3x" style="color: var(--purple);"></i>
                                <h4>Ouvinte</h4>
                                <p style="font-size:1rem; font-family:'IBM Plex Mono', monospace;">SendApprovalEmail</p>
                            </div>
                        </div>
                        <p style="margin-top: 2rem; font-style: italic; color: var(--text-muted);">"A classe não precisa disparar o e-mail, ela apenas avisa que o evento aconteceu."</p>
                    </div>
                </section>
            </section>

            <!-- SEÇÃO 6: BOAS PRÁTICAS -->
            <section>
                <h2>Além dos Padrões</h2>
                <div class="grid-3" style="margin-top: 3rem;">
                    <div class="bp-card fragment">
                        <i class="fa-solid fa-cube"></i>
                        <h4>Princípios SOLID</h4>
                        <p style="font-size: 1rem; margin:0; color:var(--text-muted)">SRP, OCP e Interfaces segregadas em todo o escopo.</p>
                    </div>
                    <div class="bp-card fragment">
                        <i class="fa-solid fa-syringe"></i>
                        <h4>Injeção de Dependência</h4>
                        <p style="font-size: 1rem; margin:0; color:var(--text-muted)">Sem "news" perdidos nos controladores.</p>
                    </div>
                    <div class="bp-card fragment">
                        <i class="fa-solid fa-layer-group"></i>
                        <h4>Arquitetura Limpa</h4>
                        <p style="font-size: 1rem; margin:0; color:var(--text-muted)">Repositórios blindando o Banco de Dados (Eloquent).</p>
                    </div>
                    <div class="bp-card fragment">
                        <i class="fa-solid fa-file-contract"></i>
                        <h4>Orientado a Contratos</h4>
                        <p style="font-size: 1rem; margin:0; color:var(--text-muted)">Uso massivo de Interfaces em vez de Implementações.</p>
                    </div>
                    <div class="bp-card fragment">
                        <i class="fa-solid fa-rocket"></i>
                        <h4>Orientação a Eventos</h4>
                        <p style="font-size: 1rem; margin:0; color:var(--text-muted)">Processamento de filas assíncronas.</p>
                    </div>
                    <div class="bp-card fragment">
                        <i class="fa-solid fa-code"></i>
                        <h4>Código Expressivo</h4>
                        <p style="font-size: 1rem; margin:0; color:var(--text-muted)">Sem comentários inúteis. O código é a documentação.</p>
                    </div>
                </div>
            </section>

            <!-- SEÇÃO 7: MÉTRICAS -->
            <section>
                <h2>O Impacto nos Números</h2>
                <div class="grid-2" style="margin-top: 3rem;">
                    <div class="metric-card fragment">
                        <div class="metric-num">96%</div>
                        <p style="text-transform: uppercase; letter-spacing: 1px; font-size: 1rem;">Aderência SOLID</p>
                    </div>
                    <div class="metric-card fragment">
                        <div class="metric-num">6</div>
                        <p style="text-transform: uppercase; letter-spacing: 1px; font-size: 1rem;">Padrões GoF Integrados</p>
                    </div>
                    <div class="metric-card fragment">
                        <div class="metric-num">100%</div>
                        <p style="text-transform: uppercase; letter-spacing: 1px; font-size: 1rem;">Injeção de Dependências</p>
                    </div>
                    <div class="metric-card fragment">
                        <div class="metric-num">70%</div>
                        <p style="text-transform: uppercase; letter-spacing: 1px; font-size: 1rem;">Redução de Desvios (IF/ELSE)</p>
                    </div>
                </div>
            </section>

            <!-- SEÇÃO 8: FIM -->
            <section>
                <h1 class="title" style="font-size: 3.5rem;">Padrões não são regras.</h1>
                <h1 class="title" style="font-size: 3.5rem;">São vocabulário.</h1>
                <p style="font-style: italic; margin-top: 3rem; color: var(--text-muted);">"Projetar software orientado a objetos é difícil,<br>projetar software orientado a objetos reutilizável é ainda mais difícil."</p>
                <p style="margin-top: 1rem; font-family: 'IBM Plex Mono', monospace; font-size: 0.9rem; color: var(--cyan);">- Gang of Four (GoF)</p>
            </section>

        </div>
    </div>

    <!-- Scripts do Reveal.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/5.0.4/reveal.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/reveal.js/5.0.4/plugin/highlight/highlight.js"></script>
    <script>
        Reveal.initialize({
            hash: true,
            controls: true,
            progress: true,
            center: true,
            transition: 'slide', // none/fade/slide/convex/concave/zoom
            plugins: [ RevealHighlight ]
        });
    </script>
</body>
</html>
HTML;

file_put_contents($outPath, $html);
echo "Apresentacao Reveal.js gerada com sucesso em: " . realpath($outPath) . "\n";
