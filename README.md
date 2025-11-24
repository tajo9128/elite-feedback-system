# Elite Feedback System - WordPress Plugin

**A comprehensive feedback collection and analytics system for NAAC and NBA accreditation compliance.**

[![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-blue)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v2-green)](https://www.gnu.org/licenses/gpl-2.0.html)

## 🎯 Overview

Elite Feedback System is a powerful WordPress plugin designed specifically for educational institutions in India to collect, analyze, and report multi-stakeholder feedback for NAAC (National Assessment and Accreditation Council) and NBA (National Board of Accreditation) accreditation processes.

### Key Features

✅ **Multi-Stakeholder Support** - Collect feedback from Students, Faculty, Alumni, Employers, and Parents  
✅ **NAAC Compliance** - Pre-built templates for all 7 NAAC criteria  
✅ **NBA Compliance** - Program Outcome (PO) and Course Outcome (CO) feedback templates  
✅ **Advanced Form Builder** - Create custom feedback forms with multiple question types  
✅ **Real-time Analytics** - Statistical analysis with averages, median, standard deviation  
✅ **Professional Reports** - Export NAAC/NBA reports in PDF, Excel, and CSV formats  
✅ **Anonymous Feedback** - Optional anonymity for honest responses  
✅ **Email Automation** - Send feedback requests and confirmations automatically  
✅ **Responsive Design** - Works seamlessly on desktop, tablet, and mobile  
✅ **Hostinger Compatible** - Optimized for shared hosting environments  

## 📋 Requirements

- **WordPress**: 5.8 or higher
- **PHP**: 7.4 or higher (8.0+ recommended)
- **MySQL**: 5.7+ or MariaDB 10.3+
- **Server**: Any WordPress-compatible hosting (Hostinger, Bluehost, SiteGround, etc.)

## 🚀 Installation

### Method 1: Upload via WordPress Admin

1. Download the plugin ZIP file
2. Log in to your WordPress admin panel
3. Navigate to **Plugins** → **Add New**
4. Click **Upload Plugin** button
5. Choose the downloaded ZIP file
6. Click **Install Now**
7. After installation, click **Activate Plugin**

### Method 2: FTP Upload

1. Extract the ZIP file to get the `elite-feedback-system` folder
2. Upload the folder to `/wp-content/plugins/` directory via FTP
3. Log in to WordPress admin
4. Navigate to **Plugins** and activate "Elite Feedback System"

### Method 3: Hostinger File Manager

1. Login to your Hostinger panel
2. Open **File Manager**
3. Navigate to `public_html/wp-content/plugins/`
4. Upload and extract the ZIP file
5. Activate from WordPress admin → Plugins

## 🎓 Quick Start Guide

### 1. Initial Setup

After activation:

1. Go to **Feedback System** in WordPress admin menu
2. Click **Settings** to configure:
   - Institution Name
   - Institution Logo
   - Admin Email for notifications
   - NAAC Grade (if applicable)
   - NBA Accredited Programs

### 2. Create Your First Feedback Form

**Option A: Use NAAC Templates**

```php
// For Students - NAAC Criterion 2
1. Go to Feedback System → Forms
2. Click "Create from NAAC Template"
3. Select "Criterion 2: Teaching-Learning and Evaluation"
4. Choose Stakeholder: "Students"
5. Set Academic Year: "2024-2025"
6. Set Semester: "Even"
7. Click "Create Form"
```

**Option B: Use NBA Templates**

```php
// For Course Feedback
1. Go to Feedback System → Forms
2. Click "Create from NBA Template"
3. Select "Course Feedback"
4. Enter Course Name
5. Select Program Outcomes covered
6. Click "Create Form"
```

**Option C: Create Custom Form**

1. Go to **Feedback System** → **Forms** → **Add New**
2. Enter form title and description
3. Select stakeholder type
4. Add questions using the form builder
5. Publish the form

### 3. Display Feedback Forms

Add to any page or post using shortcodes:

```php
// Display all active feedback forms
[efs_feedback]

// Display forms for specific stakeholder
[efs_feedback stakeholder="students"]

// Display a specific form
[efs_feedback_form id="123"]

// Show user's submitted responses (for logged-in users)
[efs_my_responses]
```

### 4. Generate Reports

**NAAC Reports:**

1. Go to **Feedback System** → **NAAC Reports**
2. Select Criterion (1-7) or "All Criteria"
3. Select Academic Year
4. Choose format (PDF/Excel/CSV)
5. Click **Generate Report**

**NBA Reports:**

1. Go to **Feedback System** → **NBA Reports**
2. Select Program/Department
3. Select Academic Year
4. Choose format (PDF/Excel/CSV)
5. Click **Generate Report**

## 📊 Features in Detail

### Question Types Supported

1. **Scale/Rating** - 5-point or custom scale (e.g., Strongly Disagree to Strongly Agree)
2. **Multiple Choice (MCQ)** - Single selection from options
3. **Checkbox** - Multiple selections
4. **Yes/No** - Binary choice questions
5. **Text** - Short text responses
6. **Textarea** - Long-form text responses

### NAAC Criteria Coverage

| Criterion |Description |
|-----------|---------------------------------------------------|
| 1         | Curricular Aspects                                 |
| 2         | Teaching-Learning and Evaluation                   |
| 3         | Research, Innovations and Extension                |
| 4         | Infrastructure and Learning Resources              |
| 5         | Student Support and Progression                    |
| 6         | Governance, Leadership and Management              |
| 7         | Institutional Values and Best Practices            |

### NBA Program Outcomes (PO1-PO12)

- PO1: Engineering Knowledge
- PO2: Problem Analysis
- PO3: Design/Development of Solutions
- PO4: Conduct Investigations
- PO5: Modern Tool Usage
- PO6: The Engineer and Society
- PO7: Environment and Sustainability
- PO8: Ethics
- PO9: Individual and Team Work
- PO10: Communication
- PO11: Project Management and Finance
- PO12: Life-long Learning

## 🔌 REST API Endpoints

The plugin provides a comprehensive REST API:

```
GET    /wp-json/efs/v1/forms              - List all forms
POST   /wp-json/efs/v1/forms              - Create new form
GET    /wp-json/efs/v1/forms/active       - Get active forms
GET    /wp-json/efs/v1/forms/{id}         - Get specific form
PUT    /wp-json/efs/v1/forms/{id}         - Update form
DELETE /wp-json/efs/v1/forms/{id}         - Delete form

POST   /wp-json/efs/v1/responses          - Submit feedback
GET    /wp-json/efs/v1/forms/{id}/responses - Get form responses

GET    /wp-json/efs/v1/analytics/overview - Dashboard analytics
GET    /wp-json/efs/v1/analytics/forms/{id} - Form-specific analytics

GET    /wp-json/efs/v1/reports/naac       - Generate NAAC report
GET    /wp-json/efs/v1/reports/nba        - Generate NBA report
```

## 📁 Database Structure

The plugin creates 6 database tables:

- `wp_efs_forms` - Feedback form definitions
- `wp_efs_questions` - Form questions
- `wp_efs_responses` - User responses
- `wp_efs_stakeholders` - Stakeholder information
- `wp_efs_reports` - Generated reports cache
- `wp_efs_settings` - Plugin settings

## 🎨 Customization

### Modify Email Templates

Edit email templates in `includes/class-efs-email.php`

### Custom CSS

Override default styles by adding custom CSS in your theme or via WordPress Customizer.

### Modify Templates

NAAC templates: `templates/class-efs-naac-templates.php`  
NBA templates: `templates/class-efs-nba-templates.php`

## 🐛 Troubleshooting

### Forms not displaying?

- Ensure you're using the correct shortcode
- Check if the form is marked as "Active"
- Verify page permissions

### Reports not generating?

- Check file permissions on `/wp-content/uploads/elite-feedback-system/`
- Ensure PHP `max_execution_time` is adequate (60+ seconds)
- Verify database contains response data

### Email notifications not working?

- Configure SMTP plugin (WP Mail SMTP recommended)
- Check spam folder
- Verify admin email in Settings

## 📞 Support

For issues, questions, or feature requests:

- **Documentation**: Full documentation available in plugin directory
- **Support Forum**: [WordPress.org Plugin Forum](https://wordpress.org/support/plugin/elite-feedback-system)
- **Email**: support@yoursite.com

## 📜 License

This plugin is licensed under the GPL v2 or later.

```
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
```

## 🙏 Credits

Developed for educational institutions to simplify NAAC and NBA accreditation processes.

**Version:** 1.0.0  
**Author:** Your Name  
**Tested up to:** WordPress 6.8  
**Stable tag:** 1.0.0  

---

Made with ❤️ for Indian Educational Institutions
