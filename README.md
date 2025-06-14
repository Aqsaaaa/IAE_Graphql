# Project Overview

This project consists of multiple microservices and a frontend application, orchestrated using Docker Compose. The main services include:

- **Deliveries Service**: A GraphQL API for managing deliveries.
- **Memberships Service**: A GraphQL API for managing memberships.
- **Vendor Requests Service**: A service for handling vendor requests.
- **Frontend**: A Laravel-based frontend application.
- **Databases**: Each service has its own MySQL database.
- **phpMyAdmin**: A web interface for managing the MySQL databases.

# Prerequisites

- [Docker](https://docs.docker.com/get-docker/) installed on your machine.
- [Docker Compose](https://docs.docker.com/compose/install/) installed.

# Installation and Running

1. **Clone the repository**

   ```bash
   git clone https://github.com/Aqsaaaa/IAE_Graphql
   cd IAE_graphql
   ```

2. **Build and start the services**

   Use Docker Compose to build and start all services and their databases:

   ```bash
   docker-compose up --build
   ```

   This command will build the Docker images and start the containers. The services will be available on the following ports:

   - Deliveries Service: http://localhost:4001
   - Memberships Service: http://localhost:4002
   - Vendor Requests Service: http://localhost:4003
   - phpMyAdmin: http://localhost:8089

3. **Access phpMyAdmin**

   You can manage the MySQL databases via phpMyAdmin at:

   ```
   http://localhost:8089
   ```

   Use the following credentials to connect to each database:

   - Host: deliveries-db, memberships-db, or vendor_requests-db (depending on the database)
   - Username and Password as defined in the docker-compose.yml:
     - Deliveries DB: deliveries_user / deliveries_pass
     - Memberships DB: memberships_user / memberships_pass
     - Vendor Requests DB: vendor_requests_user / vendor_requests_pass

# Services Overview

## Deliveries Service

- GraphQL API for managing deliveries.
- Runs on port 4001.
- Refer to `deliveries/readme.md` for GraphQL API schema and example mutations.

## Memberships Service

- GraphQL API for managing memberships.
- Runs on port 4002.
- Refer to `memberships/readme.md` for GraphQL API schema and example queries and mutations.

## Vendor Requests Service

- Runs on port 4003.
- No additional documentation provided.

## Frontend

- Laravel-based frontend application.
- To run the frontend separately, navigate to the `Frontend` directory and follow Laravel's standard setup (not covered in this README).

# Stopping the Services

To stop all running containers, press `Ctrl+C` in the terminal where `docker-compose` is running, or run:

```bash
docker-compose down
```

# Notes

- Ensure the Docker network `iae-network` exists as it is defined as an external network in the docker-compose.yml.
- Database data is persisted using Docker volumes defined in the docker-compose.yml.

# Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Docker Documentation](https://docs.docker.com)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
