# potential-crud-tdd
Crud Laravel 8 + TDD + VueJs + JWT

Este mini crud foi feito no intuito de atender os requisitos solicitados em https://gitlab.com/felipe.furtuoso538/pontential-crud e inclui autenticação por meio de Token (JWT)

O código é simplista e busca somente atender situações corriqueiras do desenvolvimento sem 
complexidade.

Inclui TDD do lado da API

--------------------
Para executar o projeto execute os passos abaixo

1- git clone https://github.com/adrianoGaspar/potential-crud-tdd.git

API
---
A API Laravel roda sobre o conteiner DOCKER fornecido pelo framework (SAIL) com MYSQL, REDIS, NGINX e PHP8

2- cd potential-crud-tdd/potential-crud-api
3- composer install
4- ./vendor/bin/sail up -d
5- ./vendor/bin/sail artisan migrate:refresh
6- ./vendor/bin/sail artisan db:seed

o endpoint da API é http://localhost/api

TESTES na API
---
Os testes foram desenvolvidos de maneira simplória apenas para demonstrar conhecimento da técnica e somente do lado da API

- Para rodar os tetes, no diretório raiz do projeto execute o comando 
- ./vendor/bin/sail test

O resultado esperado é:
 PASS  Tests\Unit\Test
  ✓ example

   PASS  Tests\Feature\DeveloperTest
  ✓ only logged in user can see all developers
  ✓ only logged in user can create a developer
  ✓ only logged in user can update a developer
  ✓ only logged in user can delete a developer

  Tests:  5 passed
  Time:   0.28s

APP - Frontend
---
A APP utiliza o framework VueJs 2 não roda sob conteiner DOCKER sendo necessário NODE e npm instalado na máquina

Subindo a APP:
- no diretório raiz do projeto execute os comandos
1- npm install
2- npm run serve

o endpoint da APP é http://localhost:8080

O usuário para login é:
email: admin@crud.com
senha: admin

Fale comigo pelo email adrianogaspar@gmail.com
