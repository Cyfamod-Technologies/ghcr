# 📦 PHP CI/CD Pipeline with GitHub Actions and Docker Swarm

This project demonstrates a complete **CI/CD pipeline** using GitHub Actions to:

* Run automated tests on every pull request
* Build and publish Docker images to GitHub Container Registry (GHCR)
* Deploy the application to a Docker Swarm cluster (staging and production)
* Trigger manual environment-specific deployments
* Support automatic and manual rollback strategies

---

## 📁 Project Structure

```
.
├── Dockerfile                    # Builds PHP app image
├── index.php                    # Sample PHP file with DB connection
├── docker-compose.yml           # Default compose file
├── dev/                         # Env-specific Docker Compose files
│   ├── docker-compose.staging.yml
│   └── docker-compose.production.yml
├── README.md                    # This documentation
└── .github/
    └── workflows/
        ├── env-deploy.yml       # Manual deploy (staging/prod)
        ├── ghcr-push.yml        # Build & push image on main
        ├── rollback.yml         # Rollback on deployment failure
        └── test.yml             # Smoke test on pull requests
```

---

## ✅ Features

* **Smoke Testing** on pull requests via `curl` and DB connection check
* **Multi-stage Docker build** for optimized image
* **Image push to GHCR** with auto versioning
* **Environment-based deployments** using `workflow_dispatch`
* **Automatic rollback** using Docker Swarm update policies
* **Branch protection** enforced with test status checks

---

## ⚙️ GitHub Workflows

### 1. `test.yml`

Runs on pull requests only:

```yaml
on:
  pull_request:
```

* Docker build
* Launches PHP + MySQL containers
* Validates connection using `curl` + `grep`

### 2. `ghcr-push.yml`

Runs only on push to `main`:

```yaml
on:
  push:
    branches: [main]
```

* Generates timestamp-based version tag
* Builds and pushes GHCR image
* Deploys to Swarm via SSH

### 3. `env-deploy.yml`

Manual deployment to staging/production:

```yaml
on:
  workflow_dispatch:
    inputs:
      env:
        type: choice
        options: [staging, production]
```

* Uses appropriate host and compose file based on input
* SSH + Docker stack deploy

### 4. `rollback.yml`

Triggers if `ghcr-push.yml` fails:

```yaml
on:
  workflow_run:
    workflows: ["Build & Push Hello World to GHCR"]
    types: [completed]
```

* Executes `docker service rollback` remotely

---

## 🔐 Required GitHub Secrets

| Secret Name       | Purpose                                |
| ----------------- | -------------------------------------- |
| `GHCR_TOKEN`      | GitHub PAT with `write:packages` scope |
| `SSH_USER`        | SSH username (e.g., `ubuntu`)          |
| `SSH_PASSWORD`    | SSH password or use SSH key            |
| `STAGING_HOST`    | IP/DNS of staging Swarm manager        |
| `PRODUCTION_HOST` | IP/DNS of production Swarm manager     |

---

## 🌐 Docker Compose Files

Located in the `dev/` directory:

### `dev/docker-compose.staging.yml`

```yaml
version: '3.8'
services:
  app:
    image: ghcr.io/cyfamod-technologies/ghcr-php:latest
    deploy:
      replicas: 1
      update_config:
        failure_action: rollback
```

### `dev/docker-compose.production.yml`

```yaml
version: '3.8'
services:
  app:
    image: ghcr.io/cyfamod-technologies/ghcr-php:latest
    deploy:
      replicas: 3
      update_config:
        failure_action: rollback
```

---

## 🚀 Usage

### 1. Run Tests on Pull Requests

Triggered automatically when opening or updating a PR:

```bash
git checkout -b feature/test
# make changes
hub pull-request
```

### 2. Deploy on Push to Main

After tests pass and code is merged:

```bash
git merge feature/test
git push origin main
```

### 3. Manual Deploy to Env

Via GitHub UI → Actions → Deploy to Environment → Run workflow → Select `staging` or `production`

---

## 🔁 Rollback Support

### Automatic (Docker Swarm):

```yaml
update_config:
  failure_action: rollback
```

### Manual (rollback.yml):

Triggered when main deploy workflow fails, calls:

```bash
docker service rollback ghcr_app
```

---

## ✅ Best Practices

* Use branch protections and require test checks on `main`
* Keep compose files versioned in Git and environment-specific
* Use health checks in Docker to catch failures early
* Avoid deploying PR branches — only use `main` or tagged versions

---

## 📣 Future Improvements

* Add deployment status notification (Slack, email)
* Add blue/green deployment strategy
* Auto-cleanup old Docker images
* Add integration test suite (e.g. PHPUnit)

---

## 🧠 Credits

Built with ❤️ using:

* [GitHub Actions](https://github.com/features/actions)
* [Docker Swarm](https://docs.docker.com/engine/swarm/)
* [GHCR](https://ghcr.io)
* [Appleboy SSH Action](https://github.com/appleboy/ssh-action)

---

## 📄 License

MIT — feel free to adapt, reuse, and contribute.
