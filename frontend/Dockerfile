# Use a imagem oficial do Node.js como base
FROM node:16

# Configure o diretório de trabalho dentro do contêiner
WORKDIR /app

# Copie os arquivos de configuração (package.json e package-lock.json) para o contêiner
COPY package*.json ./

# Instale as dependências do Node.js
RUN npm install

# Garanta que o Vue Router seja instalado
RUN npm install vue-router

# Instale o Vue CLI globalmente para gerenciar o projeto Vue
RUN npm install -g @vue/cli

# Copie todo o código do frontend para o contêiner
COPY . .

# Compile os arquivos do Vue para produção (ou deixe para modo de desenvolvimento)
RUN npm run build

# Exponha a porta padrão para o servidor do Vue
EXPOSE 8080

# Comando para iniciar o servidor do Vue no modo de desenvolvimento
CMD ["npm", "run", "serve"]
