# 📖 My Finances. APIREST with SYMFONY 5 && API PLATFORM.

## Description

APIREST api-platform & Symfony 5.

- Documentation **Api-Platform**.
- Complete Authentication:
  - Send activation email -> RabbitMQ.
  - Resend activation email -> RabbitMQ.
  - OAuth facebook login.
  - JWT Auth (JWT LexikJWTAuthenticationBundle).
  - Reset password
- Upload user avatar Amazon S3/Digital Ocean Cloud.
- Groups management
- Categories management.
- Movements management.
- Upload movements file Amazon S3/Digital Ocean Cloud.
- Custom Filters Api Platform
- Tests.


## Getting Started

1. Clone repository.
2. Execute `cd ~/symfony-api-platform/api` && `make run` --> up containers(nginx + php8.0 + MySQL8)
3. Execute `cd ~/symfony-api-platform/mailer` && `make run` -->up Mailer Service
4. Execute `cd ~/symfony-api-platform/rabbitmq` && `make run` up RabbitMQ Service
5. Execute `make composer-install` At root proyect for install  dependencies.
6. Add migrations: `make migrations`.
7. Check your local server: `http://localhost:250`.
8. API Documentatiob: `http://localhost:250/api/v1/docs`
9. Happy codding my friends!


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
