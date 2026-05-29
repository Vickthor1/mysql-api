# 🛒 Mercadinho do Bairro — Laravel + MySQL + Open Food Facts

Sistema de catálogo de produtos para supermercado desenvolvido com Laravel, MySQL e Tailwind CSS. Permite cadastrar produtos internos e importar produtos reais via API externa, reunindo tudo em uma interface simples e agradável.

**Autor:** [Victor Hugo Fassini de Oliveira](https://github.com/Vickthor1)  
**Repositório:** [github.com/Vickthor1/mysql-api](https://github.com/Vickthor1/mysql-api)

---

## 🌎 API Utilizada — Open Food Facts

O projeto integra a **[Open Food Facts API](https://world.openfoodfacts.org/)**, uma base de dados colaborativa e gratuita com informações de produtos alimentícios do mundo inteiro, incluindo nome, imagem, código de barras e muito mais.

### Como a API funciona neste projeto

A API é usada de duas formas:

**1. Busca de produto por código de barras**

Usado na funcionalidade de importação. Ao acessar `/import-product`, o sistema consulta a API com um código de barras e salva o produto encontrado no banco local:

```
GET https://world.openfoodfacts.org/api/v0/product/{barcode}.json
```

Exemplo de resposta relevante:
```json
{
  "product": {
    "product_name": "Leite Integral",
    "code": "7891000100103",
    "image_url": "https://images.openfoodfacts.org/..."
  }
}
```

**2. Busca por nome (texto livre)**

Usada na pesquisa unificada. Quando o usuário digita um termo na busca, o sistema consulta simultaneamente o banco interno e a API externa:

```
GET https://world.openfoodfacts.org/cgi/search.pl?search_terms={termo}&json=1
```

Os resultados externos aparecem junto com os produtos internos na mesma listagem, com a tag **Externo** para diferenciá-los.

### Campos utilizados da API

| Campo da API               | Uso no sistema              |
|----------------------------|-----------------------------|
| `product_name`             | Nome do produto             |
| `image_url`                | Imagem exibida no card      |
| `image_front_small_url`    | Imagem na busca unificada   |
| `code`                     | Código de barras salvo      |

> A API é pública, gratuita e não exige chave de autenticação.

---

## ✨ Funcionalidades

- Cadastro de produtos internos com nome, preço e imagem (via URL)
- Importação de produtos reais via API (Open Food Facts)
- Busca unificada: banco interno + API externa na mesma lista
- Filtro por origem: produtos do mercado ou externos
- Ordenação por preço (menor → maior e maior → menor)
- Tags visuais diferenciando produtos internos e externos
- Interface responsiva estilo mercadinho de bairro

---

## 🛠️ Tecnologias

| Camada    | Tecnologia                          |
|-----------|-------------------------------------|
| Backend   | PHP 8 · Laravel · Eloquent ORM      |
| Banco     | MySQL (criado manualmente via SQL)  |
| Frontend  | Blade Templates · Tailwind CSS      |
| API       | Open Food Facts (pública, gratuita) |

---

## 📂 Estrutura do Projeto

```
app/
├── Http/Controllers/
│   └── ProductController.php    # Listagem, cadastro e importação
├── Models/
│   └── Product.php              # Model com fillable e accessor de imagem
└── Services/
    ├── OpenFoodService.php      # Comunicação com a API (por barcode)
    └── ProductSearchService.php # Busca unificada banco + API

resources/views/
├── layouts/
│   └── app.blade.php            # Layout principal com header e nav
├── products/
│   ├── index.blade.php          # Listagem com busca e filtros
│   └── create.blade.php        # Formulário de cadastro
└── home.blade.php               # Página inicial

routes/
└── web.php                      # Rotas da aplicação
```

---

## ⚙️ Como Rodar o Projeto

### Requisitos

- PHP 8 ou superior
- Composer
- MySQL
- Node.js e npm

### Passo 1 — Clonar o repositório

```bash
git clone https://github.com/Vickthor1/mysql-api.git
cd mysql-api
```

### Passo 2 — Criar o banco de dados manualmente no MySQL

```sql
CREATE DATABASE api_db;

USE api_db;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    price DECIMAL(8,2),
    stock INT DEFAULT 0,
    barcode VARCHAR(255) NULL,
    external_source VARCHAR(255) NULL,
    external_id VARCHAR(255) NULL,
    is_external TINYINT(1) DEFAULT 0,
    image VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

> O projeto usa banco criado manualmente, sem migrations do Laravel.

### Passo 3 — Instalar dependências

```bash
composer install
npm install
```

### Passo 4 — Configurar o `.env`

```bash
cp .env.example .env
php artisan key:generate
```

Edite o `.env` com os dados do seu banco:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api_db
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

### Passo 5 — Compilar os assets e iniciar o servidor

```bash
# Em um terminal
npm run dev

# Em outro terminal
php artisan serve
```

### Passo 6 — Acessar no navegador

```
http://127.0.0.1:8000
```

---

## 🗺️ Rotas Disponíveis

| Método | Rota               | Descrição                                 |
|--------|--------------------|-------------------------------------------|
| GET    | `/`                | Página inicial                            |
| GET    | `/products`        | Listagem de produtos com busca e filtros  |
| GET    | `/products/create` | Formulário para cadastrar produto interno |
| POST   | `/products`        | Salvar produto cadastrado manualmente     |
| GET    | `/import-product`  | Importar produto da Open Food Facts API   |

### Parâmetros de busca e filtro (`/products`)

| Parâmetro | Valores possíveis           | Descrição                     |
|-----------|-----------------------------|-------------------------------|
| `q`       | qualquer texto              | Busca por nome                |
| `sort`    | `price_asc` · `price_desc`  | Ordenação por preço           |
| `origin`  | `internal` · `external`     | Filtrar por origem do produto |

Exemplo:
```
/products?q=leite&sort=price_asc&origin=internal
```

---

## 👨‍💻 Autor

**Victor Hugo Fassini de Oliveira**  
[github.com/Vickthor1](https://github.com/Vickthor1)