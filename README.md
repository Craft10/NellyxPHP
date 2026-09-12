# PHP-FPM + Nginx Docker (multi-versión 8.2 → 8.5)

Imagen Docker con **Nginx + PHP-FPM en el mismo contenedor**, parametrizada por versión de PHP
y publicada automáticamente en GitHub Container Registry (GHCR) vía GitHub Actions.

## Estructura

```
.
├── Dockerfile                      # Imagen parametrizada con ARG PHP_VERSION
├── entrypoint.sh                   # Arranca php-fpm + nginx y maneja señales
├── .dockerignore
├── config/
│   ├── php-fpm.conf                # Config global de PHP-FPM (logs a stdout)
│   ├── www.conf                    # Pool de PHP-FPM (usuario, workers, etc.)
│   └── nginx.conf                  # Server Nginx -> fastcgi hacia 127.0.0.1:9000
├── src/
│   └── public/index.php            # Tu código de aplicación (reemplázalo)
└── .github/workflows/
    └── docker-publish.yml          # Build + push a GHCR para 8.2/8.3/8.4/8.5
```

## Uso local

```bash
# Build con PHP 8.3 (por defecto)
docker build -t mi-app:php8.3 .

# Build con otra versión
docker build --build-arg PHP_VERSION=8.4 -t mi-app:php8.4 .

# Correr
docker run -p 8080:80 mi-app:php8.3
# -> http://localhost:8080
```

Coloca el código de tu aplicación dentro de `src/` (con tu `index.php` en `src/public/`)
o monta un volumen en desarrollo:

```bash
docker run -p 8080:80 -v $(pwd)/src:/var/www/html mi-app:php8.3
```

## Publicación automática en GHCR

El workflow `.github/workflows/docker-publish.yml`:

1. Se activa en `push` a `main`, en tags `v*.*.*`, en PRs (solo build, sin push) y manualmente.
2. Construye la imagen para **PHP 8.2, 8.3, 8.4 y 8.5** en paralelo (matrix).
3. Publica cada una en `ghcr.io/<tu-usuario>/<tu-repo>` con tags:
   - `php8.2`, `php8.3`, `php8.4`, `php8.5`
   - `php8.2-<sha>` (trazabilidad por commit)
   - `php8.2-latest` (solo desde `main`)
4. Construye para `linux/amd64` y `linux/arm64`.

### Pasos para activarlo

1. Sube este repo a GitHub (ver comandos abajo).
2. En GitHub, ve a **Settings → Actions → General → Workflow permissions** y marca
   "Read and write permissions" (para que `GITHUB_TOKEN` pueda publicar en GHCR).
3. Haz push a `main` (o crea un tag `v1.0.0`) y el workflow se ejecutará solo.
4. Verás las imágenes en `https://github.com/<tu-usuario>/<tu-repo>/pkgs/container/<tu-repo>`.

### Descargar una imagen publicada

```bash
docker pull ghcr.io/<tu-usuario>/<tu-repo>:php8.4
```

Si el paquete es privado, primero autentícate:

```bash
echo <TU_TOKEN> | docker login ghcr.io -u <tu-usuario> --password-stdin
```

## Subir el repo a GitHub

```bash
cd docker-php-nginx
git init
git add .
git commit -m "Docker PHP-FPM + Nginx multi-versión (8.2-8.5) con publicación en GHCR"
git branch -M main
git remote add origin https://github.com/<tu-usuario>/<tu-repo>.git
git push -u origin main
```

## Notas / personalización

- **Extensiones PHP**: agrega o quita en el `RUN docker-php-ext-install ...` del Dockerfile.
- **Document root**: por defecto `/var/www/html/public` (estilo Laravel/Symfony). Cámbialo en
  `config/nginx.conf` si tu app no usa carpeta `public/`.
- **php.ini personalizado**: agrega un archivo `config/php.ini` y cópialo en el Dockerfile a
  `/usr/local/etc/php/conf.d/zz-custom.ini`.
- **Composer**: el `entrypoint.sh` ejecuta `composer install` automáticamente si detecta
  `composer.json` sin `vendor/`. Bórralo del script si prefieres instalar dependencias en build-time.
- **PHP 8.5**: al ser una versión reciente, si el tag `php:8.5-fpm-bookworm` aún no existe en
  Docker Hub cuando ejecutes el build, quita `"8.5"` temporalmente de la matriz del workflow.
