# Déploiement en ligne — Boutique Quartier

Je ne peux pas naviguer sur railway.app ni créer de compte à votre place (restriction de
sécurité + ça touche votre compte réel). Tout le code nécessaire est déjà prêt
(`Dockerfile`, variables d'environnement) ; les étapes marquées **[VOUS]** sont à faire
dans votre navigateur, ça prend 5 minutes.

**Architecture retenue** : Railway (Docker + plugin MySQL), un seul service pour toute
l'application.

---

## 1. [VOUS] Créer le projet Railway

1. Allez sur [railway.app](https://railway.app) → **New Project**
2. **Deploy from GitHub repo** → sélectionnez `Anani23/boutique-quartier`
   (Railway détecte automatiquement le `Dockerfile` du dépôt, aucune config manuelle requise)
3. Dans le même projet, cliquez **+ New** → **Database** → **Add MySQL**

## 2. [VOUS] Configurer les variables d'environnement

Dans le service **boutique-quartier** (pas le service MySQL) → onglet **Variables** →
ajoutez :

| Variable | Valeur |
|---|---|
| `APP_NAME` | `Boutique Quartier` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | *(voir étape 3 ci-dessous)* |
| `APP_URL` | `https://VOTRE-DOMAINE.up.railway.app` *(Railway vous le donne après le 1er déploiement, à recopier ici ensuite)* |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | `${{MySQL.MYSQLHOST}}` |
| `DB_PORT` | `${{MySQL.MYSQLPORT}}` |
| `DB_DATABASE` | `${{MySQL.MYSQLDATABASE}}` |
| `DB_USERNAME` | `${{MySQL.MYSQLUSER}}` |
| `DB_PASSWORD` | `${{MySQL.MYSQLPASSWORD}}` |

Les valeurs `${{MySQL.XXX}}` sont des références Railway : tapez-les telles quelles,
Railway les remplace automatiquement par les vraies informations de connexion du
plugin MySQL que vous venez d'ajouter.

## 3. [VOUS] Générer votre APP_KEY

Sur votre machine, dans le dossier du projet :

```bash
php artisan key:generate --show
```

Copiez la valeur affichée (commence par `base64:`) dans la variable `APP_KEY` sur Railway.

## 4. [VOUS] Paiement d'abonnement (optionnel, pour activer CinetPay)

Si vous voulez que le paiement des abonnements (2 000 / 20 000 FCFA) fonctionne
réellement, créez un compte marchand sur [cinetpay.com](https://cinetpay.com), puis
ajoutez sur Railway :

| Variable | Valeur |
|---|---|
| `CINETPAY_API_KEY` | votre clé API CinetPay |
| `CINETPAY_SITE_ID` | votre site ID CinetPay |
| `CINETPAY_NOTIFY_URL` | `https://VOTRE-DOMAINE.up.railway.app/abonnement/webhook` |

Sans ça, l'application reste 100 % fonctionnelle : le bouton de paiement affiche
juste un message "configuration requise" au lieu de rediriger vers CinetPay.

## 5. [VOUS] Domaine public

Dans le service boutique-quartier → onglet **Settings** → **Networking** →
**Generate Domain**. Railway vous donne une URL en `*.up.railway.app`.
Recopiez-la dans la variable `APP_URL` (étape 2) et redéployez.

## 6. Vérification

Une fois déployé, Railway exécute automatiquement les migrations au démarrage
(commande définie dans le `Dockerfile`). Ouvrez l'URL générée : vous devez arriver
sur la page de connexion. Créez votre boutique via "Inscrire ma boutique" pour tester.

---

### Pourquoi je n'ai pas pu le faire moi-même

Railway (comme Fiverr) bloque la navigation automatisée sur son domaine, et créer un
compte ou lier une carte de paiement sont des actions que je ne dois jamais faire à
votre place. Tout le reste (Dockerfile, code, migrations) est déjà prêt et poussé sur
GitHub.
