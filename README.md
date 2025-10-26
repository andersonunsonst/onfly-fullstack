Perfeito, Anderson 👌
Aqui está o **`README.md` pronto**, formatado em Markdown — é só copiar e colar na raiz do seu projeto (`backend/` ou no diretório principal).

---

````markdown
# ✈️ Onfly Desafio — Travel Request System

Aplicação **Full Stack** desenvolvida em **Laravel 12** (API REST) e **Quasar/Vue 3** (frontend) para gerenciamento de pedidos de viagem corporativa.

---

## 🧰 Tecnologias Utilizadas

- **Laravel 12** — Framework PHP
- **Laravel Sanctum** — Autenticação com tokens
- **MySQL 8** — Banco de dados
- **Docker / Docker Compose** — Ambiente de desenvolvimento
- **PHP 8.3** — Versão utilizada no container
- **Quasar / Vue 3** — Interface web SPA
- **Pinia** — Gerenciamento de estado no frontend
- **Axios** — Comunicação com a API
- **PHPUnit / Pest** — Testes automatizados

---
````

## 🚀 Instruções de Instalação

### 1️⃣ Clonar o repositório

```bash
git clone git@github.com:andersonunsonst/onfly-fullstack.git
cd onfly-desafio
````


### 2️⃣ Configurar o ambiente

Entre na pasta do **backend**:

```bash
cd backend
cp .env.example .env
```

Edite o arquivo `.env` e configure conforme abaixo:

```env
APP_NAME=OnflyDesafio
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=mysql-db
DB_PORT=3306
DB_DATABASE=onfly
DB_USERNAME=root
DB_PASSWORD=root

SANCTUM_STATEFUL_DOMAINS=localhost:8000
SESSION_DOMAIN=localhost
```

---

### 3️⃣ Subir os containers com Docker

Na raiz do projeto:

```bash
docker-compose up -d --build
```

Após os containers subirem:

```bash
docker exec -it laravel-backend bash
composer install
php artisan key:generate
php artisan migrate --seed
```

✅ O backend estará acessível em:
👉 **[http://localhost:8000](http://localhost:8000)**

---

### 4️⃣ Executar o frontend (Quasar)

Entre na pasta do frontend:

```bash
cd ../frontend/travel-dashboard
npm install
npx quasar dev
```

✅ O frontend estará acessível em:
👉 **[http://localhost:9000](http://localhost:9000)**

---

## 🧪 Executando os Testes

No container do backend:

```bash
php artisan test
```

Ou apenas os testes de feature:

```bash
php artisan test --testsuite=Feature
```

> Os testes cobrem autenticação, criação e listagem de viagens, controle de acesso por **roles** (`admin` / `user`), e atualização de status.

---

## ⚙️ Estrutura de Diretórios

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/TravelRequestController.php
│   │   └── Middleware/RoleMiddleware.php
│   ├── Models/TravelRequest.php
│   ├── Repositories/TravelRequestRepository.php
│   └── Services/TravelRequestService.php
├── database/
│   ├── factories/
│   │   ├── TravelRequestFactory.php
│   │   └── UserFactory.php
│   └── seeders/DatabaseSeeder.php
└── tests/
    ├── Feature/AuthTest.php
    └── Feature/TravelRequestTest.php

frontend/
└── travel-dashboard/
    ├── src/pages/LoginPage.vue
    ├── src/pages/DashboardPage.vue
    ├── src/components/TravelTable.vue
    └── src/stores/auth.js
```

---

## 🔐 Usuários de Exemplo

Durante o seed (`php artisan migrate --seed`), os seguintes usuários são criados:

| Tipo          | Email                                         | Senha    | Permissão                                 |
| ------------- | --------------------------------------------- | -------- | ----------------------------------------- |
| Admin         | [anderson@unsonst.dev](anderson@unsonst.dev)  | 123456   | Pode aprovar e cancelar viagens           |
| Usuário comum | [user@example.com](mailto:user@example.com)   | password | Pode criar e listar suas próprias viagens |

---

## 🧭 Rotas Principais da API

| Método  | Endpoint                           | Descrição                                       | Protegida |
| ------- | ---------------------------------- | ----------------------------------------------- | --------- |
| `POST`  | `/api/login`                       | Autentica o usuário e retorna o token           | ❌         |
| `POST`  | `/api/logout`                      | Encerra a sessão do usuário autenticado         | ✅         |
| `GET`   | `/api/me`                          | Retorna o usuário autenticado                   | ✅         |
| `GET`   | `/api/travel-requests`             | Lista pedidos (filtros: status, destino, datas) | ✅         |
| `POST`  | `/api/travel-requests`             | Cria um novo pedido de viagem                   | ✅         |
| `PATCH` | `/api/travel-requests/{id}/status` | Atualiza status (somente admin)                 | ✅         |

---

## 🧩 Variáveis de Ambiente Importantes

| Variável                   | Descrição                       |
| -------------------------- | ------------------------------- |
| `APP_URL`                  | URL base da aplicação backend   |
| `DB_*`                     | Configurações do banco de dados |
| `SANCTUM_STATEFUL_DOMAINS` | Domínios confiáveis para tokens |
| `SESSION_DOMAIN`           | Domínio do cookie de sessão     |

---

## 📝 Observações

* O projeto utiliza **Laravel Sanctum** para autenticação SPA com token.
* O **RoleMiddleware** garante que apenas administradores possam aprovar/cancelar pedidos.
* Datas e status são automaticamente formatados no frontend.
* O **frontend Quasar** utiliza **Pinia** para persistência do usuário autenticado.
* Os testes automatizados validam as principais regras de negócio e acesso.

---

## 👨‍💻 Autor

**Anderson Unsonst**
Desenvolvedor Full Stack — PHP / Laravel / Vue.js
📧 [anderson.unsonst@email.com](mailto:anderson.unsonst@email.com)
🌐 [linkedin.com/in/andersonunsonst](https://linkedin.com/in/andersonunsonst)
