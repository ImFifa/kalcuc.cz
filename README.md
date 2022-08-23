# Skeleton website (template)

Starter package for building websites on top of K2D.CZ CMS

## Installation
- Create your own repository from this one by clicking **Use this template** green button
- Install **Composer** dependencies - `$ composer install`
- Install **NPM** dependencies - `$ npm install`
- Build front-end **assets**:
	- Development: `npm run start` or `npm run watch`
	- Production: `npm run build`
- **Create file** `app/config/server/local.neon` from `app/config/server/local.template.neon` and **configure database and dbal**
- **Run** `$ bin/console migration:continue`
- **Enjoy**

## Github Actions (phpstan and codesniffer)

### CI
- **Move** `.github/workflows-inactive/ci.yaml` to `.github/workflows/ci.yaml`
- ensure in `.github/workflows/ci.yaml` option `on > push > branches` is set to correct branch
- setup GitHub secret `SSH_PRIVATE_KEY` with your GitHub private key, so the build can install private packages

### Deploy (SSH)
- **Move** `.github/workflows-inactive/deploy.yaml` to `.github/workflows/deploy.yaml`
- **Setup GitHub secrets**:
  - `DEPLOY_HOST` - IP or domain
  - `DEPLOY_USER` - user
  - `DEPLOY_PORT` - port
  - `DEPLOY_KEY` - paired with server you are deploying to
  - `SSH_PRIVATE_KEY` - GitHub private for private packages installation
- **Create file** `app/config/server/stable.neon` (should be copy of `local.neon`)
- **Change** `/www/skeleton-website` to correspond with location on the server
- Using `.env` file is recommended and has to be uploaded to server **before** first deploy

