# Laravel Blog - Workshop Debugging

![Blog Screenshot](https://beerandcode.com.br/assets/logo.png)

Um blog completo desenvolvido com Laravel, Tailwind CSS e Alpine.js, criado especificamente para o Workshop de Debugging.

## Sobre o projeto

Este projeto foi criado para servir como base para o Workshop de Debugging, onde os participantes aprenderão técnicas de depuração eficientes em aplicações Laravel. O blog possui diversas funcionalidades comuns em aplicações reais, permitindo simular e resolver problemas típicos de desenvolvimento.

> **Nota:** Este projeto foi gerado com assistência de IA (Claude) para fins educacionais.

## Recursos e funcionalidades

- Sistema de autenticação completo (Laravel Breeze)
- Blog público com design moderno e responsivo
- Painel administrativo para gerenciamento de conteúdo
- Posts com categorias múltiplas
- Sistema de comentários com respostas aninhadas e moderação
- Status de publicação (rascunho/publicado)
- Filtros e pesquisas

## Estrutura do banco de dados

- **Users**: Usuários do sistema (leitores e autores)
- **Categories**: Categorias para organizar os posts
- **Posts**: Conteúdo principal do blog
- **Comments**: Comentários em posts com suporte a respostas

## Tecnologias utilizadas

- **Laravel**: Framework PHP para o backend
- **Tailwind CSS**: Framework de CSS para o frontend
- **Alpine.js**: Framework JavaScript minimalista para interatividade
- **SQLite**: Banco de dados (pode ser alterado para MySQL/PostgreSQL)
- **Laravel Breeze**: Starter kit para autenticação

## Requisitos

- PHP 8.1 ou superior
- Composer
- Node.js e NPM
- Docker (opcional, para Laravel Sail)

## Instalação

### Usando Laravel Sail (recomendado):

```bash
# Clonar o repositório
git clone https://github.com/usuario/workshop-debugging.git
cd workshop-debugging

# Instalar dependências PHP
composer install

# Configurar o ambiente
cp .env.example .env
php artisan key:generate

# Iniciar os contêineres Docker
./vendor/bin/sail up -d

# Instalar dependências JavaScript e compilar assets
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev

# Executar migrações e seeders
./vendor/bin/sail artisan migrate:fresh --seed
```

### Instalação tradicional:

```bash
# Clonar o repositório
git clone https://github.com/usuario/workshop-debugging.git
cd workshop-debugging

# Instalar dependências PHP
composer install

# Configurar o ambiente
cp .env.example .env
php artisan key:generate

# Configurar o banco de dados no arquivo .env
# DB_CONNECTION=sqlite
# DB_DATABASE=/absolute/path/to/database.sqlite
# ou configurar para MySQL/PostgreSQL

# Instalar dependências JavaScript e compilar assets
npm install
npm run dev

# Executar migrações e seeders
php artisan migrate:fresh --seed

# Iniciar o servidor de desenvolvimento
php artisan serve
```

## Usuários padrão

Após executar os seeders, você terá acesso aos seguintes usuários:

- **Administrador**:
  - Email: admin@example.com
  - Senha: password

- **Usuários regulares**: Vários usuários criados automaticamente
  - Email: ver tabela users no banco de dados
  - Senha: password

## Exercícios de debugging comuns

Este projeto foi configurado com algumas "armadilhas" intencionais para praticar técnicas de debugging:

1. **Problemas de rota**: Conflitos de rota, parâmetros incorretos
2. **Queries N+1**: Áreas onde consultas ineficientes podem ser otimizadas
3. **Race conditions**: Em cenários de alto tráfego
4. **Manipulação de sessão**: Problemas com dados de sessão
5. **Validação de formulários**: Erros na validação de entrada de dados

## Como usar este projeto no workshop

1. Clone o repositório e configure o ambiente
2. Familiarize-se com a estrutura e funcionalidades
3. Siga as instruções do facilitador para os exercícios de debugging
4. Use as ferramentas de depuração (Tinker, Laravel Debugbar, Ray, etc.)
5. Aplique as soluções e verifique os resultados

## Ferramentas recomendadas para debugging

- Laravel Debugbar: `composer require barryvdh/laravel-debugbar --dev`
- Laravel Telescope: `composer require laravel/telescope --dev`
- Ray: `composer require spatie/laravel-ray --dev`
- XDebug: Configuração no PHP

## Contribuições

Este projeto é destinado principalmente para fins educacionais, mas contribuições são bem-vindas. Sinta-se à vontade para abrir issues ou enviar pull requests com melhorias ou correções.

## Licença

Este projeto está licenciado sob a [Licença MIT](LICENSE).

---

Criado com 💻 por Claude AI e mantido por Beer And Code para o Workshop de Debugging.
