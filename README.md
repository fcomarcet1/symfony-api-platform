# 📖 My Finance. API REST con SYMFONY 5

Simple overview of use/purpose.

## Description
-WORKING
API REST empleando api-platform. Esta API nos permitirá gestionar nuestras finanzas personales, es decir, nos permitirá:
- Docker
- Send activation email -> RabbitMQ
- JWT Auth (JWT LexikJWTAuthenticationBundle).
- Reset password
- Realizar operaciones CRUD sobre nuestros movimientos.
- Documentation **Api-Platform**.

- WORKING:
    - 



## Getting Started

1. Clona el repositorio.
2. Ejecuta `cd api` && `make run` para levantar los contenedores(nginx + php8.0 + MySQL8)
3. Ejecuta `cd mailer` && `make run` para levantar Mailer Service
4. Ejecuta `cd rabbitmq` && `make run` para levantar RabbitMQ Service
5. Ejecuta `make composer-install` en la raíz del proyecto.
6. Instala las migraciones de base de datos: `make migrations`.
7. Accede el servidor local de desarrollo para comprobar que funciona correctamente: `http://localhost:250`.
8. Happy codding!


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

This project is licensed under the [ GPL-3.0 ] License - see the LICENSE.md file for details