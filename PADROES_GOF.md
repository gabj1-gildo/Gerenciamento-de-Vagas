# Padrões de Projeto (GoF) Utilizados no SyncMatch

Este documento detalha os **Design Patterns (Padrões de Projeto do Gang of Four - GoF)** implementados na arquitetura do sistema SyncMatch durante as fases de refatoração. O objetivo principal da aplicação destes padrões foi reduzir o acoplamento, aumentar a coesão, facilitar testes automatizados e preparar o terreno para manutenções e expansões futuras.

---

## 1. Facade (Padrão Estrutural)

**📍 Onde foi utilizado:** 
- `App\Services\UserRegistrationService`
- `App\Services\ApplicationService`

**❓ Necessidade (Problema resolvido):** 
Anteriormente, os Controllers (como `MainController` e `ApplicationController`) acumulavam muitas responsabilidades: validavam requests, criavam usuários, definiam perfis, gerenciavam uploads e disparavam e-mails. Isso feria o princípio da Responsabilidade Única (SRP) e tornava os controllers "Gordos" (Fat Controllers).

**✅ Vantagens Obtidas:**
- **Simplificação da Interface:** Os Controllers agora chamam apenas um método simples (ex: `$this->registrationService->register($data)`).
- **Desacoplamento:** O Controller não precisa saber *como* um usuário é criado, apenas *pede* que seja criado.
- **Reuso:** A mesma lógica de registro ou candidatura pode ser chamada via API, Web ou linha de comando (Artisan) sem duplicar código.

---

## 2. Factory Method (Padrão Criacional)

**📍 Onde foi utilizado:** 
- `App\Patterns\Factory\UserProfileFactory`
- `App\Patterns\Factory\StudentProfileCreator`
- `App\Patterns\Factory\RecruiterProfileCreator`

**❓ Necessidade (Problema resolvido):** 
Ao registrar um usuário, a lógica precisava checar com vários `if/else` qual tipo de conta o usuário escolheu (Candidato ou Recrutador) para criar os registros nas tabelas corretas (`candidate_profiles` ou relacionamento com `companies`).

**✅ Vantagens Obtidas:**
- **Aberto/Fechado (OCP):** Se no futuro adicionarmos um novo tipo de usuário (ex: Administrador de RH ou Parceiro), basta criar uma nova classe `Creator` sem mexer no serviço principal de registro.
- **Isolamento de Lógica:** A complexidade de criar e associar uma Empresa ao perfil do Recrutador ficou totalmente isolada na sua própria classe criadora.

---

## 3. Strategy (Padrão Comportamental)

**📍 Onde foi utilizado:** 
- `App\Services\Notification\NotificationStrategy` (Interface)
- `App\Services\Notification\LaravelMailStrategy` (Implementação concreta)
- Estratégias de Autorização (`StudentAuthorization`, `RecruiterAuthorization`) no `ApplicationService`.

**❓ Necessidade (Problema resolvido):** 
- **Notificações:** O sistema estava preso ao envio de e-mails tradicional. Se quiséssemos mudar o envio para SMS ou apenas salvar em log, teríamos que reescrever partes cruciais do sistema.
- **Autorização:** As regras para verificar se um usuário tem permissão para visualizar candidatos variavam drasticamente dependendo do seu *Role* (estudante vs recrutador).

**✅ Vantagens Obtidas:**
- **Flexibilidade (Plug and Play):** O sistema agora delega a responsabilidade para uma "Estratégia". Podemos facilmente trocar a forma de notificação ou criar novas regras de permissão sem alterar o código que as chama.
- **Fácil Testabilidade:** Em testes automatizados, podemos injetar uma `MockStrategy` que não faz nada, acelerando o tempo de execução dos testes.

---

## 4. State (Padrão Comportamental)

**📍 Onde foi utilizado:** 
- `App\Patterns\State\ApplicationState` (Interface)
- `RecebidoState`, `EmAnaliseState`, `AprovadoState`, `RejeitadoState`

**❓ Necessidade (Problema resolvido):** 
Mudar o status de uma candidatura (ex: de "Recebido" para "Aprovado") envolvia lógicas específicas. Por exemplo, ao aprovar um candidato, o sistema deveria obrigatoriamente fechar a vaga e enviar um e-mail de parabéns. Fazer isso espalhando `if ($status == 'aprovado')` gerava um código frágil e difícil de dar manutenção.

**✅ Vantagens Obtidas:**
- **Comportamento Direcionado pelo Estado:** Cada status da candidatura sabe lidar com suas próprias regras e efeitos colaterais (Side Effects).
- **Eliminação de Condicionais Complexas:** O serviço principal não precisa saber o que acontece quando a vaga é aprovada; ele apenas delega a ação ao Estado Atual do objeto.

---

## 5. Builder (Padrão Criacional)

**📍 Onde foi utilizado:** 
- `App\Patterns\Builder\JobSearchBuilder`

**❓ Necessidade (Problema resolvido):** 
A tela de listagem de vagas possuía múltiplos filtros opcionais (Busca por termo, Tipo de contrato, Modalidade). O Controller acumulava dezenas de linhas de `if ($request->has('type')) { $query->where(...) }` para construir a Query SQL no banco de dados.

**✅ Vantagens Obtidas:**
- **Construção Passo a Passo:** O Builder permite "montar" a consulta ao banco de dados gradualmente, encadeando métodos como `$builder->search($termo)->withType($tipo)->build()`.
- **Código Fluente e Limpo:** A montagem de consultas complexas ficou extremamente legível e fácil de estender caso novos filtros sejam criados no futuro.

---

## 6. Observer (Padrão Comportamental)

**📍 Onde foi utilizado:** 
- `App\Events\ApplicationApproved` (Evento / Subject)
- `App\Listeners\SendApprovalNotification` (Listener / Observer)

**❓ Necessidade (Problema resolvido):** 
Quando um candidato é aprovado, várias coisas devem acontecer (fechar vaga, mandar e-mail, etc.). Misturar a lógica de envio de e-mail no meio da lógica que altera dados no banco atrasava a resposta do servidor e misturava responsabilidades.

**✅ Vantagens Obtidas:**
- **Desacoplamento Assíncrono:** O `ApplicationService` apenas "grita" para o sistema: *"O candidato foi aprovado!"* (Dispara o evento). Quem está interessado nisso (os Listeners/Observers) escutam a mensagem e reagem enviando o e-mail em segundo plano.

---

## (Bônus) Repository Pattern

*Embora seja frequentemente classificado como um padrão de arquitetura de software (PoEAA - Martin Fowler) e não estritamente GoF, sua aplicação atua de forma complementar aos demais padrões estruturais.*

**📍 Onde foi utilizado:** `App\Repositories\JobRepository`
**✅ Vantagens:** Isola a camada de persistência (Eloquent ORM) dos Controllers. Se amanhã o banco de dados mudar ou precisarmos de cacheamento intenso (ex: Redis), mudamos apenas no Repository, e os Controllers não sofrerão nenhum impacto.
