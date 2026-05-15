# Documentação Técnica — ERP CONTRACTS

# Estrutura da Aplicação

O sistema foi desenvolvido utilizando Laravel no backend e Vue.js no frontend com Inertia.js.

A aplicação foi organizada utilizando separação clara de responsabilidades para facilitar manutenção e escalabilidade.

Estrutura principal:

- Controllers
- Models
- Services
- Pages Vue
- Layouts
- Rotas organizadas

---

# Organização das Camadas

## Controllers

Os controllers possuem responsabilidade apenas de:

- Receber requisições
- Validar dados
- Delegar regras de negócio
- Retornar respostas

Foi evitada a concentração de lógica de negócio diretamente nos controllers.

---

## Models

Os models foram utilizados para:

- Relacionamentos
- Fillable
- Organização de entidades

Relacionamentos implementados:

### Client

- hasMany Contracts

### Contract

- belongsTo Client
- hasMany ContractItems

### ContractItem

- belongsTo Contract
- belongsTo Service

### Service

- hasMany ContractItems

---

## Services

As regras de negócio foram centralizadas em uma camada de Services.

Exemplo:

```text
app/Services/ContractCalculatorService.php
```

Responsabilidades:

- cálculo total do contrato
- aplicação de descontos
- centralização das regras financeiras

Essa abordagem facilita:

- manutenção
- testes
- expansão futura

---

# Decisões Técnicas Tomadas

## Utilização de Service Layer

A principal regra de negócio do projeto foi isolada em uma camada de serviço para evitar lógica complexa nos controllers.

Benefícios:

- baixo acoplamento
- reutilização
- organização
- facilidade de manutenção

---

## Utilização de SPA com Inertia.js

Foi utilizada arquitetura SPA utilizando:

- Laravel
- Vue.js
- Inertia.js

Benefícios:

- navegação sem reload
- melhor experiência do usuário
- integração simples entre backend e frontend

---

## Utilização de Vuetify

Vuetify foi utilizado para acelerar desenvolvimento da interface e manter padronização visual.

---

## Docker

A aplicação foi containerizada utilizando Docker para facilitar:

- padronização do ambiente
- execução local
- facilidade de setup

---

# Implementação das Regras de Negócio

## Regra de desconto

Foi implementada uma regra simples de desconto:

- contratos acima de R$1000 recebem 10% de desconto automático

A lógica foi implementada dentro de:

```text
ContractCalculatorService
```

Objetivo:

- permitir expansão futura
- facilitar alteração de regras
- centralizar cálculos

---

## Regra de status

Contratos cancelados não podem ser editados.

Essa regra foi implementada diretamente no controller de contratos para impedir alterações indevidas.

---

# Melhorias Futuras

Com mais tempo, poderiam ser implementadas:

- testes automatizados
- histórico de alterações
- versionamento de contratos
- dashboard com métricas
- filtros avançados
- autenticação baseada em permissões
- API REST completa
- logs de auditoria
- cache de consultas
- notificações

---

# Considerações Finais

O projeto foi desenvolvido priorizando:

- organização de código
- separação de responsabilidades
- regras de negócio
- facilidade de manutenção
- experiência do usuário

A arquitetura foi planejada para permitir crescimento e expansão futura da aplicação.