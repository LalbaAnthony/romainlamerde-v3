# 💬 - romainlamerde

## 📖 Technos

- [Quasar](https://quasar.dev/)

## 🚀 Quick Start

### Frontend

```bash
cd front/ && quasar dev
```

## 🚢 Deployment

### Workflow

Workflow are named as `flow_<WHAT>_<WHERE>`. So `flow_deploy_prod` will deploy the production version of the app.

### Secrets

NB: `*_SERVER_PRIVATE_KEY` is like the `cat ~/.ssh/id_rsa` on local machine

### Preproduction

Those secrets are required in the deployment environment:
- `PREPROD_APACHE_FOLDER`: The apache folder on the server from `/var/www/html`
- `PREPROD_SERVER_IP`: The server IP address
- `PREPROD_SERVER_USER`: The server user
- `PREPROD_SERVER_PRIVATE_KEY`: The private key to connect to the server

### Production

- `PROD_APACHE_FOLDER`: The apache folder on the server from `/var/www/html`
- `PROD_SERVER_IP`: The server IP address
- `PROD_SERVER_USER`: The server user
- `PROD_SERVER_PRIVATE_KEY`: The private key to connect to the server

### Web server

Apache is used to serve the frontend. Make sure to install it on the server.

Use the `apache.prod.conf` file to configure the virtual host.