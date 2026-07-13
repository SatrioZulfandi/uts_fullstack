# Pre-Upgrade Baseline

## Environment
- **PHP Version:** 8.5.7
- **Laravel Version:** 12.58.0
- **Composer Version:** 2.8.12
- **Node Version:** 22.17.0
- **npm Version:** 10.9.2

## Routes
**Total Routes:** 27

**Web Routes:**
- `GET /`
- `GET admin/dashboard`
- `GET admin/inventories` (and other CRUD)
- `GET admin/schedules` (and other CRUD)
- `GET login`
- `POST login`
- `POST logout`

**API Routes:**
- `POST api/login`
- `POST api/logout`
- `GET api/inventories`
- `POST api/check-in`

## Automated Tests
- **Tests Executed:** 2 tests (2 assertions)
- **Passed:** 1 test (`Tests\Unit\ExampleTest`)
- **Failed:** 1 test (`Tests\Feature\ExampleTest > the application returns a successful response`)
- **Pre-existing Issues:** The root path `/` returns `302` (Redirect) instead of `200` OK, which causes the default `ExampleTest` to fail. This is normal if the home page redirects to login.

## Build Assets
- **Command Executed:** `npm install && npm run build`
- **Result:** Successfully compiled 55 modules via Vite. No vulnerabilities found in npm audit.

## Pre-existing problems
- Home route (`/`) redirects, causing the default `ExampleTest` to fail.
