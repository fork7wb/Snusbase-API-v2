# Snusbase-API-v2

Vous trouverez ci-dessous la documentation de tous les points de terminaison d’API privée. Vous pouvez également utiliser des outils pré-écrits tels que :

- [h8mail](https://github.com/khast3x/h8mail) - Outil de chasse aux violations de l’OSINT et du mot de passe par e-mail, localement ou à l’aide de services premium
- [pure_javascript.html](lien_vers_autre_projet](https://github.com/fork7wb/Snusbase-API-v2/blob/main/pure_javascript.html)https://github.com/fork7wb/Snusbase-API-v2/blob/main/pure_javascript.html) - Un exemple purement javascript de la façon d’interagir avec l’API Snusbase et toutes ses fonctionnalités
- [api_curl.php](https://github.com/fork7wb/Snusbase-API-v2/blob/main/api_curl.php) - Une fonction PHP qui utilise php-curl pour interagir avec l’API Snusbase et toutes ses fonctionnalités

### Champs
Vous pouvez actuellement effectuer une recherche dans 6 champs (« nom d’utilisateur », « e-mail », « lastip », « hachage », « mot de passe » et « nom »).

Par défaut, nous afficherons 8 champs (« nom d’utilisateur », « email », « lastip », « hash », « salt », « mot de passe », « nom » et « base de données »).

Si vous avez utilisé l’API v2/v1 et que vous disposiez d’une logique pour supprimer les champs «  », " " et NULL, ceux-ci sont maintenant analysés sur le backend.

### Erreurs
Sur le point de terminaison /v3/search, nous répondons toujours avec un champ « error » et un champ « reason ». S’il s’agit de mauvaises requêtes, elles doivent être descriptives, si elles sont dues à des erreurs internes ou à une maintenance, elles ressembleront aux éléments ci-dessous et se résoudront généralement d’elles-mêmes dans les 10 secondes suivant la première apparition de l’erreur.

```json
{
"error": "failed_on_search",
"reason": "Search failed while waiting for backend"
}
```

## Recherche de base
### Demander
```json
curl -H "content-type: application/json" -H "authorization: API_KEY" -X POST -d '{"type":"email","term":"test@test.com"}' https://api.snusbase.com/v3/search
```
### Requête
```json
{
  "type":"email",
  "term":"test@%.___",
  "wildcard":true
}
```
### Réponse
```json
{
  "results": [
    {"email":"test@glofox.com","username":"test@glofox.com","hash":"7f2ababa423061c509f4923dd04b6cf1","name":"Glofox Test","db":"GLOFOX_COM_2M_FITNESS_032020"},
    {"email":"test@testian123.com","username":"testian123","password":"123456","lastip":"70.81.142.30","hash":"a49dce509af07a3d003798ce5b800647","db":"FLING_COM_39M_DATING_2011"}
  ],
  "took":0.79,
  "size":54577
}
```
Vous pouvez échapper les deux caractères génériques en les faisant précéder d’une barre oblique inverse (« % », « _ »).

## Recherche de limite/décalage
Ici, nous utilisons le caractère « % » pour spécifier que tout ce qui commence par « test@ » doit être inclus dans le premier cycle d’analyse. Le deuxième tour vérifie pour un . suivi de 3 caractères, représentés par les trois « _ ».
### Demander
```json
curl -H "content-type: application/json" -H "authorization: API_KEY" -X POST -d '{"type":"email","term":"test@%.___","wildcard":true}' https://api.snusbase.com/v3/search
```
### Requête
```json
{
  "type":"email",
  "term":"test@%.___",
  "wildcard":true
}
```
### Réponse
```json
{
  "results": [
    {"email":"test@glofox.com","username":"test@glofox.com","hash":"7f2ababa423061c509f4923dd04b6cf1","name":"Glofox Test","db":"GLOFOX_COM_2M_FITNESS_032020"},
    {"email":"test@testian123.com","username":"testian123","password":"123456","lastip":"70.81.142.30","hash":"a49dce509af07a3d003798ce5b800647","db":"FLING_COM_39M_DATING_2011"}
  ],
  "took":0.79,
  "size":54577
}
```
Vous pouvez échapper les deux caractères génériques en les faisant précéder d’une barre oblique inverse (« % », « _ »).

## Recherche de limite/décalage
Étant donné que le dernier a eu 54 577 résultats, vous voudrez peut-être le paginer. Vous pouvez le faire en ajoutant les options limit et offset.
### Demander
```json
curl -H "content-type: application/json" -H "authorization: API_KEY" -X POST -d '{"type":"email","term":"test@%.___","wildcard":true,"limit":5,"offset":0}' https://api.snusbase.com/v3/search
```
### Requête
```json
{
  "type":"email",
  "term":"test@%.___",
  "wildcard":true,
  "limit":5,
  "offset":0
}
```
### Réponse
```json
{
  "results": [
    {"email":"test@gmail.com","password":"newstandard","db":"IMGUR_COM_2M_SOCIAL_092013"},
    {"email":"test@fannrem.com","password":"warrior","db":"IMGUR_COM_2M_SOCIAL_092013"},
    {"email":"test@digitalruby.com","password":"test!2345","db":"IMGUR_COM_2M_SOCIAL_092013"},
    {"email":"test@comcast.net","password":"helloo","db":"IMGUR_COM_2M_SOCIAL_092013"},
    {"email":"test@aol.com","password":"test101test","db":"IMGUR_COM_2M_SOCIAL_092013"}
  ],
  "took":0.642,
  "size":5
}
```
Gardez à l’esprit que cela ne vous donne pas le nombre complet de résultats et ne fait (presque) rien pour améliorer les performances de recherche, nous vous recommandons donc généralement de le faire via un logiciel sur votre propre frontend.

## Recherche de hachage
Pour plus de clarté, cette API stocke les hachages qui ont déjà été piratés. Il ne tente pas réellement de déchiffrer les hachages en natif et est mis à jour une fois tous les trimestres.
### Demander
```json
curl -H "content-type: application/json" -H "authorization: API_KEY" -X POST -d '{"hash": "164a645acf2f0b3ac49e7139602c29d6"}' https://api.snusbase.com/v3/hash
```
### Requête
```json
{
  "hash": "164a645acf2f0b3ac49e7139602c29d6"
}
```
### Réponse
```json
{
  "found":true,
  "term":"164a645acf2f0b3ac49e7139602c29d6",
  "password":"Password132"
}
```

## IP Whois API
### Demander
```json
curl -H "content-type: application/json" -H "authorization: API_KEY" -X POST -d '{"address": "12.34.56.78"}' https://api.snusbase.com/v3/ipwhois
```
### Requête
```json
{
  "address": "12.34.56.78"
}
```
### Réponse
```json
{
  "status":"success",
  "country":"United States",
  "countryCode":"US",
  "region":"WV",
  "regionName":"West Virginia",
  "city":"Huntington",
  "zip":"25701",
  "timezone":"America/New_York",
  "isp":"AT&T Services",
  "org":"Northwestern Mutualvb2e-is OR",
  "as":"AS7018 AT&T Services, Inc.",
  "mobile":false,
  "proxy":false
}
```
Si vous passez un nom de domaine dans le champ « adresse », il le résoudra en une adresse IP et fonctionnera de la même manière.















