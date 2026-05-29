# Proyecto Intermodular - La Cancha del Saber

## Documentación Oficial

### Instalación

#### Requisitos

* Git
* Docker Desktop
* Navegador web

#### Instalación en local

1. Clona el repositorio:

```bash
git clone https://github.com/daanigp/LaCanchadelSaber.git
cd LaCanchadelSaber
```

2. Accede a la carpeta de la aplicación:

```bash
cd "app"
```

3. Copia el archivo de entorno:

```bash
cp .env.example .env
```

4. Renombra las variables de entorno con tus datos:

```bash
nano .env
```

5. Levanta los contenedores:

```bash
docker compose up -d
```

6. Abre el navegador en:

* **Para ver la web:** (http://localhost/public/index.php)
* **phpMyAdmin:** (http://localhost:8081)

#### Variables de entorno

El archivo `.env` contiene las credenciales de la base de datos. No se sube al repositorio. Ejemplo:

```env
DB_NAME=lacanchadelsaber
DB_USER=lacanchadelsaber_user
DB_PASS=tu_contraseña
DB_ROOT_PASS=tu_contraseña_root
```

## Estructura de carpetas

```
app
├───docker
│   ├───mysql
│   └───php
└───src
    ├───admin
    ├───assets
    │   └───lib
    │       └───font
    ├───includes
    ├───js
    │   └───utils
    ├───public
    ├───public_user
    ├───static
    │   ├─db
    │   ├─icon
    │   └───img
    │       └───profile
    ├───style
    └───templates
docs
├───imgs
├───mock
└───styleGuide
```

## Despliegue en producción (AWS)

La aplicación está desplegada en AWS. Para un nuevo despliegue:

1. Configura una instancia EC2 con Apache y PHP 8.2
2. Sube los archivos de `src/` al servidor
3. Configura las variables de entorno en `includes/configuracion.php`
4. Importa `docker/mysql/init.sql` en la base de datos

(Estos se resumen rápidamente en los mismos pasos que en [Instalación en local](#instalación-en-local))  

5. Lo único que en AWS, una vez se han levantado los contenedores, abrá que dirigirse a la carpeta donde se guardan las imágenes de perfil:

```bash
cd /home/admin/LaCanchadelsaber/app/src/static/img
```

6. Y después ejecutaremos el siguiente comando, para dar los permisos necesarios a la carpeta para que a la hora de guardar nuevas imágenes no nos de error:

```bash
sudo chmod -R 777 profile
```

Y ahora sí, podríamos funcionar sin problema :)

[Volver](index.md)
