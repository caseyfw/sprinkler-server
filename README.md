# Sprinkler Server

A backend server for an ESP8266 automated sprinkler system.

# Routes

- GET `/api/v1/` Returns 200
- GET `/api/v1/sprinklers` List of sprinklers.
- GET `/api/v1/sprinkler/1` Sprinkler details.
- GET `/api/v1/sprinkler/1/instruction` Fetch instruction for sprinkler - to be
  used by valve client.
- POST `/api/v1/sprinkler` Create new sprinkler.
- PUT `/api/v1/sprinkler/1` Update sprinkler.
- DELETE `/api/v1/sprinkler/1` Delete sprinkler.

# Getting started

This project requires PHP 8. You will also need the xml and sqlite3 PHP
extensions.

Install dependencies using composer:

```
$ composer install
```

Create the database and its schema using the symfony console:

```
$ ./bin/console doctrine:database:create
...
$ ./bin/console doctrine:schema:create
```

Start the server using the PHP built-in webserver:

```
$ php -S localhost:8000 -t public/
```

You can now hit the health endpoint to ensure it is working correctly:

```
$ curl http://localhost:8000/api/v1/
{"status":"ok"}
```

# Adding a sprinkler

Use the following request to add a sprinkler:

```
$ curl --data '{"name":"front-garden","state":"off"}' \
    http://localhost:8000/api/v1/sprinkler
{"status":"success","message":"Created sprinkler 1","sprinkler":{"id":1,"name":"front-garden","state":"off"}}
```

# Turning a sprinkler on or off

Simply update the sprinkler to have the state `turning_on` or `turning_off`:

```
$ curl -X PUT --data '{"state":"turning_on"}' \
    http://localhost:8000/api/v1/sprinkler/1
{"status":"success","message":"Updated sprinkler 1","sprinkler":{"id":1,"name":"front-garden","state":"turning_on"}}
```

# Retrieving instructions for a sprinkler

Use the following endpoint to fetch the sprinkler's instruction:

```
$ curl http://localhost:8000/api/v1/sprinkler/1/instruction
{"instruction":"turn_on"}
```

This command has the side-effect of mutating the state of the sprinkler in
acknowledgement of the instruction being carried out.
