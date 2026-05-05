# FidelityX

> **Plataforma de Fidelização para Comerciantes Locais** — Sistema SaaS moderno, desacoplado e escalável, construído com arquitetura MVC manual em PHP.

![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat-square&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat-square&logo=mysql)
![Architecture](https://img.shields.io/badge/Architecture-MVC-blue?style=flat-square)
![Status](https://img.shields.io/badge/Status-Beta-orange?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

---

## 📋 Visão Geral

**FidelityX** é uma plataforma SaaS (Software-as-a-Service) que capacita comerciantes locais a implementar programas de fidelização robustos e inteligentes. Desenvolvido com foco em **escalabilidade**, **segurança** e **arquitetura limpa**, o sistema oferece uma base sólida para crescimento futuro.

Recentemente refatorado para adotar o padrão **MVC com separação clara de responsabilidades**, FidelityX demonstra as melhores práticas de engenharia de software: prepared statements, validação de entrada, hash de senhas e uma estrutura que facilita testes e manutenção.

---

## ✨ Features

### Funcionalidades Implementadas

- ✅ **Cadastro de Lojistas** — Registro completo com validação de documentos (CPF/CNPJ)
- ✅ **Autenticação Segura** — Login com hash BCRYPT e sessões persistentes
- ✅ **Validação de Documentos** — Algoritmos robustos de validação de CPF e CNPJ
- ✅ **Gestão de Planos** — Suporte para múltiplos planos (Free, Pro)
- ✅ **Sistema de Status** — Ativação/desativação de comerciantes
- ✅ **Arquitetura Desacoplada** — Separação clara entre Controllers, Models e Validators
- ✅ **Banco de Dados Normalizado** — Estrutura escalável com índices otimizados

---

## 🛠️ Tech Stack

| Componente | Tecnologia | Versão |
|---|---|---|
| **Linguagem** | PHP | 8.1+ |
| **Banco de Dados** | MySQL | 8.0+ |
| **ORM** | PDO (prepared statements) | nativa |
| **Autenticação** | BCRYPT | nativa |
| **Dependency Manager** | Composer | 2.0+ |
| **Charset** | UTF-8mb4 | unicode_ci |
| **Engine** | InnoDB | transacional |

---

## 🏗️ Arquitetura

### Padrão MVC Desacoplado

FidelityX implementa uma arquitetura MVC **manual** que prioriza clareza, testabilidade e escalabilidade:

```
src/
├── Controllers/       → Orquestração de requisições HTTP
├── Models/            → Lógica de persistência (BD)
└── Validators/        → Validação de dados (regras de negócio)
```

### Fluxo de Requisição

```
[HTTP Request]
      ↓
[Controller] → Recebe entrada, delega validação
      ↓
[Validator] → Valida regras de negócio
      ↓
[Model] → Persiste dados com prepared statements
      ↓
[View] → Renderiza resposta
```

### Exemplo: Cadastro de Lojista

**MerchantController.php** orquestra o fluxo:
- `handleRegister()` → captura entrada, sanitiza dados
- `DocumentValidator::isValid()` → valida CPF/CNPJ
- `MerchantModel::create()` → persiste com prepared statements

```php
// Validação rigorosa
$isValid = DocumentValidator::isValid($document);
if (!$isValid) {
    header('Location: index.php?url=merchant/register&error=documento_invalido');
    exit;
}

// Persistência segura com prepared statements
$data = [
    ':email' => $email,
    ':password_hash' => password_hash($password, PASSWORD_BCRYPT),
    // ... outros campos
];
$this->merchantModel->create($data);
```

### Validação de Documentos

O módulo `DocumentValidator` implementa algoritmos de validação official:
- **CPF**: Validação de dígitos verificadores com regra do módulo 11
- **CNPJ**: Validação completa com pesos decrescentes

```php
DocumentValidator::isValid($document); // true/false
```

---

## 🚀 Getting Started

### Pré-requisitos

- PHP 8.1 ou superior
- MySQL 8.0 ou superior
- Composer 2.0+
- Git

### Instalação

#### 1. Clone o Repositório

```bash
git clone https://github.com/sousa7tz/fidelityx.git
cd fidelityx
```

#### 2. Instale as Dependências

```bash
composer install
```

#### 3. Configure o Banco de Dados

```bash
# Crie o banco e importe o schema
mysql -u root -p < database/schema.sql
```

O arquivo [database/schema.sql](database/schema.sql) cria automaticamente:
- Database `fidelityx`
- Tabelas de Merchants e Customers
- Índices para performance
- Charset UTF-8mb4

#### 4. Configure Variáveis de Ambiente

```bash
# Copie o arquivo de exemplo
cp .env.example .env

# Edite com suas credenciais
nano .env
```

`.env`:
```
DB_HOST=localhost
DB_USER=root
DB_PASS=sua_senha
DB_NAME=fidelityx
DB_PORT=3306
```

#### 5. Inicie o Servidor Local

```bash
# PHP Built-in Server
php -S localhost:8000 -t public/

# Ou com Nginx/Apache (configure document root para ./public/)
```

Acesse: `http://localhost:8000`

---

## 📚 Estrutura de Diretórios

```
fidelityx/
├── database/
│   ├── schema.sql              # Schema do banco de dados
│   └── migrations/             # (Futuro) Versionamento de schema
├── docs/
│   ├── adr/                    # Architecture Decision Records
│   └── db/                     # Documentação de banco de dados
├── public/
│   ├── index.php               # Entry point
│   ├── assets/
│   ├── css/                    # Estilos (SCSS compilado)
│   └── js/                     # Frontend (TypeScript compilado)
├── src/
│   ├── Controllers/            # Orquestração de requisições
│   ├── Models/                 # Camada de dados
│   ├── Validators/             # Validação de regras de negócio
│   └── Database.php            # Singleton de conexão PDO
├── views/
│   ├── auth/                   # Templates de autenticação
│   └── errors/                 # Templates de erro (400, 404, 500...)
├── config/                     # Configurações da aplicação
├── composer.json               # Dependências PHP
├── tsconfig.json               # Configuração TypeScript
└── README.md                   # Este arquivo
```

---

## 🔧 Desenvolvimento

### Rodando Testes

```bash
# Testes unitários (em breve)
composer test
```

### Code Quality

```bash
# PHPStan (análise estática)
composer analyse

# PHP-CS-Fixer (formatting)
composer format
```

### Estrutura de Controllers

```php
namespace App\Controllers;

class MerchantController {
    private $db;
    private $merchantModel;

    public function renderRegister() { /* Renderiza view */ }
    public function handleRegister() { /* Processa POST */ }
}
```

---

## 🔐 Segurança

- ✅ **Prepared Statements** — Proteção contra SQL Injection
- ✅ **BCRYPT Hashing** — Senhas armazenadas com hash seguro
- ✅ **Input Sanitization** — `filter_input()` para todos os dados
- ✅ **Validação de Negócio** — Documentos validados com algoritmos official
- ✅ **UTF-8mb4** — Proteção contra ataques Unicode

---

## 📊 Roadmap

### Q2 2026 - Beta

- [ ] Dashboard de Lojistas
- [ ] Sistema de Pontos (earn/redeem)
- [ ] API REST para integrações
- [ ] Mobile App (React Native)

### Q3 2026 - v1.0

- [ ] Analytics em tempo real
- [ ] Integração com gateways de pagamento
- [ ] Webhooks para eventos de loja

### Q4 2026+

- [ ] Machine Learning para recomendações
- [ ] Multi-tenant avançado
- [ ] Marketplace de integrações

---

## 🤝 Contribuindo

Contribuições são bem-vindas! Para reportar bugs ou sugerir features:

1. **Issues**: [GitHub Issues](https://github.com/sousa7tz/fidelityx/issues)
2. **Pull Requests**: Siga o padrão de branch `feature/nome-da-feature`

### Padrão de Commits

```
feat: adiciona nova feature
fix: corrige bug
refactor: refatora código
docs: atualiza documentação
test: adiciona/atualiza testes
```

---

## 📄 Documentação

- [ADR - Decisões Arquiteturais](docs/adr/001-documents-validation.md)
- [Schema do Banco de Dados](docs/db/schema-explanation.md)
- [API Reference](docs/api/) (em desenvolvimento)

---

## 📝 Licença

MIT License — Veja [LICENSE](LICENSE) para detalhes.

---

## 👤 Autor

**Emanuel Sousa** — [@sousa7tz](https://github.com/sousa7tz)

---

## 📞 Suporte

- **Email**: suporte@fidelityx.com
- **Docs**: [fidelityx.dev](https://fidelityx.dev)
- **Issues**: [GitHub Issues](https://github.com/sousa7tz/fidelityx/issues)

---

**Última atualização**: Maio 2026 | **Versão**: 0.1.0-beta