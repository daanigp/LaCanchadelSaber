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

4. Levanta los contenedores:

```bash
docker compose up -d
```

5. Abre el navegador en:

* **Para ver la web:** (http://localhost:8080)
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
```

## Despliegue en producción (AWS)

La aplicación está desplegada en AWS. Para un nuevo despliegue:

1. Configura una instancia EC2 con Apache y PHP 8.2
2. Sube los archivos de `src/` al servidor
3. Configura las variables de entorno en `config/config.php`
4. Importa `docker/mysql/init.sql` en la base de datos

[Volver](index.md)
