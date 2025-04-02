# Password Generator Project

## Descrição
Este projeto consiste em um **Gerador de Senhas** construído com:
- Backend utilizando **Flight PHP** como framework para gerenciar rotas.
- Frontend utilizando **Vue.js** para a interface do usuário.

O objetivo é demonstrar uma aplicação simples que integra frontend e backend, permitindo que os usuários gerem senhas de forma dinâmica.

---

## Tecnologias Utilizadas
### Backend
- **PHP 8.2**
- **Flight PHP**
- **MySQL** (para gerenciamento de banco de dados)
- **Docker** (para containerização)
- **JWT** (para autenticação e autorização)
- **bcrypt** (para criptografia de senhas)
- **Symfony YAML** (para manipulação de arquivos .yaml)
- **PHPUnit** (para testes automatizados)

### Frontend
- **Vue.js**
- **Axios** (para requisições HTTP)
- **Docker** (para containerização)

---

## Como Executar

### Pré-requisitos
- **Docker** instalado em sua máquina.
- **Docker Compose** para gerenciar os contêineres.

### Instruções
1. Clone este repositório:
   ```
   git clone https://github.com/seu-usuario/password-generator.git
   cd password-generator
   ```
2. Construa os contêineres:
```
docker-compose up --build
```

3. Acesse o backend no navegador:

= **Rota inicial:** http://localhost:9000
- **Testar conexão com o banco:** http://localhost:9000/db-test
- **Gerar senha:** http://localhost:9000/generate-password

4. Para o frontend, acesse:
- **Interface de usuário:** http://localhost:8080
- Clique no botão "Gerar Senha" para gerar uma senha dinâmica.

## Estrutura do Projeto
```
PasswordGenerator/
├── backend/                  # Código do backend (Flight PHP)
│   ├── public/               # Arquivos públicos (index.php)
│   ├── composer.json         # Gerenciador de dependências do backend
│   └── Dockerfile            # Configuração do Docker para o backend
├── frontend/                 # Código do frontend (Vue.js)
│   ├── src/                  # Código-fonte do Vue.js
│   │   ├── components/       # Componentes Vue
│   │   │   └── PasswordGenerator.vue
│   │   └── axios.js          # Configuração do Axios
│   ├── package.json          # Gerenciador de dependências do frontend
│   └── Dockerfile            # Configuração do Docker para o frontend
├── docker-compose.yml        # Configuração do Docker Compose
└── README.md                 # Documentação do projeto
```

### Funcionalidades
- **Gerar Senhas:** Gera senhas aleatórias e seguras com caracteres alfanuméricos.
- **Salvar Senhas:** Armazena as senhas no banco de dados.
- **Listar Senhas:** (Planejado) Exibir as senhas armazenadas.


### Histórico do Projeto
#### Backend
- Configuração inicial com Flight PHP para gerenciar rotas.
- Adicionada rota `/generate-password` para gerar senhas aleatórias.
- Configuração do Docker para containerização do backend.
- Adicionada rota `/save-password` para salvar senhas no banco de dados.
- Implementada conexão com o banco de dados MySQL utilizando Illuminate Database.
- Adicionada a rota `/list-passwords` para recuperar e retornar todas as senhas salvas no banco de dados no formato JSON.
- Corrigida formatação das respostas JSON nas rotas para evitar mensagens indesejadas antes do JSON.
- Adicionado tratamento de erros detalhado nas rotas para diagnóstico aprimorado.
- Implementada validação no backend para garantir que apenas respostas JSON sejam retornadas para o frontend.
- Integrado suporte a JWT utilizando a biblioteca `firebase/php-jwt`.
  - Rota `/generate-token` criada para geração de tokens JWT.
  - Rota `/validate-token` criada para validação de tokens JWT.
- Configuração de CORS para permitir comunicação entre frontend e backend.
- Tratamento de erros detalhado nas rotas para diagnóstico aprimorado.
- Implementada criptografia de senhas com bcrypt ao salvar no banco de dados.
- Adicionada verificação de senhas utilizando bcrypt para comparar hashes.
- Adicionado suporte ao `Symfony YAML` para manipulação de arquivos .yaml.
- Adicionado `PHPUnit` para testes automatizados robustos.
- Adicionadas validações para entradas nas rotas do backend (ex.: verificação de campos obrigatórios como password e formatação de erros detalhada).
- Implementado tratamento de erros genéricos com mapeamento centralizado no backend.

#### Frontend
- Configuração inicial com Vue.js..
- Integrado Axios para comunicação com o backend.
- Criado o componente PasswordGenerator.vue para gerar e exibir senhas aleatórias.
- Configuração do Docker para containerização do frontend.
- Atualizado o componente PasswordGenerator.vue para salvar as senhas geradas no banco de dados através da rota /save-password.
- Adicionado evento para atualizar automaticamente a lista de senhas salvas após gerar uma nova senha.
- Corrigido o componente ListPasswords.vue para tratar mensagens malformadas antes do JSON retornado pelo backend.
- Garantido que o componente ListPasswords.vue exiba as senhas salvas corretamente a partir da rota /list-passwords.
- Criado o componente TokenValidationForm.vue para validar tokens JWT através da rota /validate-token.
- Atualizado o App.vue para integrar o novo componente TokenValidationForm.vue no fluxo principal.


### Funcionalidades Planejadas

Persistência de dados usando MySQL.

Implementar validação de entradas com yup ou joi.

Adicionar sistema de notificações em tempo real com socket.io.

1. Listar Senhas Salvas
Criar uma rota no backend para retornar todas as senhas armazenadas no banco de dados.

Isso permitirá que o frontend ou um cliente visualize as senhas salvas.

2. Validação e Segurança
Evitar duplicatas: Implementar uma verificação para evitar que a mesma senha seja salva mais de uma vez.

Regras de geração: Adicionar configurações para que o usuário defina o comprimento da senha e o tipo de caracteres (alfanuméricos, especiais, etc.).


3. Expandir Funcionalidades
Implementar o front-end para exibir senhas geradas e permitir salvar diretamente pela interface.

Adicionar filtros no front-end para buscar senhas salvas com base em data ou outros critérios.

4. Documentação
Melhorar o README.md com mais detalhes sobre as rotas disponíveis e como contribuir para o projeto.






#### Backend:
- Implementar autenticação baseada em níveis de acesso (admin, usuário, etc.).
- Adicionar rotas para deletar senhas específicas do banco de dados.
- Implementar limite de tentativas de login para evitar ataques de força bruta.
- Adicionar logs detalhados de todas as solicitações feitas ao backend.
- Configurar HTTPS com certificados SSL para segurança.
- Integrar suporte a múltiplos bancos de dados (ex.: PostgreSQL).
- Adicionar expiração automática de senhas antigas no banco.
- Implementar cache para melhorar o desempenho de rotas mais acessadas.
- Adicionar suporte para envio de notificações (ex.: e-mail ou SMS) após ações específicas.
- Configurar autenticação de dois fatores (2FA).
- Criar documentação automatizada das rotas com OpenAPI/Swagger.
- Adicionar suporte para uploads e armazenamento de arquivos de senhas em formato seguro.
- Implementar validação mais robusta nos dados de entrada das rotas.
- Adicionar integração com serviços externos como APIs de segurança.
- Criar testes automatizados para validar o funcionamento do backend.

#### Frontend:
- Implementar paginação na listagem de senhas salvas.
- Criar funcionalidade de busca para encontrar senhas específicas.
- Adicionar tema escuro/claro para personalização visual.
- Criar gráficos interativos sobre o uso de senhas (ex.: número de senhas geradas).
- Implementar notificações em tempo real após gerar ou salvar senhas.
- Adicionar botão para copiar senhas geradas para a área de transferência.
- Integrar animações para tornar o uso mais fluido e atraente.
- Configurar sistema de tradução para múltiplos idiomas.
- Adicionar suporte offline com armazenamento local para senhas temporárias.
- Implementar formulário de contato com integração para enviar feedback.
- Adicionar visualização de segurança relativa para cada senha gerada.
- Criar autenticação de login no frontend integrado com o JWT do backend.
- Configurar testes de interface para validar funcionalidade e design.
- Melhorar acessibilidade (ex.: suporte para leitores de tela).
- Adicionar sistema de exportação de senhas em formato CSV ou PDF.