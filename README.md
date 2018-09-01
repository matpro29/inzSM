inzSM
========================

Praca inżynierska

Jak uruchomić?
--------------

Należy wykonać następujące polecenia:

  * git clone git@github.com:matpro29/inzSM.git

  * cd inzSM

  * composer install

  * php bin/console doctrine:database:create

  * php bin/console doctrine:schema:create
  
  * php bin/console doctrine:fixture:load
  
  * php bin/console server:run
  
  Teraz możesz przejść do: http://127.0.0.1:8000
