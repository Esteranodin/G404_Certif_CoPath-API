SUPPRIMER etat nullable de ce qui ne le sera pas

Supprimer tous les imports HS + tout le code commenté inutile (repo, ...)


** finir dashboard not. tout mettre en français ou anglais && uniformiser (cf. les set des actions dans USER crud)
+ faire en sorte que si campagne ou scénario créé via dashboard id admin en user_id ?

*** ajouter contraintes de validation (Assertions)

** ** TESTS UNITAIRES (cf. projet API-TestUnitaire)

gerer la secu des roles avec possibilité de créer des campagnes que en super user par exemple


NOTES POUR IMAGES (music peut etre pas car juste un lien)
image via form-data et pas un json (dans postman voir form-data a la place de raw key/value)
et coté front = objet formdata

update image pas patch mais post ==> donc 2 routes post (1 création et une pour update) car formdata

UPLOADS dans .gitignore (suppression) + gérer l'upload avec Vich ? 

----------------------------------------------------------------------------
----------------------------------------------------------------------------

FINIR DTO Scenario pour recherche

GARDER autant de routes pour scenarios

----------------------------------------------------------------------------
----------------------------------------------------------------------------
Penser message erreur bannissement
code: 401
message: "Votre compte a été suspendu. Contactez l'administrateur."