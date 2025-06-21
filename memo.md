SUPPRIMER etat nullable de ce qui ne le sera pas

Supprimer tous les imports HS + tout le code commenté inutile (repo, ...)


** faire role et connection pour dashboard
+ finir dashboard not. tout mettre en français ou anglais && uniformiser (cf. les set des actions dans USER crud)
+ faire en sorte que si campagne ou scénario créé via dashboard id admin en user_id ?

*** ajouter contraintes de validation (Assertions)

** ** TESTS UNITAIRES (cf. projet API-TestUnitaire)

gerer la secu des roles avec possibilité de créer des campagnes que en super user par exemple


Entités music et img créées maintenant il faut pouvoir faire en sorte que seul l'utlisateur qui a crée le scénar puisse ajouter/modifier image ou music sur le scenar selectionner via le datapersister du scenario

NOTES POUR IMAGES (music peut etre pas car juste un lien)
image via form-data et pas un json (dans postman voir form-data a la place de raw key/value)
et coté front = objet formdata

update image pas patch mais post ==> donc 2 routes post (1 création et une pour update) car formdata

UPLOADS dans .gitignore + gérer l'upload


----------------------------------------------------------------------------
----------------------------------------------------------------------------

FINIR DTO Scenario pour recherche
Cache React Query → Performance front optimisée
Rate Limiting → Protection API
Tests unitaires → Qualité code

----------------------------------------------------------------------------
----------------------------------------------------------------------------

que se passe t il si je me co avec un banni ? 

PBLEME requete 400 favoris et note