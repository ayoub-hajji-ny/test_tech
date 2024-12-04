Projet Symfony - Première expérience
Bienvenue dans mon projet Symfony ! Ce projet marque ma première expérience avec ce framework. Voici les instructions pour démarrer l'application, exécuter les tests, ainsi qu'une explication rapide des choix techniques que j'ai faits.

Instructions pour lancer l'application
Tout d'abord, clonez le projet avec la commande suivante : git clone  et placez-vous dans le dossier du projet avec cd test_tech . Ensuite, installez les dépendances nécessaires en exécutant composer install. Assurez-vous d'avoir installé PHP, Composer, et la Symfony CLI sur votre machine avant de procéder.

Pour configurer la base de données, ouvrez le fichier .env et modifiez l'URL de votre base de données en fonction de votre configuration locale (exemple : DATABASE_URL="mysql://username:password@127.0.0.1:3306/nom_base"). Ensuite, créez la base de données en exécutant php bin/console doctrine:database:create.
Pour démarrer l'application, lancez le serveur Symfony avec la commande symfony server:start. L'application sera alors disponible sur http://127.0.0.1:8000.

Instructions pour exécuter les tests
Si vous souhaitez exécuter des tests unitaires, vous devez d'abord installer PHPUnit en exécutant composer require --dev phpunit/phpunit. Une fois PHPUnit installé, vous pouvez exécuter les tests en utilisant la commande php bin/phpunit.
