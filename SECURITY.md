# Hall of Fame Plugin - Security & Configuration Guide

## 🔒 Security Configuration

### Email Configuration

Before deploying to production, update the email settings in `config/general.php`:

```php
'emails' => [
    'bcc' => 'your-security@yourdomain.com',           // Replace with security email
    'from_name' => 'Your Organization Name',           // Replace with org name
    'from_address' => 'no-reply@yourdomain.com',      // Replace with your domain
],
```

### PGP Configuration

For certificate signing, configure PGP settings:

1. Generate or import PGP keys through the admin interface
2. Or set them in the configuration:

   ```php
   'pgp' => [
       'enabled' => true,                    // Enable PGP features
       'public_key' => 'your_public_key',   // Your PGP public key
       'private_key' => 'your_private_key', // Your PGP private key
       'passphrase' => 'your_passphrase',   // Key passphrase
   ],
   ```

### Domain Configuration

Update all references to example domains with your actual domain:

- Search and replace `example.com` with your domain
- Update URL references in documentation files
- Configure proper email addresses for your organization

## 🛡️ Security Best Practices

1. **Never commit sensitive files:**
   - PGP private keys
   - Email files (.eml)
   - Production configuration data
   - Actual vulnerability reports

2. **Environment Variables:**
   - Use `.env` files for sensitive configuration
   - Keep `.env` files out of version control

3. **File Permissions:**
   - Restrict access to PGP key files
   - Secure certificate storage directories

4. **Regular Updates:**
   - Keep dependencies updated
   - Monitor for security advisories

## 📂 File Structure

```text
platform/plugins/hall-of-fame/
├── config/general.php          # Main configuration - REVIEW BEFORE DEPLOY
├── resources/views/            # Templates and views
├── src/                        # Core plugin code
├── database/migrations/        # Database schema
└── .gitignore                 # Security file exclusions
```

## ⚠️ Pre-Deployment Checklist

- [ ] Replace all example email addresses with real ones
- [ ] Configure PGP keys for certificate signing
- [ ] Remove any test/demo data
- [ ] Verify no sensitive files are included
- [ ] Test email delivery functionality
- [ ] Verify certificate generation works
- [ ] Check permissions on sensitive directories

## 🔧 Installation

1. Copy plugin to `platform/plugins/hall-of-fame/`
2. Run migrations: `php artisan migrate`
3. Activate plugin in admin panel
4. Configure settings in Hall of Fame > Settings
5. Set up email and PGP configuration
6. Test certificate generation

For detailed installation instructions, see the main README.md file.
