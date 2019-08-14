#Comment contribuer au projet

Tu viens d’arriver dans l’équipe (yeah !) mais tu ne connais pas encore nos méthodes de travail et de développement. 

Suis ce guide pour comprendre notre workflow, les principes à retenir lorsque tu développes et autres informations utiles à la maintenance du code de notre application.

# Ce qu’il faut savoir
## Que développons-nous ?

Comme tu le sais surement déjà, l’application que nous développons permet la gestion des tâches, grâce à l’ajout de nouvelles tâches, leur modification, et leur suppression. 

Nous utilisons même cette application en interne afin de nous aider à savoir ou nous en sommes dans nos projets. 

L’idée est de pouvoir développer la gestion d’une équipe, l’entre aide avec nos collègues, et la gestion des tâches. En effet, chacun ajoute sa tâche, et indique que celle-ci est terminée quand celle-ci a été réalisée. Plutôt simple non ?

***

# Travailler en équipe

Maintenant que tu fais partie de l’équipe, tu dois t’informer de quelques règles à respecter afin de nous aider efficacement dans le développement. Rien de bien compliqué, tu verras.

## Gérer le code existant

Bien que nous sommes une petite équipe, chaque développeur à ses préférences, ses idées, son caractère. Et il est très facile de se perdre dans notre code si chacun d’entre nous décide de travailler à sa façon, sans respecter les règles déjà en place.

Et, tu le sais déjà, moins le code est homogène et plus il est facile de créer des bugs, et de rendre la maintenance difficile. Aussi, il faut éviter qu’un seul collègue se retrouve avec une charge trop importante de travail, ce qui risque de l’entraîner à bâcler son travail.

Afin d’éviter tous ces problèmes, voici comment on fonctionne :
* On se répartis les tâches en fonction des emplois du temps des différents collègues
* On respecte quelques règles de nommage
* On rédige des messages clair et concis à chaque commit git
* On relit les Pull Request (PR) des autres collègues pour s’assurer que l’on ne va pas push des erreurs
* On aide ses collègues en cas de blocage sur un sujet

Parmi toutes ces règles, certaines ont un intérêt à être expliquées. C’est ce que nous allons voir maintenant.

## Répartir les tâches

Cela semble logique, mais répartir le travail dans une équipe est un sujet très important. Une mauvaise répartition entraîne des tensions inter équipe, ce qui impacte la productivité et la progression du développement.

Bien sûr, tout le monde ne travaille pas au même rythme. Il faut donc s’adapter à chacun. De fait, une personne qui travaille vite peut s’occuper de plus de tâches qu’une personne qui travaille moins vite. 

Cependant, ce qui compte, ce n’est pas la quantité, mais la qualité. Tu connais sûrement le concept du **LESS IS MORE**. Travaille à ton rythme, mais fais en sorte que ton travail soit de qualité.

## Respecter les règles de nommage

Pour que l’on se perde pas dans nos fichiers ou nos fonctions, il est important de bien respecter ces quelques règles de nommage.

1. **Respecter les règles de PSR 1-2** (je mets celui-là en gras tellement c’est important)
1. Nommer les fonctions pour ce qu’elles font vraiment mais en restant concis. Rien de pire qu’une fonction à rallonge.
1. Nommer les PR de manière à ce que l’on comprenne quel module ou fonctions sont travaillé(s). Les PR doivent être rédigées en anglais et les titres et corps doivent être parlant, concis et résumer le travail effectué.
1. Nommer les branches de la manière suivante : feature/branch-name ou bug/branch-name.

**Note: Il faut créer une branche par issue afin de conserver l'homogénéité entre les issues et les commits.**

Nous utilisons PhpStorm pour le développement avec le plugin Symfony intégré. De fait, php-cs-fixer (le plugin de gestion des PSR) est déjà installé. PhpStorm te proposera déjà une indentation et un nommage suivant les règles de PSR. Cependant, si tu ne souhaites pas utiliser PhpStorm, assure toi d'installer php-cs-fixer.

Si tu as des questions, ou que tu n’es pas sûr(e) sur un point, mieux vaut demander que se tromper ;)

***

# Gérer Git

Tu n’es pas sans savoir qu’il est très important de garder une trace de son développement à l’aide d’un outil de versioning.

## Créer des messages de commit

Garder une trace de ce qui a été fait, et de ce qui est en développement est très important, comme expliqué au dessus. 

Mais il est surtout important de garder une trace claire du travail effectué. En effet, s’il est impossible de s’y retrouver dans les différents commits ajoutés à l’application et de savoir ce qu’il a été fait à quel moment, garder une trace devient de fait sans intérêt.

Pour nous aider à documenter le développement de notre application, nous créons généralement des issues sur le repository GitHub du projet. Ainsi, à chaque commit, on peut lier le commit à l’issue concernée, ce qui se trouve être très utile. 

**Note: Quoiqu'il arrive, les messages de commits doivent être rédigés en anglais. Le français dans le code, c'est le mal !!!**

Voici donc les étapes à réaliser avant de commit une nouvelle feature :
1. Créer l’issue liée à la feature, en s’assurant que celle-ci n’existe pas déjà.
1. Récupérer le numéro de l’issue (généralement indiqué par un #)
1. Sur le projet, lancer les commandes git add puis git commit -m “#N°issue + message du commit”
1. Lancer un git push.

***

# Développer
## Gérer l’ajout et la modification de code

L’application est vouée à évoluer. Pour que cette évolution se passe bien, il faut garder une homogénéité dans le code.
Lors de l’ajout et de la modification de code, veilles à bien respecter les règles mises en avant précédemment.

## Documenter son code

Le développement, ce n’est pas que l’ajout ou la suppression de ligne de code. Il est aussi très important de documenter son code afin que les autres développeurs ne soient pas perdus une fois qu’il est demandé d’améliorer une feature créée par un collègue.

Pour peu que ta feature soit en plus assez technique, il devient vite compliquée de la retravailler en cas de besoin.

Pour ce faire, il est demandé à chacun de nous de créer des minis documentations qui relatent d’une feature en particulier, en plus de l’ajout de documentation dans le code.

# Pérenniser la qualité de l’application

Afin de garder un oeil sur la qualité de l’application, nous avons utilisons un outil de relecture du code, qui nous permet de savoir si les nouvelles PR comportent des erreurs majeures, et ce qu’il faut modifier pour obtenir un code de bonne qualité.

Tu trouveras l’analyse ici. A chaque nouveau commit, ou nouvelle PR, regardes les résultats de l’analyse pour savoir si tu n’as pas fait d’erreur majeure. Si l’analyse remonte de mauvaise résultat, corrige-les avant de passer à une autre feature.

Nous aimons aussi analyser les performances de notre application à l’aide de BlackFire. Cet outil nous permet de vérifier les performances de notre application. 

## Suivre la qualité du code

Pour suivre la qualité du code, nous utilisons Codacy. Je te laisse te rendre sur ce lien. 

Tu peux voir différents outils d’analyse du code. Pour t’orienter, suis les indications suivantes. Ce qu’il faut regarder :
1. La note totale du projet, représenter par un macaron contenant une lettre.
1. L’onglet **Security**, afin de t’assurer qu’il n’y ait pas de problème majeur lié à la sécurité.
1. Le tableau des **issues breakdowns**. Il te permet de mieux te rendre compte des domaines dans lesquels l’analyse rencontre des erreurs, et ce qu’il faut améliorer. Tu peux cliquer sur les catégories du tableau pour qu’il filtre les messages d’erreur.

Tu peux suivre les erreurs indiquées et les résoudre au fur et à mesure de ton travail.

**Note: Codacy remonte parfois des erreurs liées à Symfony. Tu ne peux donc rien y faire, tu peux les ignorer.**

## Analyser les performances

Pour tester les performances de l’application, nous utilisons BlackFire. 
BlackFire te permet de voir les performances de ton application à différents niveaux :
1. Le temps de réponse de la requête HTTP.
1. Le I/O wait qui correspond au temps d’attente du système pour l’écriture ou la lecture des données.
1. Le temps de réponse du CPU.
1. Les Network Calls.
1. Le temps liés aux requêtes SQL.

Cette analyse récupère les performances sur l’ensemble des fichiers de l’application. Cependant, certains, et pas de moindre, car il s’agit généralement de ceux qui prennent le plus de ressources, sont en fait des fichiers relatifs à Symfony. Ne pouvant pas modifier ces fichiers là, il faut donc se rabattre sur ceux que l’on peut modifier, c’est-à-dire ceux qui contiennent notre application.

Pour connaître les statistiques de fichiers que tu peux modifier, recherches ta feature, ou ton fichier dans la barre de recherche. Puis clique sur le nom du fichier dans le tableau.

BlackFire te montre alors un tableau détaillé avec les données de performances pour ta feature.

***

# Tester l’application

Afin de s’assurer que l’application fonctionne correctement, et éviter les régressions, nous avons implémenter des tests unitaires et fonctionnels à l’aide de PHPUnit.

Cela signifie qu’à chaque ajout ou modification de code, il te faudra vérifier que les tests fonctionnent toujours et en implémenter de nouveau si nécessaire.

Il est important de tester l’application et de s’assurer que les tests ne sont pas “cassés” lors de la modification de code. 
Tu trouveras tous les tests dans le dossiers /tests du projet.

