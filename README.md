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

## 🚀 Instruções de Instalação

### 1️⃣ Clonar o repositório

```bash
git clone https://github.com/seu-usuario/onfly-desafio.git
cd onfly-desafio
2️⃣ Configurar o ambiente
Entre na pasta do backend:

bash
Copiar código
cd backend
cp .env.example .env
No arquivo .env, configure as variáveis principais:

env
Copiar código
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
3️⃣ Subir os containers com Docker
Na raiz do projeto (onde está o docker-compose.yml):

bash
Copiar código
docker-compose up -d --build
Após a subida dos containers:

bash
Copiar código
docker exec -it laravel-backend bash
composer install
php artisan key:generate
php artisan migrate --seed
✅ O backend estará disponível em:
http://localhost:8000

4️⃣ Executar o frontend (Quasar)
Entre na pasta do frontend:

bash
Copiar código
cd ../frontend/travel-dashboard
yarn install
yarn quasar dev
✅ O frontend estará disponível em:
http://localhost:9000 (ou porta indicada no terminal)

🧪 Executando os Testes
No container do backend:

bash
Copiar código
php artisan test
Ou apenas os de feature:

bash
Copiar código
php artisan test --testsuite=Feature
Os testes cobrem autenticação, criação e listagem de viagens, controle de acesso por role (admin/user), e atualização de status.

⚙️ Estrutura de Diretórios
bash
Copiar código
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
🔐 Usuários de Exemplo
Durante o seed (php artisan migrate --seed), dois usuários são criados:

Tipo	Email	Senha	Permissão
Admin	admin@example.com	password	Pode aprovar/cancelar viagens
Usuário comum	user@example.com	password	Pode criar e listar viagens

🧭 Rotas Principais da API
Método	Endpoint	Descrição	Protegida
POST	/api/login	Autentica usuário e retorna token	❌
POST	/api/logout	Encerra sessão do usuário	✅
GET	/api/me	Retorna usuário autenticado	✅
GET	/api/travel-requests	Lista pedidos (filtro por status, destino, datas)	✅
POST	/api/travel-requests	Cria novo pedido de viagem	✅
PATCH	/api/travel-requests/{id}/status	Atualiza status (somente admin)	✅

🧩 Variáveis Importantes
Variável	Descrição
APP_URL	URL base do backend
DB_*	Configurações do banco
SANCTUM_STATEFUL_DOMAINS	Domínios confiáveis para tokens
SESSION_DOMAIN	Domínio do cookie de sessão

📝 Observações
O projeto utiliza Laravel Sanctum para autenticação por token SPA.

O Role Middleware garante que apenas usuários admin possam aprovar ou cancelar pedidos.

As datas e status são formatados automaticamente no frontend.

O frontend Quasar foi configurado com Pinia para persistência do usuário autenticado.

👨‍💻 Autor
Anderson Unsonst
Desenvolvedor Full Stack PHP / Laravel / Vue.js
📧 [seu-email@exemplo.com]
🌐 linkedin.com/in/andersonunsonst