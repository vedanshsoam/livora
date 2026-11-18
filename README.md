# Livora — Cloud-ready web application

Livora helps users discover roommates and domestic service providers such as cooks and maids. This repository keeps the existing PHP website and adds a cloud-ready deployment path.

## What is implemented in this code update

- Registration and login use the MySQL `users` table and PHP `password_hash()` / `password_verify()`.
- Database connection values are read from environment variables, so the same code can use local MySQL or Amazon RDS for MySQL.
- Profile-image upload validates file type and size. It stores images in Amazon S3 when `AWS_S3_BUCKET` is configured, or in the local `uploads/` directory otherwise.
- S3 reads use short-lived pre-signed URLs. The AWS SDK uses its default credential provider chain; on EC2, attach an IAM instance role rather than putting access keys in source code.
- Dockerfile and Docker Compose provide an application container and a MySQL development container.
- `health.php` reports application/database health. `cloud_status.php` shows non-secret runtime configuration to a logged-in user.
- Example least-privilege S3 policy and EC2 trust policy are in `aws/`.

## Important: what still requires your AWS account

This code does not create AWS resources or deploy itself. You must create/configure the EC2 instance, RDS database, S3 bucket, IAM role, networking, DNS and HTTPS in your own AWS account. Until you do that, use the local Docker setup below. The existing PHP pages and sample listing content remain in place; not every listing module has been converted from sample data to database-backed records.

## Run locally with Docker

1. Install Docker Desktop or Docker Engine with the Compose plugin.
2. Copy `.env.example` to `.env`.
3. Change `DB_PASS` and `MYSQL_ROOT_PASSWORD` to strong local development passwords.
4. From this directory run:

   ```bash
   docker compose up --build -d
   ```

5. Open `http://localhost:8080`.
6. Register a new account. The database is initialized from `database_setup.sql` on the first start.
7. Health check: `http://localhost:8080/health.php`.

To stop:

```bash
docker compose down
```

To remove the local database volume and all local database data (destructive):

```bash
docker compose down -v
```

## Run with XAMPP

1. Install PHP 8.1+ and MySQL through XAMPP.
2. Import `database_setup.sql` through phpMyAdmin. The default XAMPP connection is `DB_HOST=127.0.0.1`, `DB_USER=root`, `DB_PASS=` and `DB_NAME=livora_db`.
3. If you use different credentials, define `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME` and `DB_PORT` in the Apache/PHP environment. PHP does not automatically load `.env`; Docker Compose does.
4. Run `composer install` in this project directory if you want to enable AWS S3 integration.
5. Place the project in `htdocs/livora` and open `http://localhost/livora`.

Do not use the default XAMPP root account in a public production environment.

## Configure Amazon RDS for MySQL

1. Create an RDS MySQL database in the same VPC as the application host.
2. Restrict the RDS security group inbound rule to MySQL port 3306 from the EC2 application's security group only. Do not expose the database publicly.
3. Create a dedicated database user with only the privileges required by Livora.
4. Import `database_setup.sql` into the RDS database.
5. Set the application environment variables:

   ```env
   DB_HOST=your-rds-endpoint.region.rds.amazonaws.com
   DB_PORT=3306
   DB_NAME=livora_db
   DB_USER=your_livora_db_user
   DB_PASS=your_strong_database_password
   ```

6. Restart the application and check `/health.php`.

For production, place secrets in AWS Secrets Manager or another managed secret store rather than committing them to `.env`.

## Configure Amazon S3 profile-image storage

1. Create a private S3 bucket in your selected AWS region.
2. Keep Block Public Access enabled. The application uses pre-signed URLs to display profile images.
3. Replace `REPLACE_WITH_LIVORA_BUCKET` in `aws/livora-s3-iam-policy.json` with your bucket name.
4. Create an IAM policy from that JSON and attach it to an IAM role assigned to the EC2 instance.
5. Set:

   ```env
   AWS_REGION=ap-south-1
   AWS_S3_BUCKET=your-private-livora-bucket
   ```

6. Run `composer install` so `aws/aws-sdk-php` is available, then restart PHP/Apache.
7. Log in and upload a profile image from the dashboard.

The sample policy allows listing the bucket and reading/writing objects under `profiles/`. Review and narrow permissions for your deployment. Do not add AWS access keys to PHP files or source control.

## Docker / virtualization concepts

- Docker image: built from `Dockerfile`.
- Application container: PHP + Apache + Livora source.
- Database container: MySQL 8 for local development.
- Persistent volumes: database data and local uploads survive container restarts.
- For a cloud production environment, the application container can run on EC2; use RDS rather than the Compose MySQL container if you choose managed database hosting.

## IAM / security notes

- `aws/ec2-trust-policy.json` is an example trust policy for an EC2-assumable role.
- Attach the S3 access policy to the role; do not create broad administrator permissions for the application.
- Keep the S3 bucket private and use pre-signed access.
- Use HTTPS in production, strong database credentials, regular backups, and least-privilege security groups.
- The included session settings use HttpOnly and SameSite cookies; the Secure cookie flag is enabled when HTTPS is detected.
- The upload endpoint accepts JPG, PNG and WEBP images up to 5 MB and checks the detected MIME type.

## Cloud concepts demonstrated

| Concept | Project implementation / deployment option |
|---|---|
| SaaS-style access | Livora is accessed through a web browser |
| IaaS | EC2 can host the PHP/Apache application |
| PaaS | A compatible managed PHP application platform can be used as an alternative host |
| Cloud database | Environment-based MySQL connection supports RDS |
| Cloud storage | Optional S3 profile-image storage |
| Virtualization | EC2 virtual machine and Docker containers |
| Containerization | Dockerfile and Compose |
| IAM | EC2 role and scoped S3 policy |
| Networking | Application-to-RDS private networking; HTTPS and optional CDN |
| Monitoring | Health endpoint and runtime status page |

The PaaS, EC2, RDS and IAM items require actual cloud setup before they can be described as deployed services. The local Docker configuration is runnable without an AWS account.
