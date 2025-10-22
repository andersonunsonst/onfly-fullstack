# --- Etapa 1: Ambiente de desenvolvimento ---
FROM node:20 AS develop-stage

# Ativa o Corepack (Yarn vem desativado no Node 20+)
RUN corepack enable

WORKDIR /var/www/html/frontend/quasar-project

# Copia apenas arquivos de dependência para aproveitar cache
COPY quasar-project/package*.json ./

# Instala Quasar CLI global e dependências
RUN yarn global add @quasar/cli && \
    yarn install --ignore-scripts --non-interactive

# Copia o restante do código-fonte
COPY quasar-project/. .

# --- Etapa 2: Build de produção ---
FROM develop-stage AS build-stage

WORKDIR /var/www/html/frontend/quasar-project

# Compila o projeto Quasar
RUN quasar build

# --- Etapa 3: Servidor Nginx ---
FROM nginx:1.28-alpine AS production-stage

# Copia o build gerado para o diretório público do Nginx
COPY --from=build-stage /var/www/html/frontend/quasar-project/dist/spa /usr/share/nginx/html

EXPOSE 9090

CMD ["nginx", "-g", "daemon off;"]
