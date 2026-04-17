# Agenda Telefónica v1.0

Projecto de estudo desenvolvido para aprender desenvolvimento web com PHP, MySQL e Docker.
Esta é a versão 1 — simples e funcional. Será evoluída progressivamente.

---

## Sobre o projecto

Uma aplicação web que permite ao utilizador criar uma conta, fazer login e gerir uma agenda telefónica pessoal com operações CRUD completas (criar, listar, editar e eliminar contactos).

---

## Funcionalidades

- Registo de utilizador com senha encriptada (bcrypt)
- Login e logout com sessões PHP
- Listagem de contactos pessoais
- Adicionar novo contacto
- Editar contacto existente
- Eliminar contacto com confirmação
- Cada utilizador só vê os seus próprios contactos

---

## Tecnologias utilizadas

| Tecnologia | Versão | Função |
|---|---|---|
| PHP | 8.3 | Backend / lógica da aplicação |
| Apache | 2.4 | Servidor web |
| MySQL | 8.0 | Base de dados |
| phpMyAdmin | latest | Gestão visual da base de dados |
| Docker | — | Ambiente de desenvolvimento isolado |
| Docker Compose | — | Orquestração dos containers |

---

## Requisitos

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado e em execução
- Git

---

## Instalação e execução

### 1. Clonar o repositório

```bash
git clone https://github.com/ivaldinofortes/agenda-telefonica.git
cd agenda-telefonica
```

### 2. Arrancar os containers

```bash
docker compose up -d
```

### 3. Criar as tabelas na base de dados

Abre o phpMyAdmin em `http://localhost:8889`, selecciona `agenda_db`, vai ao separador **SQL** e executa:

```sql
CREATE TABLE utilizadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE contactos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilizador_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(150),
    notas TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id)
);
```

### 4. Aceder à aplicação

| Serviço | Endereço |
|---|---|
| Aplicação web | http://localhost:8888 |
| phpMyAdmin | http://localhost:8889 |

---

## Estrutura do projecto

```
agenda-telefonica/
├── docker-compose.yml
├── Dockerfile
├── README.md
└── src/
    ├── index.php
    ├── login.php
    ├── registo.php
    ├── logout.php
    ├── agenda.php
    ├── config/
    │   └── database.php
    └── includes/
        └── header.php
```

---

## Segurança aplicada

- Senhas encriptadas com `password_hash()` usando bcrypt
- Prepared statements em todas as queries SQL (previne SQL injection)
- `htmlspecialchars()` em todos os outputs (previne XSS)
- Verificação de sessão em todas as páginas protegidas
- Cada utilizador só acede aos seus próprios contactos

---

## Próximas versões

- [ ] Design melhorado com Bootstrap
- [ ] Pesquisa de contactos por nome
- [ ] Categorias de contactos
- [ ] Exportar contactos para CSV
- [ ] Versão Laravel

---

## Autor

**Ivaldino Fortes**
Projecto de estudo — versão 1.0
Abril 2026
