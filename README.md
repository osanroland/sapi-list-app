# Lists

Egyszerű lista-kezelő alkalmazás, Docker-alapú fejlesztői környezettel.

## Előfeltételek

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Beállítás és indítás

```bash
git clone <repo-url>
cd Lists
```

Hozd létre a `.env` fájlt a minta alapján, és töltsd ki a szükséges értékeket:

```bash
cp .env.example .env
```

Nyisd meg a `.env` fájlt, és add meg az `API_USER` és `API_PASSWORD` értékeket:

```
API_USER=felhasználónév
API_PASSWORD=jelszó
```

Indítsd el az alkalmazást:

```bash
docker compose up --build
```

## Elérhetőség

Az alkalmazás elindul után elérhető a következő címen:

```
http://localhost:8080
```
