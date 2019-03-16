praca-inzynierska
========================

Praca inżynierska

Temat: Internetowy system wspomagający prowadzenie studiów wirtualnych

Autorzy: Sylwia Podkościelna, Mateusz Proskura

Jak uruchomić?
--------------

Należy wykonać następujące polecenia:

  * git clone git@github.com:matpro29/praca-inzynierska.git

  * cd inzSM

  * composer install
  
  * yarn install
  
  * yarn add bootstrap
  
  * yarn add jquery
  
  * yarn add popper.js

  * yarn add owl.carousel jquery
  
  * yarn add flatpickr
  
  * yarn encore dev
  
  * W .env zamień "DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name"

  * php bin/console doctrine:database:create

  * php bin/console doctrine:schema:create
  
  * php bin/console doctrine:fixture:load
  
  * php bin/console server:run
  
  Teraz możesz przejść do: http://127.0.0.1:8000
