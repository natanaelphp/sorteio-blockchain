# 🎲 Sorteio Blockchain

Um sistema de sorteios 100% transparente e auditável que utiliza a blockchain do **Bitcoin** como fonte pública e imutável de entropia (aleatoriedade).

---

## 💡 O Problema e a Nossa Solução

Sorteios web tradicionais dependem de geradores de números pseudo-aleatórios (PRNGs) escondidos em servidores privados (`Math.random()`, `rand()`, etc.). Isso exige **confiança cega** dos participantes: ninguém garante que o dono do servidor não manipulou o resultado no banco de dados.

**Nossa solução:** Nós removemos a necessidade de confiança. Ao atrelar a seleção do vencedor ao hash do próximo bloco do Bitcoin, nós garantimos que:

1. **Transparência Absoluta:** O número vencedor deriva de uma fonte pública. Qualquer pessoa no mundo pode consultar o bloco num explorador de blocos e verificar a matemática.
2. **Imprevisibilidade Matemática:** Ninguém — nem os criadores da plataforma, nem os participantes, nem os próprios mineradores de Bitcoin — pode prever ou forjar o hash do próximo bloco.
3. **Imutabilidade:** Após a confirmação da rede, o resultado fica gravado para sempre na história, imune a fraudes retroativas.

---

## ⚙️ Como o Algoritmo Funciona?

O fluxo do sorteio é simples, elegante e totalmente determinístico:

1. As pessoas participam do sorteio recebendo bilhetes sequenciais (ex: bilhetes de 0 a 99).
2. O sorteio é bloqueado para novas entradas. O sistema define que o resultado dependerá do **próximo bloco a ser minerado** na rede.
3. Quando o bloco é minerado, o nó do Bitcoin notifica o nosso servidor em tempo real (via ZeroMQ).
4. O servidor extrai o Hash do Bloco (uma string hexadecimal gigante, ex: `00000000000000000003b...`).
5. Transformamos esse hash hexadecimal em um número decimal gigantesco.
6. Aplicamos a operação de módulo: `Número Decimal % Total de Participantes = Índice do Vencedor`.

Como a lista de participantes e o hash do bloco são dados abertos, **qualquer juiz, auditor ou participante pode reproduzir o cálculo e chegar exatamente ao mesmo vencedor.**

---

## 🛠️ Stack Tecnológica

O projeto foi construído focando em performance, reatividade em tempo real e boas práticas de arquitetura.

### Backend
* **Laravel 13:** Orquestração geral, rotas, banco de dados e APIs limpas.
* **PHP 8.3:** Linguagem base com forte tipagem e recursos modernos.
* **ZeroMQ (ZMQ):** Estabelece um *socket* de comunicação constante e leve com o nó do Bitcoin, escutando eventos de novos blocos (`hashblock`) em *background* via um comando Artisan dedicado (`ListenBitcoinBlocks`).

### Frontend
* **Vue.js 3:** Criando uma interface web3-style dinâmica e reativa.
* **Inertia.js:** Faz a ponte entre Laravel e Vue, permitindo construir uma *Single Page Application (SPA)* sem a necessidade de construir uma API REST complexa de meio de campo.
* **Tailwind CSS:** Estilização moderna, responsiva e focada em utilitários.

### Blockchain
* **Bitcoin Core (Modo Regtest):** Para este ambiente de Hackathon, o projeto se conecta a um nó de Bitcoin rodando localmente em modo *Regression Test*. Isso nos permite simular a rede principal (Mainnet), minerando blocos sob demanda instantaneamente e sem custo financeiro, validando toda a arquitetura que funciona de forma idêntica no mundo real.

---

## 🚀 Para o Avaliador: O que observar

Durante a avaliação deste projeto, sugerimos atentar para os seguintes pontos de destaque:

1. **A Integração Real com o Node:** Não estamos mockando (simulando falsamente) a blockchain. O Laravel está ativamente ouvindo uma porta TCP via ZMQ conectada a um nó real do Bitcoin Core.
2. **Separação de Conceitos (Clean Code):** Observe como isolamos a lógica do modelo de domínio das regras de repositório e infraestrutura, garantindo que o comando do console responsável pelo listener não se misture com a persistência de banco de dados.
3. **UX Transparente:** O frontend não apenas mostra o vencedor, mas exibe o hash do bloco exato que gerou a vitória, educando o usuário e fornecendo a prova criptográfica do resultado.

Obrigado por avaliar nosso projeto! 
