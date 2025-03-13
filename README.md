# Magento 2 `config.php` validation

The module answers one of the most common mistakes made when maintaining Magento 2 projects.

Outdated `config.php` file in the repository leads to serious issues with environment consistency after the deployment.

When your **Deployment Configuration** is not in line with the codebase, exit code is `1` (ERROR)

## Installation

```shell
composer require lbajsarowicz/module-config-validator
```

Module does not require Magento initialization, so you can install it either as `require` or `require-dev` without any issues.

## Usage Examples

### CLI

```shell
bin/magento setup:config:validate

# There is also verbose version:
bin/magento setup:config:validate -v
# Contents of `config.php` is not up to date
# Modules should be removed from `config.php`: LBajsarowicz_Example
# Modules missing from `config.php`: Magento_Amqp
```

### Github Workflow

```yaml
name: Pull Request
on: pull_request
jobs:
  # Verify whether Project can be built
  qa:
    name: Pull Request validations
    runs-on: self-hosted
    container:
      image: docker.io/wardenenv/php-fpm:8.2-magento2
    env:
      COMPOSER_AUTH: "${{ secrets.COMPOSER_AUTH }}"
    steps:
      - name: Clone repository to build
        uses: actions/checkout@v4
      - name: Install Composer Dependencies
        run: "composer2 install --no-interaction --no-progress --no-dev --prefer-dist"
      - name: Verify 'config.php'
        run: bin/magento setup:config:validate -v
```
