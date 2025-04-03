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
- **Vue Router** (para gerenciamento de rotas)

---

## Como Executar

### Pré-requisitos
- **Docker** instalado em sua máquina.
- **Docker Compose** para gerenciar os contêineres.

### Instruções
1. Clone este repositório:
   ```
   git clone https://github.com/lgomesroc/GeradorDeSenha.git
   cd GeradorDeSenha
   ```
2. Construa os contêineres:
```
docker-compose up --build
```

## **Como Executar o Sistema**

Após construir e iniciar os contêineres, siga os comandos abaixo para executar o backend, o frontend e o banco de dados MySQL:

### **Backend**
1. Certifique-se de que o contêiner do backend está ativo.

2. Acesse o terminal do contêiner do backend:
```
docker exec -it backend bash
```

3. Execute o comando para iniciar o backend:
```
php -S 0.0.0.0:9001
```

4. O backend estará acessível em http://localhost:9001.

### **Frontend**
1. Certifique-se de que o contêiner do frontend está ativo.

2. Acesse o terminal do contêiner do frontend:
```
docker exec -it frontend bash
```

3. Execute o comando para iniciar o frontend no modo de desenvolvimento:
```
npm run serve
```

4. O frontend estará acessível em http://localhost:8081.

### **Banco de Dados MySQL**
1. Certifique-se de que o contêiner do MySQL está ativo.

2. Para acessar o banco de dados MySQL diretamente:
```
docker exec -it mysql bash
```

3. Dentro do contêiner, conecte-se ao MySQL:
```
mysql -u password_user -p
```
4. Insira a senha configurada no contêiner para acessar o MySQL. A partir daí, você pode executar comandos SQL diretamente.



### URLs
1. Acesse o backend no navegador:

= **Rota inicial:** http://localhost:9001
- **Testar conexão com o banco:** http://localhost:9001/db-test
- **Gerar senha:** http://localhost:9001/generate-password

2. Para o frontend, acesse:
- **Interface de usuário:** http://localhost:8081


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
- Criada a rota `/register` para cadastro de usuários com nome, email e senha.
- Criada a rota `/login` para autenticação de usuários e retorno de tokens JWT.
- Implementada rota `/update-user` para atualização de informações de usuários (nome e email).
- Implementada rota `/update-password` para alteração de senha do usuário.
- Criada a rota `/delete-user` para exclusão de usuários e suas senhas associadas, com confirmação.
- Configurado tratamento de erros detalhados em todas as novas rotas.


#### Frontend
- Configuração inicial com Vue.js.
- Integrado Axios para comunicação com o backend.
- Criado o componente **PasswordGenerator.vue** para gerar e exibir senhas aleatórias.
- Configuração do Docker para containerização do frontend.
- Atualizado o componente PasswordGenerator.vue para salvar as senhas geradas no banco de dados através da rota /save-password.
- Adicionado evento para atualizar automaticamente a lista de senhas salvas após gerar uma nova senha.
- Corrigido o componente **ListPasswords.vue** para tratar mensagens malformadas antes do JSON retornado pelo backend.
- Garantido que o componente **ListPasswords.vue** exiba as senhas salvas corretamente a partir da rota /list-passwords.
- Criado o componente **TokenValidationForm.vue** para validar tokens JWT através da rota /validate-token.
- Atualizado o **App.vue** para integrar o novo componente TokenValidationForm.vue no fluxo principal.
- Criado o componente **LoginForm.vue** para autenticação de usuários.
- Criado o componente **RegisterUser.vue** para cadastro de novos usuários.
- Criado o componente **UpdateUser.vue** para permitir a atualização de nome e email.
- Criado o componente **UpdatePassword.vue** para alteração de senha do usuário.
- Criado o componente **DeleteUser.vue** para exclusão de usuários e senhas associadas, com aviso de confirmação.
- Adicionado botão de logout no componente **PasswordGenerator.vue** para permitir que usuários saiam do sistema.
- Adicionado suporte ao Vue Router para gerenciamento de rotas no frontend.
- Atualizado o `App.vue` para integrar o Vue Router e redirecionar automaticamente `/` para `/login`.


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





















### **Funcionalidades Planejadas - Pendente**

#### Backend:
- Implementar validação de entradas mais robusta com **yup** ou **joi**.
- Implementar sistema de notificações em tempo real (ex.: **Socket.IO** para eventos).
- Adicionar verificações para evitar duplicatas ao salvar senhas.
- Configurar expiração automática de senhas antigas no banco.
- Configurar autenticação de dois fatores (2FA).
- Adicionar suporte para envio de notificações (ex.: e-mail ou SMS).
- Criar documentação automatizada das rotas com **OpenAPI/Swagger**.
- Adicionar suporte para múltiplos bancos de dados (PostgreSQL, etc.).
- Configurar HTTPS com certificados SSL.
- Implementar cache para melhorar o desempenho.
- Adicionar suporte para uploads e armazenamento seguro de arquivos.
- Integrar APIs externas relacionadas à segurança.

#### Frontend:
- Implementar paginação na listagem de senhas salvas.
- Adicionar funcionalidade de busca para encontrar senhas específicas.
- Melhorar personalização visual com tema escuro/claro.
- Criar gráficos interativos sobre o uso de senhas.
- Implementar notificações em tempo real após ações (ex.: geração/salvamento).
- Adicionar botão para copiar senhas geradas para a área de transferência.
- Configurar suporte offline para armazenamento local de senhas temporárias.
- Configurar tradução para múltiplos idiomas.
- Melhorar acessibilidade (ex.: suporte para leitores de tela).
- Adicionar sistema de exportação de senhas em formato CSV ou PDF.