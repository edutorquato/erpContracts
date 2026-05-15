# ERP CONTRACTS

Sistema de gestão de contratos e serviços desenvolvido como teste técnico utilizando Laravel e Vue.js.

O sistema permite o gerenciamento completo de clientes, serviços e contratos, incluindo cálculo dinâmico de valores mensais e aplicação de regras de negócio.

---

# 🚀 Funcionalidades

## 👤 Clientes

- CRUD completo de clientes
- Cadastro de:
  - Nome
  - CPF/CNPJ
  - Email
  - Status
- Validação de CPF/CNPJ
- Validação de email
- Máscara automática para CPF/CNPJ
- Busca e paginação

---

## 🛠 Serviços

- CRUD completo de serviços
- Cadastro de:
  - Nome
  - Valor base mensal
- Busca e paginação
- Atualização dinâmica sem reload

---

## 📄 Contratos

- CRUD completo de contratos
- Relacionamento entre clientes e contratos
- Cadastro de:
  - Cliente
  - Data de início
  - Data de término
  - Status
- Adição e remoção de serviços vinculados
- Bloqueio de edição para contratos cancelados

---

## 📦 Itens do Contrato

Cada contrato pode possuir múltiplos serviços vinculados contendo:

- Serviço
- Quantidade
- Valor unitário personalizado

---

## 💰 Cálculo Dinâmico do Contrato

O valor total do contrato é calculado dinamicamente com base nos itens cadastrados.

### Exemplo

| Serviço | Quantidade | Valor |
|---|---|---|
| Serviço A | 2 | R$ 100 |
| Serviço B | 1 | R$ 200 |

### Total

```text
R$ 400,00
```

---

# 🧠 Regra de Negócio

Foi implementada uma regra adicional utilizando Service Layer.

### Regra aplicada

- Contratos acima de R$1000 recebem 10% de desconto automático.

A lógica foi implementada de forma desacoplada para facilitar futuras expansões e manutenção do sistema.

---

# 🏗 Arquitetura

O projeto foi estruturado utilizando separação clara de responsabilidades:

- Controllers
- Models
- Services
- Pages Vue
- Layouts
- Rotas organizadas

A lógica de negócio foi centralizada em Services para manter os controllers mais limpos e facilitar manutenção futura.

---

# 🐳 Tecnologias

## ⚙ Backend

- PHP 8
- Laravel 12
- MySQL

## 🎨 Frontend

- Vue.js 3
- Inertia.js
- Vuetify

## 🔐 Autenticação

- Laravel Breeze

## 🐳 Infraestrutura

- Docker
- Docker Compose

---

# ✨ Experiência do Usuário

O frontend foi desenvolvido utilizando arquitetura SPA com Inertia.js, proporcionando:

- Navegação sem reload
- Atualização dinâmica dos dados
- Snackbar de feedback
- Busca em tabelas
- Paginação
- Interface responsiva

---

# ⚙ Como Executar o Projeto

## 📥 Clonar repositório

```bash
git clone URL_DO_REPOSITORIO
```

---

## 📄 Configurar ambiente

```bash
cp .env.example .env
```

---

## 🐳 Subir containers

```bash
docker compose up -d
```

---

## 📦 Instalar dependências

```bash
composer install

npm install
```

---

## 🔑 Gerar chave da aplicação

```bash
php artisan key:generate
```

---

## 🗄 Rodar migrations

```bash
php artisan migrate
```

---

## 🚀 Executar frontend

```bash
npm run dev
```

---

# 🎯 Objetivo do Projeto

O projeto foi desenvolvido com foco em:

- Organização de código
- Estruturação de regras de negócio
- Separação de responsabilidades
- Arquitetura limpa
- Experiência SPA com Vue.js
- Facilidade de manutenção e expansão futura