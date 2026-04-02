# Configuración del entorno — Portfolio v2

Sistema: **Ubuntu 22.04 / Pop!_OS** · Servidor: **Apache 2.4** · BD: **MariaDB 10.6** · PHP: **8.1**

---

## 1. Instalar dependencias del sistema

```bash
# Extensiones PHP necesarias
sudo apt install -y \
  php8.1-dom \
  php8.1-xml \
  php8.1-curl \
  php8.1-mbstring \
  php8.1-gd \
  php8.1-zip \
  php8.1-mysql

# Apache
sudo apt install -y apache2 libapache2-mod-php8.1

# MariaDB
sudo apt install -y mariadb-server mariadb-client

# Verificar extensiones PHP activas
php -m | grep -E "pdo_mysql|dom|mbstring|gd|curl"
```

---

## 2. Configurar Apache

### Habilitar mod_rewrite

```bash
sudo a2enmod rewrite
```

### Editar VirtualHost

```bash
sudo nano /etc/apache2/sites-available/000-default.conf
```

Contenido exacto:

```apache
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html

    <Directory /var/www/html>
        Options FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

### Editar apache2.conf — cambiar AllowOverride

```bash
sudo nano /etc/apache2/apache2.conf
```

Buscar el bloque `<Directory /var/www/>` y dejarlo así:

```apache
<Directory /var/www/>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

### Reiniciar Apache

```bash
sudo systemctl restart apache2
```

---

## 3. Configurar MariaDB

### Crear base de datos y usuario

```bash
sudo mariadb
```

Dentro del prompt ejecutar:

```sql
CREATE DATABASE IF NOT EXISTS portfolio_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER 'portfolio'@'localhost' IDENTIFIED BY 'portfolio2024';
GRANT ALL PRIVILEGES ON portfolio_db.* TO 'portfolio'@'localhost';
FLUSH PRIVILEGES;

EXIT;
```

### Importar el schema

```bash
sudo mariadb portfolio_db < ~/Descargas/portfolio/database.sql
```

Si da error en `is_current`, importar manualmente desde dentro de mariadb:

```bash
sudo mariadb portfolio_db
```

```sql
-- Verificar IDs actuales antes de insertar achievements
SELECT id, position FROM work_experience;

-- Ajustar los IDs según lo que devuelva la query anterior
INSERT INTO work_achievements (work_id, achievement, display_order) VALUES
  (1,'Reduced page load time by 40%.',1),
  (1,'Led migration from PHP to React + REST API.',2),
  (2,'Shipped iOS and Android apps with 4.8-star rating.',1),
  (3,'Built 15+ client landing pages with 90+ PageSpeed scores.',1);

EXIT;
```

### Verificar tablas

```bash
mariadb -u portfolio -pportfolio2024 portfolio_db -e "SHOW TABLES;"
```

Resultado esperado (14 tablas):

```
certifications, code_samples, key_achievements, languages,
personal_info, professional_goals, professional_references,
project_features, project_technologies, projects,
technical_skills, technical_tools, work_achievements, work_experience
```

---

## 4. Desplegar el proyecto

### Copiar a /var/www/html

```bash
sudo cp -r ~/Descargas/portfolio /var/www/html/portfolio
```

### Permisos correctos

```bash
sudo chown -R www-data:www-data /var/www/html/portfolio
sudo find /var/www/html/portfolio -type d -exec chmod 755 {} \;
sudo find /var/www/html/portfolio -type f -exec chmod 644 {} \;
sudo chmod 777 /var/www/html/portfolio/error_logs
```

### Instalar dependencias PHP (Composer)

```bash
cd /var/www/html/portfolio
composer install
```

O copiar vendor directamente desde Descargas si ya se instaló ahí:

```bash
sudo cp -r ~/Descargas/portfolio/vendor /var/www/html/portfolio/
sudo chown -R www-data:www-data /var/www/html/portfolio/vendor
```

### Configurar credenciales

```bash
sudo nano /var/www/html/portfolio/config/app.php
```

Valores a editar:

```php
'db' => [
    'host'    => 'localhost',
    'name'    => 'portfolio_db',
    'user'    => 'portfolio',
    'pass'    => 'portfolio2024',
    'charset' => 'utf8mb4',
],

'mail' => [
    'host'      => 'smtp.tuproveedor.com',
    'port'      => 587,
    'user'      => 'tu@email.com',
    'pass'      => 'tu_password',
    'from'      => 'tu@email.com',
    'from_name' => 'Portfolio Contact',
    'to'        => 'tu@email.com',
    'encryption'=> 'tls',
],
```

### .htaccess mínimo funcional

```bash
sudo nano /var/www/html/portfolio/.htaccess
```

Contenido:

```apache
Options +FollowSymLinks
RewriteEngine On
RewriteBase /portfolio/

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

### Reiniciar servicios

```bash
sudo systemctl restart apache2
sudo systemctl restart mariadb
```

### Verificar que funciona

```bash
curl -I http://localhost/portfolio/index.php
# Debe devolver: HTTP/1.1 200 OK
```

---

## 5. Abrir en el navegador

```
http://localhost/portfolio
```

---

## 6. Comandos del día a día

### Arrancar servicios

```bash
sudo systemctl start apache2
sudo systemctl start mariadb
```

### Parar servicios

```bash
sudo systemctl stop apache2
sudo systemctl stop mariadb
```

### Ver estado

```bash
sudo systemctl status apache2 mariadb
```

### Autoarranque al encender el PC

```bash
# Activar
sudo systemctl enable apache2
sudo systemctl enable mariadb

# Desactivar
sudo systemctl disable apache2
sudo systemctl disable mariadb
```

---

## 7. Workflow de desarrollo

Editas archivos en `~/Descargas/portfolio` y sincronizas a Apache con un solo comando.

### Crear alias permanente

```bash
echo "alias portfolio-sync='sudo rsync -av --delete ~/Descargas/portfolio/ /var/www/html/portfolio/ && sudo chown -R www-data:www-data /var/www/html/portfolio'" >> ~/.bashrc
source ~/.bashrc
```

### Usar el alias

```bash
# 1. Editas tu código en ~/Descargas/portfolio con VS Code u otro editor
# 2. Sincronizas:
portfolio-sync

# 3. Refrescas el navegador con F5
```

### Ver logs de error en tiempo real

```bash
# Errores PHP / Apache
sudo tail -f /var/log/apache2/error.log

# Errores de la app
tail -f /var/www/html/portfolio/error_logs/app.log
```

---

## 8. Diagnóstico rápido

| Problema | Comando |
|----------|---------|
| Ver error exacto de Apache | `sudo tail -20 /var/log/apache2/error.log` |
| Probar PHP directamente | `sudo -u www-data php -f /var/www/html/portfolio/index.php 2>&1 \| head -30` |
| Verificar sintaxis Apache | `sudo apache2ctl -t` |
| Verificar conexión a BD | `mariadb -u portfolio -pportfolio2024 portfolio_db -e "SELECT 1;"` |
| Ver tablas de la BD | `mariadb -u portfolio -pportfolio2024 portfolio_db -e "SHOW TABLES;"` |
| Ver extensiones PHP | `php -m` |

---

## 9. Estructura del proyecto

```
portfolio/
├── config/app.php              ← Credenciales BD, mail, seguridad
├── middleware/                 ← CSRF, RateLimit, SecurityHeaders
├── services/                   ← Database (PDO), ResumeService, ProjectsService, ContactService
├── helpers/                    ← Sanitizer, translation (t(), lang_url())
├── includes/                   ← bootstrap, head, header, nav, footer, modal, contact-handler, generate-pdf
├── lang/                       ← es.php · en.php · fr.php
├── assets/
│   ├── css/main.css            ← Design system completo (tokens dark/light, responsive)
│   └── js/                     ← theme · toast · lazy · pagination · modal · app
├── error_pages/                ← 403.php · 500.php
├── error_logs/                 ← app.log (generado en runtime)
├── vendor/                     ← Composer (dompdf, phpmailer)
├── index.php
├── about.php
├── projects.php
├── resume.php
├── contact.php
├── database.sql                ← Schema completo + datos de ejemplo
├── composer.json
└── .htaccess
```

---

## 10. Notas de seguridad

- Las credenciales de BD están en `config/app.php` — no subir a git público
- Añadir `config/app.php` al `.gitignore` si se sube el proyecto
- El archivo `error_logs/app.log` contiene trazas internas — tampoco subirlo
- En producción, cambiar `'debug' => false` en `config/app.php`
- Cambiar la contraseña de `portfolio2024` por una segura en producción
