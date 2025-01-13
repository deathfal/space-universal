# Space-Universal

Space-Universal is a Docker-based Symfony project that integrates FrankenPHP and Webpack Encore for modern web development. This guide explains how to set up, run, and manage the project efficiently.

---

## Prerequisites

Before starting, ensure you have the following installed on your system:

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

---

## Getting Started

### First-Time Setup

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd space-universal
   ```

2. Build and start the containers (first-time setup):
   ```bash
   docker compose up -d --build
   ```

### Starting the Project

To start the project (after the initial setup):
```bash
docker compose up -d
```

This will start all the services in detached mode.

---

## Accessing the Symfony Container

To execute Symfony or other commands within the container:

```bash
docker compose exec -it php /bin/bash
```

Once inside the container, you can run Symfony, Composer, and Node.js commands.

---

## Common Commands

### Symfony Commands

1. Clear the cache:
   ```bash
   php bin/console cache:clear
   ```

2. List all available Symfony commands:
   ```bash
   php bin/console list
   ```

3. Check the Symfony project environment:
   ```bash
   php bin/console about
   ```

### Composer Commands

1. Install dependencies:
   ```bash
   composer install
   ```

2. Update dependencies:
   ```bash
   composer update
   ```

### Node.js and Webpack Commands

1. Install Node.js dependencies:
   ```bash
   npm install
   ```

2. Compile assets in development mode:
   ```bash
   npm run dev
   ```

3. Compile assets in production mode:
   ```bash
   npm run build
   ```

4. If Webpack is buggy or not compiling correctly, clean and recompile:
   ```bash
   rm -rf node_modules package-lock.json
   npm install
   npm run dev
   ```

---

## Debugging

1. To check running containers:
   ```bash
   docker ps
   ```

2. To view container logs:
   ```bash
   docker compose logs -f
   ```

3. To rebuild the containers:
   ```bash
   docker compose up -d --build
   ```

---

## Stopping the Project

To stop the running containers:
```bash
docker compose down
```

This will stop and remove all the containers.

---

## Troubleshooting

If you encounter issues:

1. Ensure Docker and Docker Compose are installed and running.
2. Rebuild the containers:
   ```bash
   docker compose up -d --build
   ```
3. Clear Symfony cache:
   ```bash
   php bin/console cache:clear
   ```
4. Recompile Webpack assets:
   ```bash
   npm run dev
   ```
   You can also run this to recompile the webpack 

   ```bash
   ./node_modules/.bin/webpack --config webpack.config.js --mode development
   ```

---

## License

This project is licensed under the ISC License. See the LICENSE file for details.

---

Enjoy building with **Space-Universal**! 🚀

