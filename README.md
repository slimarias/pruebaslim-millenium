# PRUEBA SLIM

Prueba presentada para Millenium Group

## Instalación

Se debe crear la base de datos e importar el archivo sql/database.sql con el gestor de base de datos de su preferencia.

Luego se configura el archivo models/BaseModel.php en la línea 11 de la siguiente forma:

```php
$this->db = new PDO('mysql:host=localhost;dbname=pruebaslim', "root", "admin123456");
```
El parametro *pruebaslim* se cambia por el nombre de la base de datos, *root* se cambia por el usuario y *admin123456* por la contraseña de acceso a la base de datos, todos estos datos proporcionados por el proveedor de hosting

Recomendable instalar en la raíz del sitio (public_html o www)

## Ingreso al sistema

Puedes ingresar con el email de prueba *admin@mail.com* y la contraseña *admin123456* 