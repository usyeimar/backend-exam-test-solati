# Proxy Todo APP With Traefik

> Un ejemplo básico de un proxy inverso que utiliza Docker Compose y Traefik.

## Conceptos rápidos

En circunstancias normales en el desarrollo local, podría servir una aplicación Laravel y una aplicación Vue
en `localhost` pero en dos puertos diferentes.

Por ejemplo, `:8081` y `:8082`.

Por lo general, esto no es gran cosa, pero a veces puede causar conflictos, ya que la mayoría de los navegadores
consideran que diferentes puertos son iguales a nombres de dominio de nivel superior separados.
Lo que significa que es posible que cosas como las cookies entre sitios y algunos métodos de autenticación no funcionen
según lo previsto.

Lo que hace Traefik es actuar como proxy inverso. Asigna a sí mismo todo el tráfico del puerto: `80` en la máquina que
lo ejecuta. A partir de ahí, configura nombres de dominio o rutas de acceso específicos para adjuntarlos a los servicios
en su configuración de Docker.
Traefik determina a qué servicio reenviar una solicitud según el valor de la URL que se utiliza y la envía al contenedor
apropiado en consecuencia.

## Empezando

Recuerda que antes de iniciar con la clonacion del proyecto debes tener instalado Docker y Docker Compose en tu
maquina.([Guia de instalacion](https://docs.docker.com/desktop/install/windows-install/))

Clona este repositorio y asegúrate de que las dependencias estén instaladas. Desde la línea de comando en la raíz del
proyecto:

```
cd backend && composer install
cd frontend && pnpm install
```

Luego corre todo con ``Docker Compose: `` desde la raíz del proyecto:

```
docker-compose up -d
```

Utilizando los valores proporcionados de forma predeterminada, ahora deberían estar disponibles dos sitios en el
navegador.

- [todo-app.localhost](http://todo-app.localhost): The JavaScript (Vue) app
- [todo-app.localhost/api](http://todo-app.localhost/api): The PHP (Laravel) app
- [todo-app.mail.localhost](http://todo-app.mail.localhost): The Mail(Mailpit) dashboard

# Referencia a la API

- Ejecuta la colección de Postman

  [<img src="https://run.pstmn.io/button.svg" alt="Run In Postman" style="width: 128px; height: 32px;">](https://app.getpostman.com/run-collection/14969501-31fac7be-60ad-4188-a53d-2863a977eab5?action=collection%2Ffork&source=rip_markdown&collection-url=entityId%3D14969501-31fac7be-60ad-4188-a53d-2863a977eab5%26entityType%3Dcollection%26workspaceId%3D08af0b8c-1618-460b-a1d3-f902ca38ca53)

- Documentacion
  [Link](https://documenter.getpostman.com/view/14969501/2sA3JDiks9)

# Guia de usuario(paso a paso) 

1. Pantalla de registro,si no tienes una cuenta puedes registrarte
   ![Registro](./docs/00.jpg)

2. Pantalla de inicio de sesión

![Login](./docs/01.jpg)


3. Ingresar  las credenciales de usuario, luego click en el boton de iniciar sesión
   ![Modulo de tareas](./docs/02.jpg)

4. Pantalla de tareas,durante la carga de esta pantalla se realiza una peticion a la API para obtener las tareas del usuario
   ![Crear tarea](./docs/03.jpg)

5. Listado de tareas, en esta pantalla se puede visualizar las tareas del usuario y crear nuevas tareas
   ![Crear tarea](./docs/04.jpg)



