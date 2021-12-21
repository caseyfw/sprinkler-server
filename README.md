# Sprinkler Server

A backend server for an ESP8266 automated sprinkler system.

# Routes

-   GET `/api/v1/` Returns 200
-   GET `/api/v1/sprinklers` List of sprinklers.
-   GET `/api/v1/sprinkler/1` Sprinkler details.
-   GET `/api/v1/sprinkler/1/instruction` Fetch instruction for sprinkler - to be
    used by valve client.
-   POST `/api/v1/sprinkler` Create new sprinkler.
-   PUT `/api/v1/sprinkler/1` Update sprinkler.
-   DELETE `/api/v1/sprinkler/1` Delete sprinkler.
