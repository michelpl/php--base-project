# 🧪 Hiring Challenge

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/1954140-6a0a051a-1bf6-4c19-9702-26058efaf04d?action=collection%2Ffork&collection-url=entityId%3D1954140-6a0a051a-1bf6-4c19-9702-26058efaf04d%26entityType%3Dcollection%26workspaceId%3D884cf7ff-ca99-4231-944e-d47ac4babda5)

## :memo: Api doc
[Api documentation](https://documenter.getpostman.com/view/1954140/2s8Z6yYZHS)

## 📈 Sequence diagram

![image](https://user-images.githubusercontent.com/6605776/210117293-618adc93-f112-4d6f-bb22-dff6fa2f807d.png)


## ⬆️ Dependencies

*   ``Docker`` 19.03.5+
*   ``Docker Compose`` 1.23.1+

## Make sure the following ports are avaliable in your server

*   ``Port 8000`` For Backend API
*   ``Port 3306`` For database

## 💚 Building the environment

Clone this repository

```bash
https://github.com/michelpl/hiring-challenge-docker.git
```

Enter in repository folder

```bash
cd hiring-challenge
```

Run de follwing command

```bash
make install
```

## 💚 Building the environment (manually)

Clone this repository

```bash
https://github.com/michelpl/hiring-challenge-docker.git
```

Enter in repository folder

```bash
cd hiring-challenge
```

Create the .env file

Use .env.example as example

```bash
cp src/.env.example src/.env
```

Running services
```bash
docker-compose up -d
```
Get the composer dependencies

```bash
docker-compose exec webapi composer update -vvv
```

Running Laravel migrations
```bash
docker-compose exec webapi php artisan migrate
```

## ⏪️ Stoping services

```bash
make stop
```

or

```bash
docker-compose down
```

## 🚀 Running Scheduled Tasks Manually
```bash
docker-compose exec webapi php artisan schedule:run 
```

## The Hiring Challenge API will be avaliable on
```bash
http://localhost:8000/api/V1
```

## 🧑‍💻 Consuming the API

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/1954140-6a0a051a-1bf6-4c19-9702-26058efaf04d?action=collection%2Ffork&collection-url=entityId%3D1954140-6a0a051a-1bf6-4c19-9702-26058efaf04d%26entityType%3Dcollection%26workspaceId%3D884cf7ff-ca99-4231-944e-d47ac4babda5)

## 🧑‍💻 Development environment

For changing project files in your machine, you need to run the following permissons

```bash
$ find src/ -type d -exec chmod 775 {} \;
$ find src/ -type f -exec chmod 664 {} \;
$ chown -R www-data:$USER src
```

or 

```bash
make permissions
```

## ✅ Running tests

```bash
make test
```

or

```bash
docker exec -it webapi php artisan test
```

## 🔊 Logs

You can find all the api logs on ``src/storage/logs``

Or just executing the following command

```bash
make showlogs
```
