# Mailer

Les messages de notifications Jeunot sont envoyés par email.

## Environnement de développement

Les e-mails en environnement de développement ne sont pas réellement envoyés et sont interceptés par la librairie `schickling/mailcatcher` disponible sur l'url suivante : http://jeunot.localhost:1080.

## Environnement de production

En production, nous utilisons [sendinblue](https://brevo.com). Il est nécéssaire de définir une variable d'environnement `MAILER_DSN` spécifiant quel est le DSN à utiliser : `sendinblue+api://KEY@default`.
La clef d'API est à récupérer dans le compte sendinblue.
