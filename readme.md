NOTA: Todos los pasos a mencionar fueron explicados y ejecutados en S.O Linux

Parte 1. Duplicar el archivo .env.example y elimiminar el .example de manera que quede .env En ese archivo debe configurar su base de datos. (Debe crear una base de datos vacia en MySQLphp )

Parte 2. En la consola ejecutar los siguientes comandos en el siguiente orden

1. composer install    (Para descargar dependencias)
2. php artisan key:generate   (Generar la key del proyecto)
3. php artisan migrate --seed (Generar migracios y el usuario administrador)
4. php artisan passport:install (Genera llave de OAuth)
5. php artisan serve (Encender servidor. PD: dejar que corrar en su host predeterminado 127.0.0.1)

Listo !!
