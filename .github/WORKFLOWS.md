# GitHub Actions Workflows Documentation

This document provides an overview of the GitHub Actions workflows configured for this Laravel + Filament + Livewire project.

---

## Overview

The project uses a modular CI/CD approach with separate, reusable workflows for each code quality tool:

- **Pint** - Laravel code style fixer
- **PHPStan** - Static analysis tool
- **Rector** - Automated refactoring tool
- **Code Quality** - Orchestrates all quality checks

---

## Workflows

### 1. Code Quality (Main Orchestrator)

**File:** `.github/workflows/code-quality.yml`

**Triggers:**
- Push to `main`, `develop`, `components` branches
- Pull requests to `main`, `develop`, `components` branches
- Manual dispatch with auto-fix option

**What it does:**
- Runs Pint, PHPStan, and Rector in parallel
- Provides a unified summary of all quality checks
- Optionally auto-fixes issues when triggered manually

**Manual Trigger:**
```bash
# Via GitHub UI: Actions → Code Quality → Run workflow
# Enable "Auto-fix issues" checkbox to automatically fix Pint & Rector issues
```

---

### 2. Pint (Code Style)

**File:** `.github/workflows/pint.yml`

**Purpose:** Ensures consistent code style following Laravel conventions

**Features:**
- Runs in test mode by default (no changes)
- Auto-fix mode available when called from main workflow
- Uses `cs2pr` for inline annotations
- Parallel execution for speed
- Auto-commits fixes when enabled

**Configuration:** `pint.json`
```json
{
    "preset": "laravel"
}
```

**Local Usage:**
```bash
# Check code style
composer test:lint
# or
vendor/bin/pint --test

# Fix code style
composer lint
# or
vendor/bin/pint
```

---

### 3. PHPStan (Static Analysis)

**File:** `.github/workflows/phpstan.yml`

**Purpose:** Static analysis to catch bugs before runtime

**Features:**
- Level 5 analysis (configured in `phpstan.neon`)
- GitHub error format for inline annotations
- Memory limit: 2GB
- Baseline generation suggestion on failure

**Configuration:** `phpstan.neon`
```neon
parameters:
    level: 5
    paths:
        - app
        - config
        - database
        - routes
        - tests
```

**Local Usage:**
```bash
# Run PHPStan analysis
vendor/bin/phpstan analyse

# Generate baseline (if needed)
vendor/bin/phpstan analyse --generate-baseline
```

---

### 4. Rector (Automated Refactoring)

**File:** `.github/workflows/rector.yml`

**Purpose:** Automated code refactoring and modernization

**Features:**
- Dry-run mode by default (no changes)
- Auto-fix mode available when called from main workflow
- GitHub error format for inline annotations
- Auto-commits refactorings when enabled

**Configuration:** `rector.php`
```php
return RectorConfig::configure()
    ->withPaths([__DIR__.'/app', __DIR__.'/tests'])
    ->withTypeCoverageLevel(0)
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(0);
```

**Local Usage:**
```bash
# Check what Rector would change (dry-run)
vendor/bin/rector process --dry-run

# Apply Rector refactorings
vendor/bin/rector process
```

---

## Usage Guide

### For Pull Requests

When you create a pull request, all quality checks run automatically:

1. **Pint** checks code style
2. **PHPStan** performs static analysis
3. **Rector** checks for refactoring opportunities

If any check fails, you'll see inline annotations in your PR.

### Manual Auto-Fix

To automatically fix issues:

1. Go to **Actions** tab
2. Select **Code Quality** workflow
3. Click **Run workflow**
4. Enable **Auto-fix issues** checkbox
5. Click **Run workflow**

This will:
- Run Pint and auto-commit style fixes
- Run Rector and auto-commit refactorings
- Run PHPStan (analysis only, no auto-fix)

### Local Development

Before pushing, run these commands locally:

```bash
# Check everything (recommended before commit)
composer test

# Individual checks
composer test:lint          # Pint (test mode)
composer lint               # Pint (fix mode)
vendor/bin/phpstan analyse  # PHPStan
vendor/bin/rector --dry-run # Rector (test mode)
vendor/bin/rector           # Rector (fix mode)
```

---

## Workflow Architecture

```
code-quality.yml (Main Orchestrator)
├── setup (Configure PHP & Composer)
├── pint.yml (Code Style)
│   ├── Test mode (default)
│   └── Auto-fix mode (optional)
├── phpstan.yml (Static Analysis)
│   └── Analysis with GitHub annotations
├── rector.yml (Refactoring)
│   ├── Dry-run (default)
│   └── Auto-fix mode (optional)
└── quality-summary (Results Summary)
```

---

## Secrets Required

### FLUX_USERNAME & FLUX_LICENSE_KEY

Required for Livewire Flux dependency installation.

**Setup:**
1. Go to **Settings** → **Secrets and variables** → **Actions**
2. Add `FLUX_USERNAME`
3. Add `FLUX_LICENSE_KEY`

These are optional - workflows will skip Flux authentication if not provided.

---

## Quality Levels

### PHPStan Levels

Currently configured at **Level 5** (moderate strictness):

| Level | Checks |
|-------|--------|
| 0 | Basic checks, unknown classes |
| 1 | Possibly undefined variables |
| 2 | Unknown methods called |
| 3 | Return types, property types |
| 4 | Dead code, unused private elements |
| **5** | **Argument types (current)** |
| 6 | Missing typehints |
| 7 | Unions and intersections |
| 8 | Calling methods on nullable |
| 9 | Mixed types |

**Recommendation:** Gradually increase to level 6-7 for maximum type safety.

### Rector Levels

Currently configured:
- **Type Coverage:** Level 0 (disabled)
- **Dead Code:** Level 0 (disabled)
- **Code Quality:** Level 0 (disabled)

**Recommendation:** Enable gradually as codebase stabilizes:
```php
->withTypeCoverageLevel(50)    // 50% type coverage
->withDeadCodeLevel(20)         // Remove obvious dead code
->withCodeQualityLevel(30)      // Basic quality improvements
```

---

## Troubleshooting

### Workflow fails with "Composer dependencies error"

**Solution:** Ensure `FLUX_USERNAME` and `FLUX_LICENSE_KEY` secrets are set.

### PHPStan reports too many errors

**Options:**
1. Generate baseline: `vendor/bin/phpstan analyse --generate-baseline`
2. Lower the level temporarily in `phpstan.neon`
3. Fix issues gradually

### Rector changes too much code

**Options:**
1. Review `rector.php` and disable aggressive rules
2. Use `--dry-run` to preview changes first
3. Apply changes in small batches

### Pint auto-fix not committing

**Solution:** Ensure the workflow has `contents: write` permission (already configured).

---

## Best Practices

### Before Committing

```bash
# Run full test suite (includes Pint check)
composer test

# Run static analysis
vendor/bin/phpstan analyse

# Check for refactoring opportunities
vendor/bin/rector --dry-run
```

### During Code Review

- ✅ All quality checks must pass
- ✅ No PHPStan baseline additions without justification
- ✅ Rector suggestions should be reviewed, not blindly applied
- ✅ Code style is automatically enforced by Pint

### Continuous Improvement

1. **Monthly:** Review PHPStan baseline and try to reduce it
2. **Quarterly:** Increase PHPStan level if codebase is stable
3. **As needed:** Enable more Rector rules for better refactoring

---

## Maintenance

### Updating GitHub Actions Versions

All workflows use pinned SHA versions for security:

```yaml
uses: actions/checkout@692973e3d937129bcbf40652eb9f2f61becf3332 # v4.1.7
```

To update:
1. Check [GitHub Actions](https://github.com/marketplace?type=actions) for latest versions
2. Update SHA and version comment
3. Test in a branch first

### Adding New Quality Tools

To add a new tool (e.g., PHP CS Fixer):

1. Create `.github/workflows/new-tool.yml`
2. Follow the pattern of existing workflows
3. Add to `code-quality.yml` orchestrator
4. Update this documentation

---

## Additional Resources

- [Laravel Pint Documentation](https://laravel.com/docs/pint)
- [PHPStan Documentation](https://phpstan.org/)
- [Rector Documentation](https://getrector.com/)
- [GitHub Actions Documentation](https://docs.github.com/actions)

---
