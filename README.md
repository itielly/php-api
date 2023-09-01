Instalação do projeto
  - composer install


Banco de dados
  - como não foi feito o deploy e nem sistema de migrations, exportei um script do banco que está na raiz do projeto.
  - se faz necessário a criação de um banco local em seu pc, seguindo os comandos do script.
  - caso você crie um banco com o nome, senha ou demais informações diferentes, é necessário alterá-los em "app/utils/Connection.php".
  - se der problema de conexão ao fazer uma requisição, provavelmente as informações do seu banco estão divergentes das configurações na Connection.
  - para ter certeza que as informações do seu banco coincidem com as da Connection tente conectar em seu banco pelo DataGrip, Dbeaver e etc.


Rodar api:
  - php -S localhost:8000 -t public
  - indico que rodar nesta mesma porta, pois é a que o front estará esperando.


Exemplo do que as requisições esperam:
  - POST
      {
        "name": "Bests Chopin",
        "dayEvent": "2024-05-30",
        "initHour": "15:00",
        "finishHour": "17:30",
        "description": "Um evento inesquicível para os amantes de Chopin"
      }

  - PUT (sendo obrigatório apenas id e a informação que você deseja alterar)
    {
      "id": 84,
      "name": "Bests Chopin",
      "dayEvent": "2024-05-30",
      "initHour": "15:00",
      "finishHour": "17:30",
      "description": "Um evento inesquicível para os amantes de Chopin"
    }

  - DELETE
    {
      "id": 45
    }