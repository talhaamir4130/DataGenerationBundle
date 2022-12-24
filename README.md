# DataGenerationBundle

This bundle aims to assist developers to generate random data for development and testing purposes.

## Installation

`composer require --dev "doctrine-fixtures/data-generation-bundle"`

Create file `fixtures-config.yml` in root of your project:

```yaml

# fixtures-config.yml
user:
  namespace: App\Entity\User
  defined_roles:
    - ROLE_USER
    - ROLE_ADMIN
    - ROLE_SUPER_ADMIN
    - ROLE_ALLOWED_TO_SWITCH
  rows: 10

```

### User data

Users with `email`, `password`, `roles`
