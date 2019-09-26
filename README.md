# Liveclicker Test 

## Docker Usage

To get started, make sure you have [Docker installed](https://docs.docker.com/docker-for-mac/install/) on your system.
Open a terminal and run `docker-compose build && docker-compose up -d`. 

Open up your browser of choice to [http://localhost:8080](http://localhost:8080) and you should see your app running as intended. 

Containers created and their ports are as follows:

- **nginx** - `:8080`
- **mysql** - `:3306`
- **php** - `:9000`

## Laravel Lumen Usage

Plase run this commands on terminal to run app: 

- Generate .env file
```
cp .env.example .env
```
Check presence of this values on .env file to connect DB:

```
APP_URL=http://localhost
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
```

- Connect to docker container
```
docker exec -it php sh
```

- Install composer dependencies
```
cd ..
composer install
```

- Run migrations to db on docker container
```
php artisan migrate
```

- Run seeds on db on docker container
```
php arisan db:seed
```

- Run test on docker container
```
vendor/bin/phpunit
```
## Documentation
Swagger Api documentation on [http://localhost:8080/api/documentation](http://localhost:8080/api/documentation)

- User to test app
```
user : dev@liveclicker.com​
password : liveclicker
```

## Contact

>Email: sbarcelona@gmail.com
