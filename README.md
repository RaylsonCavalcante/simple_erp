# 🛒 Sistema de Vendas

Este projeto é um sistema de vendas desenvolvido em **Laravel 12**, com funcionalidades como:

- Cadastro e edição de produtos
- Cadastro e edição de clientes
- Cadastro e edição de vendas
- Adição de itens
- Geração de parcelas e vencimentos
- Emissão de PDF

---

## 🚀 Tecnologias

- Laravel 12
- MySQL
- JavaScript (jQuery)
- DOMPDF para geração de PDF

---

## ⚙️ Como instalar

### 1. Clone o repositório

```bash
git clone https://github.com/seu-usuario/seu-repositorio.git
cd seu-repositorio
```

### 2. Instale as dependências

```bash
composer install
```

### 3. Copie o configure o arquivo .env

```bash
cp .env.example .env
```

### 4. Edite o .env com as informações do banco de dados:

DB_DATABASE=simple_erp <br>
DB_USERNAME=seu_usuario <br>
DB_PASSWORD=sua_senha

### 5. Gerar chave da aplicação

```bash
php artisan key:generate
```

### 6. Executar as migrations

```bash
php artisan migrate
```

### 7. Rodar o servidor local

```bash
php artisan serve
```
