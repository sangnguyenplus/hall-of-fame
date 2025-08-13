# Who Zid IS Hall of Fame Plugin

[![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://github.com/WhoZidIS/hall-of-fame)
[![License](https://img.shields.io/badge/license-Commercial-red.svg)](#-license)
[![Botble CMS](https://img.shields.io/badge/Botble%20CMS-7.3.0+-green.svg)](https://botble.com)

> **🏆 Professional Security Hall of Fame Plugin for Botble CMS**

A comprehensive, enterprise-grade security vulnerability management system that
enables organizations to securely collect, manage, and showcase security
researcher contributions through a professional Hall of Fame platform.

## 🌟 Features

### 🔐 **Security-First Design**

- **Secure Vulnerability Reporting**: Multi-step submission process with validation
- **PGP Encryption Support**: End-to-end encryption for sensitive reports
- **Digital Signatures**: Certificate signing with PGP for authenticity
- **Access Controls**: Role-based permissions and admin oversight

### 📋 **Comprehensive Management**

- **Researcher Profiles**: Detailed researcher management with bio, contact info
- **Report Classification**: Categorize vulnerabilities by type and severity
- **Status Tracking**: Complete lifecycle management (pending → approved → published)
- **Attachment Support**: Secure file uploads with validation

### 🏅 **Professional Certificates**

- **Multiple Templates**: Professional, Modern, and Classic certificate designs
- **Dynamic Features**: QR codes, watermarks, PGP signatures
- **Bilingual Support**: Arabic/English with RTL/LTR layouts
- **Export Options**: PDF generation with customizable styling
- **Verification System**: Public certificate verification with unique IDs

### ⚙️ **Advanced Administration**

- **Settings Dashboard**: Comprehensive configuration interface
- **Email Notifications**: Automated notifications for submissions and approvals
- **PGP Key Management**: Built-in key generation and management
- **Template Customization**: Choose from multiple certificate styles
- **Bulk Operations**: Mass approval/rejection capabilities

### 🌐 **Multi-Language Ready**

- **Complete Translation**: English and Arabic language support
- **RTL Support**: Right-to-left layout for Arabic content
- **Extensible**: Easy addition of new languages
- **Professional Terminology**: Industry-standard security vocabulary

## 📦 Installation

### Prerequisites

- **Botble CMS**: Version 7.3.0 or higher
- **PHP**: 8.1 or higher
- **Laravel**: Compatible version with your Botble installation
- **GnuPG Extension**: For PGP functionality (optional)

### Step 1: Plugin Installation

```bash
# Extract the plugin to your Botble plugins directory
cp -r hall-of-fame /path/to/your/botble/platform/plugins/

# Or clone from repository (if you have access)
cd /path/to/your/botble/platform/plugins/
git clone https://github.com/Who Zid IS/hall-of-fame.git
```

### Step 2: Database Setup

```bash
# Run migrations to create required tables
php artisan migrate

# Optional: Seed sample data for testing
php artisan db:seed --class=HallOfFameSeeder
```

### Step 3: Activate Plugin

1. Navigate to **Admin Panel → Plugins**
2. Find "Hall of Fame" in the plugin list
3. Click **Activate** to enable the plugin
4. Clear cache if necessary: `php artisan cache:clear`

### Step 4: Configuration

1. Go to **Admin Panel → Hall of Fame → Settings**
2. Configure basic settings (enable/disable features)
3. Set up email notifications
4. Configure PGP keys (optional)
5. Customize certificate templates

## 🚀 Quick Start Guide

### Initial Setup

1. **Configure Settings**: Navigate to Hall of Fame → Settings
   - Set your organization details
   - Configure email notifications
   - Enable desired features (QR codes, PGP signing)

2. **Set Up PGP (Optional)**:
   - Go to Settings → PGP Keys
   - Generate new keys or import existing ones
   - Configure signing preferences

3. **Customize Certificates**:
   - Choose template style (Professional/Modern/Classic)
   - Set watermark opacity
   - Configure export formats

### Managing Submissions

1. **Review Reports**: Hall of Fame → Vulnerability Reports
2. **Manage Researchers**: Hall of Fame → Researchers
3. **Generate Certificates**: Approve reports and issue certificates
4. **Public Display**: Configure which reports appear on public Hall of Fame

## 📁 Directory Structure

```text
hall-of-fame/
├── src/                                    # Core plugin source code
│   ├── Http/Controllers/                   # HTTP controllers
│   ├── Models/                            # Eloquent models
│   ├── Services/                          # Business logic services
│   ├── Forms/                             # Admin form definitions
│   └── Providers/                         # Service providers
├── resources/
│   ├── views/                             # Blade templates
│   │   ├── certificates/                  # Certificate templates
│   │   ├── public/                        # Public-facing views
│   │   └── admin/                         # Admin panel views
│   └── lang/                              # Translation files
├── database/
│   ├── migrations/                        # Database migrations
│   └── seeders/                          # Database seeders
├── config/                                # Configuration files
├── routes/                                # Route definitions
└── public/                               # Public assets
```

## 🎨 Customization

### Certificate Templates

The plugin includes three professional certificate templates:

1. **Professional Template** (`resources/views/certificates/template.blade.php`)
   - Clean, corporate design
   - Ideal for enterprise environments

2. **Modern Template** (`resources/views/certificates/modern-template.blade.php`)
   - Contemporary styling
   - Perfect for tech companies

3. **Classic Template** (`resources/views/certificates/classic-template.blade.php`)
   - Traditional formal design
   - Suitable for government/academic institutions

### Customizing Templates

```php
// Extend templates by creating new Blade files
// Copy existing template and modify styling
// Update CertificateService to include new template
```

### Adding Languages

```php
// Create new language directory: resources/lang/[locale]/
// Copy translation files from existing languages
// Add locale to supported languages in configuration
```

## ⚙️ Configuration Options

### Email Settings

```php
// config/general.php
'emails' => [
    'bcc' => 'security@yourcompany.com',
    'from_name' => 'Your Security Team',
    'from_address' => 'no-reply@yourcompany.com',
],
```

### PGP Configuration

```php
'pgp' => [
    'enabled' => true,
    'sign_pdf' => true,
    'encrypt_pdf' => false,
],
```

### Certificate Settings

Available through Admin Panel → Hall of Fame → Settings:

- Template style selection
- Watermark opacity (0.0 - 1.0)
- QR code inclusion
- PGP signature inclusion
- Email delivery options

## 🔒 Security Features

### Data Protection

- **Input Validation**: Comprehensive validation on all user inputs
- **File Upload Security**: Restricted file types and size limits
- **SQL Injection Prevention**: Eloquent ORM with parameterized queries
- **XSS Protection**: Blade template escaping and CSP headers

### Access Control

- **Admin-Only Access**: All management functions require admin privileges
- **Public Interface**: Carefully controlled public submission interface
- **Role-Based Permissions**: Integration with Botble's permission system

### Encryption Support

- **PGP Integration**: Full PGP key management and encryption
- **Certificate Signing**: Digital signatures for certificate authenticity
- **Secure Communication**: Option to encrypt sensitive reports

## 📊 Analytics & Reporting

### Built-in Reports

- Total vulnerability submissions
- Researcher statistics
- Monthly/yearly trends
- Certificate generation metrics

### Export Capabilities

- CSV export of vulnerability data
- PDF certificate generation
- Report attachments download
- Bulk data operations

## 🛠 Development & Customization

### Extending Functionality

```php
// Create custom service extending base services
class CustomCertificateService extends CertificateService
{
    // Override methods to customize behavior
}

// Register in service provider
$this->app->bind(CertificateService::class, CustomCertificateService::class);
```

### Adding Custom Fields

1. Create migration for new fields
2. Update model fillable properties
3. Add fields to forms
4. Update validation rules
5. Modify templates to display new data

### API Integration

```php
// Example: Custom webhook integration
public function handleWebhook(Request $request)
{
    $vulnerabilityData = $request->validated();
    
    VulnerabilityReport::create([
        'title' => $vulnerabilityData['title'],
        'description' => $vulnerabilityData['description'],
        'researcher_email' => $vulnerabilityData['email'],
        // ... additional fields
    ]);
}
```

## 🌍 Internationalization

### Supported Languages

- **English** (en) - Complete translation
- **Arabic** (ar) - Complete translation with RTL support

### Adding New Languages

1. Create language directory: `resources/lang/[locale]/`
2. Copy translation files from existing language
3. Translate all keys maintaining structure
4. Test RTL layout if applicable
5. Add locale to configuration

## 📈 Performance Optimization

### Recommended Settings

```php
// Optimize for production
'cache_certificates' => true,
'compress_attachments' => true,
'lazy_load_images' => true,
```

### Database Optimization

- **Indexes**: Applied to frequently queried columns
- **Pagination**: Large datasets automatically paginated
- **Eager Loading**: Relationships optimized for N+1 prevention

## 🔧 Troubleshooting

### Common Issues

#### PGP Keys Not Working

```bash
# Ensure GnuPG extension is installed
sudo apt-get install php-gnupg
```

#### Certificate Generation Fails

```bash
# Check file permissions
chmod 755 storage/app/certificates
```

#### Email Notifications Not Sending

```bash
# Verify mail configuration
php artisan config:cache
```

### Debug Mode

```php
// Enable detailed logging
'debug_mode' => env('HOF_DEBUG', false),
```

## 📞 Support & Documentation

### Getting Help

- **Documentation**: Comprehensive guides included
- **Issues**: Report bugs via GitHub Issues
- **Feature Requests**: Submit enhancement proposals

### Professional Support

For enterprise customers:

- Priority bug fixes
- Custom feature development
- Migration assistance
- Training and consultation

## 📄 License

### Commercial License

**Who Zid IS Hall of Fame Plugin** is commercial software. Usage requires a
valid license.

#### License Terms

- ✅ **Commercial Use**: Authorized for business and commercial projects
  with valid license
- ✅ **Modification**: Source code may be modified for licensed use
- ✅ **Distribution**: May be distributed with your applications
- ❌ **Resale**: Cannot be resold as standalone product
- ❌ **Open Source**: Source code cannot be published publicly
- ❌ **Free Redistribution**: Cannot be distributed freely

#### Purchase & Licensing

- **Single Site License**: $299 - Use on one domain
- **Multi Site License**: $599 - Use on up to 5 domains  
- **Enterprise License**: $1,199 - Unlimited domains + priority support
- **Custom License**: Contact for enterprise/government pricing

#### Support Included

- 1 year of updates and bug fixes
- Email support (response within 48 hours)
- Installation assistance
- Basic customization guidance

### Third-Party Licenses

This plugin includes third-party packages under their respective licenses:

- **DomPDF**: LGPL 2.1 License
- **GuzzleHTTP**: MIT License
- **Bootstrap**: MIT License (CDN usage)

## 🏢 About Who Zid IS

Who Zid IS is a cybersecurity company specializing in vulnerability research and
security solutions. We develop professional tools for security researchers,
bug bounty platforms, and enterprise security teams.

### Our Products

- **Hall of Fame Plugin**: Professional vulnerability management
- **Security Assessment Tools**: Comprehensive security testing
- **Custom Security Solutions**: Tailored enterprise security

### Contact Information

- **Website**: <https://whozidis.com>
- **Email**: <contact@whozidis.com>
- **Support**: <support@whozidis.com>
- **Sales**: <sales@whozidis.com>

---

## ⭐ Features Roadmap

### Version 1.1 (Coming Soon)

- [ ] Advanced analytics dashboard
- [ ] API endpoints for external integration
- [ ] Automated vulnerability scoring
- [ ] Integration with popular bug bounty platforms

### Version 1.2 (Planned)

- [ ] Mobile application support
- [ ] Advanced notification system
- [ ] Custom workflow builder
- [ ] Enhanced reporting capabilities

---

**© 2025 Who Zid IS. All rights reserved.**

*This plugin is designed for professional security teams and organizations
serious about vulnerability management. Contact us for enterprise licensing
and custom development.*
