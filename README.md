## Getting started


Clona este repositorio y asegúrate de que las dependencias estén instaladas. Desde la línea de comando en la raíz del proyecto:
```
cd backend && composer install
cd frontend && pnpm install
```

Luego corre todo con *Docker Compose: *

```
docker-compose up -d
```

Utilizando los valores proporcionados de forma predeterminada, ahora deberían estar disponibles dos sitios en el navegador.

- [app.localhost](http://app.localhost): The JavaScript (Vue) app
- [app.localhost/api](http://app.localhost/api): The PHP (Laravel) app