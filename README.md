# Component catalog web project

## Installation

### Install Symfony CLI

Install symfony cli and add it tho the path. Check the succesful message to do it in your system.
[Check this link](https://github.com/symfony/symfony-installer)

```bash
wget https://get.symfony.com/cli/installer -O - | bash
```

Add symfony to your user path

```bash
# Use it as a local file:
/home/[your_user]/.symfony/bin/symfony

#Or add the following line to your shell configuration file:
export PATH="$HOME/.symfony/bin:$PATH"

#Or install it globally on your system:
mv /home/jose/.symfony/bin/symfony /usr/local/bin/symfony
```
Create the project

```bash
symfony new comp_catalog
```

- TIP: if you have problems with permission using composer, try this: 

```bash
sudo chown -R $USER ~/.composer
```

### Install composer depenencies

Execute composer install to install the project locally.

```bash
php composer install
```

### Install webpack dependencies

This project uses [symfony's webpack encore](https://symfony.com/doc/current/frontend.html) to manage project assets.

```bash
# yarn install dev --watch
yarn add dev
# or
npm install
```

### Run a local development server

Use the following command to use Symfony's `symfony/web-server-bundle`.

```bash
# Starts a local development server on the 8000 port
symfony server:start --no-tls
```

Execute encore to update imports of js files and functions.

```bash
yarn encore dev --watch
# or
npm run-script watch
```