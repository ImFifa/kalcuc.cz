# Skeleton website (template)

Starter package for building websites on top of K2D.CZ CMS

- Create your own repository from this one by clicking **Use this template** green button
- Install **Composer** dependencies - `$ composer install`
- Install **NPM** dependencies - `$ npm install`
- Build front-end **assets**:
	- Development: `npm run start` or `npm run watch`
	- Production: `npm run build`
- **Create file** `app/config/server/local.neon` from `app/config/server/local.template.neon` and **configure database connection**
- **Run** `$ php bin/console migration:continue`
- **Enjoy**
