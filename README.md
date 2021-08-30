# 📖 My Finance. API REST con SYMFONY 5 && API PLATFORM.



## Description

API REST empleando api-platform y Symfony 5. Esta API nos permitirá gestionar nuestras finanzas personales.

- Documentation **Api-Platform**.
- Complete Authentication:
  - Send activation email -> RabbitMQ.
  - OAuth facebook login.
  - Resend activation email.
  - JWT Auth (JWT LexikJWTAuthenticationBundle).
  - Reset password
- Upload user avatar Amazon S3/Digital Ocean Cloud.
- CRUD Groups
- Tests.


- IN PROGRESS:
    - CRUD movements.
    - Categories
    - Upload movements file
   


## Getting Started

1. Clona el repositorio.
2. Ejecuta `cd ~/symfony-api-platform/api` && `make run` para levantar los contenedores(nginx + php8.0 + MySQL8)
3. Ejecuta `cd ~/symfony-api-platform/mailer` && `make run` para levantar Mailer Service
4. Ejecuta `cd ~/symfony-api-platform/rabbitmq` && `make run` para levantar RabbitMQ Service
5. Ejecuta `make composer-install` en la raíz del proyecto.
6. Instala las migraciones de base de datos: `make migrations`.
7. Accede el servidor local de desarrollo para comprobar que funciona correctamente: `http://localhost:250`.
8. Documentacion de la API: `http://localhost:250/api/v1/docs`
9. Happy codding!


## Authors

Contributors names and contact info

- Francisco Marcet Prieto  
  [Linkedin](https://www.linkedin.com/in/fcomarcetprieto/)

## Version History

* 0.2
    * Various bug fixes and optimizations
    * See [commit change]() or See [release history]()
* 0.1
    * Initial Release

## License

This project is licensed under the [MIT] License - see the LICENSE.md file for details