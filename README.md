# Component catalog web project

List of components used along the differents Bysidecar's web projects. 

The goal of this project is to have a kind of repository/catalog to have the capability of reuse this components.
 
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

### Enviroment variables

* You must set an environment variable to set the propper DB connection string. You have to substitute the values for the correct params to match your DB requirements

  ```bash
  set COMPONENTS_DB_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7
  ```

* Another option is to create an `.env.local` file and set this values in this file. DO NOT PUSH THIS FILE!

### Run in a docker image
* Set DB connection string

```yml
ENV COMPONENTS_DB_URL=mysql://db_user:db_password@[docker_mysql_container_ip]:3306/db_name?serverVersion=5.7
```
* To check the mysql container IP, get `IPAddress` value.

```bash
docker inspect [docker_network] -f "{{json .NetworkSettings.Networks }}"
```


```bash
docker image build -t comp --build-arg app_env=dev .
docker container run --name comp -p 9000:80 --network mysql_default comp
```

### Create local DB environment (alternative to docker)

* Build a MySQL environment in local DB, and get the parameters to connect.

* In `.env` file set the propper connection string

  ```env
  set COMPONENTS_DB_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7
  ```

* Execute Symfony Migrations script to recreate the DB environment

  ```sql
  php bin/console doctrine:migrations:migrate
  ```

* Generate an encoded password

  ```bash
  php bin/console security:encode-password '[your_pass]' 'App\Entity\User'

  # Output:

  Symfony Password Encoder Utility
  ================================

  ------------------ --------------------------------------------------------------------------------------------------- 
    Key                Value                                                                                              
  ------------------ --------------------------------------------------------------------------------------------------- 
    Encoder used       Symfony\Component\Security\Core\Encoder\MigratingPasswordEncoder                                   
    Encoded password   $argon2id$v=19$m=65536,t=4,p=1$mKAiealrB/+WDq4zwwQ7mg$5dzVZZZZZZZZpXymzdBrqhnkz442MQThfBGjghh7wI  
  ------------------ --------------------------------------------------------------------------------------------------- 

  ! [NOTE] Self-salting encoder used: the encoder generated its own built-in salt.                
  ```

  Copy the encoded password result and when the DB is created, update your user with this value.

* Create entries for users

  ```sql
  insert into components.user (username, password, email, is_active, roles, salt) values
  ('admin', 'test', 'admin@bysidecar.com', '1', '{\"ROLES\": \"ROLE_ADMIN\"}', null),
  ('test', 'test', 'test@bysidecar.com', '1', '{\"ROLES\": \"ROLE_USER\"}', null);
  ```