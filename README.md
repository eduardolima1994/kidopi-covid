# App Kidopi Covid-19, Acompanhamento de Casos
App construído em PHP, Javascript, HTML5 e CSS3. 

## 🧑🏽‍💻 Acesso 

Para acessar o resultado final da aplicação, utilize o seguinte link:
https://kidopi-covid19.000webhostapp.com

## 🚀 Começando

Essas instruções permitirão que você obtenha uma cópia do projeto em operação na sua máquina local para fins de desenvolvimento e teste.

### 📋 Pré-requisitos

```
PHP 8.1.12
```
```
Composer version 2.3.10
```
```
MySQL 5.0.12
```

### 📜 Banco de Dados

Nome do Banco de Dados a ser criado:

```
kidopicovid
```
Dados para criação da tabela:

```
CREATE TABLE access_logs (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  access_datetime DATETIME,
  country VARCHAR(255)
);
```

### 🔧 Instalação

Clone este repositório e acesse o diretório (kidopi-covid):
```
cd kidopi-covid
```
Instale as dependências:
```
composer install
```
Crie um arquivo .env na raiz do projeto para configuração do banco de dados:
```
DB_HOST=hostName
DB_NAME=databaseName
DB_USER=userName
DB_PASSWORD=password
```
