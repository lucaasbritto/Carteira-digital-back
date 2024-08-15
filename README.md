
## Backend para o Projeto de Transferência de Carteiras
Este é o backend para o projeto de gerenciamento e transferência de carteiras. O backend foi construído usando Laravel e fornece uma API para gerenciar carteiras e realizar transferências entre elas, a transferêcnia pode ser para outros usuários que tenha carteira cadastrada, ou pra uma outra carteira que
o usuário criou pra ele.

## Requisitos
PHP 8.0 ou superior
Composer
MySQL
Node.js e npm (opcional, para gerenciamento de dependências)


## Instalação

1. Clone o repositório:   
        git clone https://github.com/lucaasbritto/card_back.git

2. Acesse o diretório do projeto:
    cd seu-repositorio

3. Configure o arquivo .env:
    Renomeie o arquivo .env.example para .env e configure as variáveis de ambiente conforme necessário,

4. Gere a chave de aplicativo do Laravel:
    php artisan key:generate

5. Execute as migrações:
    php artisan migrate

6. Inicie o servidor local:
    php artisan serve



## ENDPOINT DA API

### Listar Carteiras

- **URL:** `GET /api/wallets`
- **Descrição:** Retorna a lista de todas as carteiras do usuário logado.
- **Resposta:**
    ```json
    [
        {
            "id": 1,
            "cod": "0001",
            "userName": "João"
        },
        {
            "id": 2,
            "cod": "0002",
            "userName": "Maria"
        }
    ]
    ```

### Listar Usuários com Carteira

- **URL:** `GET /api/walletUsers`
- **Descrição:** Retorna a lista de usuários que possuem carteiras.
- **Resposta:**
    ```json
    [
        {
            "id": 1,
            "name": "João",
            "email": "joao@example.com"
        },
        {
            "id": 2,
            "name": "Maria",
            "email": "maria@example.com"
        }
    ]
    ```

### Criar Nova Carteira

- **URL:** `POST /api/wallet-create`
- **Descrição:** Cria uma nova carteira para o usuário logado.
- **Requisição:**
    ```json
    {
        "name": "Minha Nova Carteira",
        "initial_balance": 100
    }
    ```
- **Resposta:**
    ```json
    {
        "id": 3,
        "cod": "0003",
        "name": "Minha Nova Carteira",
        "balance": 100
    }
    ```

### Obter Detalhes da Carteira

- **URL:** `GET /api/wallet/{id}`
- **Descrição:** Retorna os detalhes de uma carteira específica pelo ID.
- **Resposta:**
    ```json
    {
        "id": 1,
        "cod": "0001",
        "name": "Carteira João",
        "balance": 150
    }
    ```

### Buscar Pessoa pela Chave PIX

- **URL:** `GET /api/wallet-pix`
- **Descrição:** Retorna as informações da pessoa associada a uma chave PIX.
- **Requisição:**
    ```json
    {
        "pix_key": "12345678901"
    }
    ```
- **Resposta:**
    ```json
    {
        "name": "Ana",
        "wallet_code": "0004"
    }
    ```

### Realizar Transferência

- **URL:** `POST /api/transfer`
- **Descrição:** Realiza uma transferência de valor entre carteiras ou via PIX.
- **Requisição:**
    ```json
    {
        "sender_wallet_id": 1,
        "receiver_wallet_id": 2,
        "quantia": 50,
        "transfer_type": "wallet"
    }
    ```
- **Resposta:**
    ```json
    {
        "success": true,
        "message": "Transferência realizada com sucesso."
    }
    ```

### Obter Transações

- **URL:** `GET /api/transactions`
- **Descrição:** Retorna uma lista de transações realizadas.
- **Resposta:**
    ```json
    [
        {
            "id": 1,
            "sender_wallet_id": 1,
            "receiver_wallet_id": 2,
            "amount": 50,
            "date": "2024-08-15T12:00:00Z"
        }
    ]
    ```

### Obter Usuário Atual

- **URL:** `GET /api/user`
- **Descrição:** Retorna o ID do usuário atualmente autenticado.
- **Resposta:**
    ```json
    {
        "id": 1,
        "name": "João",
        "email": "joao@example.com"
    }
    ```

### Login

- **URL:** `POST /api/login`
- **Descrição:** Autentica um usuário e retorna um token de acesso.
- **Requisição:**
    ```json
    {
        "email": "user@example.com",
        "password": "password"
    }
    ```
- **Resposta:**
    ```json
    {
        "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwibmFtZSI6Ikp1YW4iLCJpYXQiOjE2MjY0NzcwMDAsImV4cCI6MTYyNjQ4MDYwMH0.SXJ7T3E1zZC4R9sydS2ujZlA5gmlYqSTLsCC5Z4R0CI"
    }
    ```

### Registro

- **URL:** `POST /api/register`
- **Descrição:** Registra um novo usuário e cria uma conta.
- **Requisição:**
    ```json
    {
        "name": "João",
        "email": "joao@example.com",
        "password": "password",
        "confirm_password": "password"
    }
    ```
- **Resposta:**
    ```json
    {
        "success": true,
        "message": "Usuário registrado com sucesso."
    }
    ```

### Obter Taxas de Câmbio

- **URL:** `GET /api/exchange-rates`
- **Descrição:** Retorna as taxas de câmbio atuais, como a cotação do USD para BRL.
- **Resposta:**
    ```json
    {
        "usd_to_brl": 5.30
    }
    ```