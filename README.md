# Docker Compose Todo APP With Traefik

>Un ejemplo básico de un proxy inverso que utiliza Docker Compose y Traefik.

## Conceptos Rapidos
En circunstancias normales en el desarrollo local, podría servir una aplicación Laravel y una aplicación Vue en localhost pero en dos puertos diferentes. 
Por ejemplo, :8081 y :8082. 
Por lo general, esto no es gran cosa, pero a veces puede causar conflictos, ya que la mayoría de los navegadores consideran que diferentes puertos son iguales a nombres de dominio de nivel superior separados. Lo que significa que es posible que cosas como las cookies entre sitios y algunos métodos de autenticación no funcionen según lo previsto.

Lo que hace Traefik es actuar como proxy inverso. Asigna a sí mismo todo el tráfico del puerto: 80 en la máquina que lo ejecuta. A partir de ahí, configura nombres de dominio o rutas de acceso específicos para adjuntarlos a los servicios en su configuración de Docker. 
Traefik determina a qué servicio reenviar una solicitud según el valor de la URL que se utiliza y la envía al contenedor apropiado en consecuencia.

## Empezando

Clona este repositorio y asegúrate de que las dependencias estén instaladas. Desde la línea de comando en la raíz del proyecto:
```
cd backend && composer install
cd frontend && pnpm install
```

Luego corre todo con * Docker Compose: *

```
docker-compose up -d
```

Utilizando los valores proporcionados de forma predeterminada, ahora deberían estar disponibles dos sitios en el navegador.

- [todo-app.localhost](http://app.localhost): The JavaScript (Vue) app
- [todo-app.localhost/api](http://app.localhost/api): The PHP (Laravel) app