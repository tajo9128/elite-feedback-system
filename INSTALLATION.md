# Elite Feedback System - Installation Guide

## 📦 For Hostinger Users

### Step 1: Download the Plugin

You have received the complete plugin folder: `elite-feedback-system/`

### Step 2: Upload to Hostinger

**Method A: Using File Manager (Recommended)**

1. Login to your Hostinger hPanel
2. Go to **Files** → **File Manager**
3. Navigate to `public_html/wp-content/plugins/`
4. Click **Upload Files**
5. Select the entire `elite-feedback-system` folder (or ZIP it first and upload the ZIP)
6. If you uploaded a ZIP, right-click and select **Extract**

**Method B: Using FTP**

1. Download an FTP client (FileZilla recommended)
2. Get FTP credentials from Hostinger hPanel → FTP Accounts
3. Connect to your server
4. Navigate to `/public_html/wp-content/plugins/`
5. Upload the `elite-feedback-system` folder

### Step 3: Activate the Plugin

1. Login to your WordPress Admin Panel
2. Go to **Plugins** → **Installed Plugins**
3. Find "Elite Feedback System"
4. Click **Activate**

### Step 4: Initial Configuration

After activation, the plugin will:
- ✅ Automatically create 6 database tables
- ✅ Set up default settings
- ✅ Add a new menu "Feedback System" in WordPress admin

**Configure Settings:**

1. Go to **Feedback System** → **Settings**
2. Fill in:
   - Institution Name
   - Admin Email (for notifications)
   - NAAC Grade (if applicable)
   - NBA Accredited Programs (comma-separated)

### Step 5: Create Your First Feedback Form

**Quick Start - NAAC Template:**

1. Go to **Feedback System** → **Forms**
2. Click **"Add New Form"**
3. Scroll to **"Quick Templates"** section
4. Click **"Use NAAC Template"**
5. Select:
   - Criterion: "Criterion 2: Teaching-Learning"
   - Stakeholder: "Students"
   - Academic Year: "2024-2025"
   - Semester: "Even Semester"
6. Click **"Create from Template"**

The form will be automatically created with all questions!

### Step 6: Display the Form on Your Website

**Option 1: Add to a Page**

1. Go to **Pages** → **Add New** (or edit existing page)
2. Add this shortcode where you want the feedback form to appear:

```
[efs_feedback]
```

For a specific form:
```
[efs_feedback_form id="1"]
```

**Option 2: Create a Dedicated Feedback Page**

1. Create a new page called "Feedback"
2. Add shortcode: `[efs_feedback]`
3. Publish the page
4. Visit the page to see all active feedback forms

### Step 7: Collect Responses

Students/stakeholders can now:
1. Visit your feedback page
2. Click on any active form
3. Fill out the questions
4. Submit feedback

Responses are stored securely in your database.

### Step 8: View Analytics & Generate Reports

**View Dashboard:**
- Go to **Feedback System** → **Dashboard**
- See total forms, responses, and recent activity

**Generate NAAC Report:**
1. Go to **Feedback System** → **NAAC Reports**
2. Select Criterion (or "All")
3. Select Academic Year
4. Choose format: PDF, Excel, or CSV
5. Click **"Generate Report"**
6. Download the report

**Generate NBA Report:**
1. Go to **Feedback System** → **NBA Reports**
2. Select Program/Department
3. Select Academic Year
4. Choose format
5. Click **"Generate Report"**

---

## 🔧 Advanced Configuration

### Email Notifications

The plugin sends emails for:
- Feedback requests
- Submission confirmations
- Admin notifications

**Recommended: Install SMTP Plugin**

For reliable email delivery:
1. Install "WP Mail SMTP" plugin
2. Configure with Gmail/SendGrid/Mailgun
3. Test email delivery

### Creating Custom Forms

1. Go to **Feedback System** → **Forms** → **Add New**
2. Enter form details:
   - Title
   - Description
   - Stakeholder Type
   - Academic Year/Semester
3. Add questions using the form builder:
   - Click **"Add Question"**
   - Choose question type (Scale, MCQ, Text, etc.)
   - Enter question text
   - Configure options
   - Set as required (yes/no)
4. Click **"Publish"**

### Using REST API

The plugin provides a REST API for integration:

**Get active forms:**
```
GET https://yoursite.com/wp-json/efs/v1/forms/active
```

**Submit feedback:**
```
POST https://yoursite.com/wp-json/efs/v1/responses
{
  "form_id": 1,
  "responses": [
    {"question_id": 1, "value": "5"},
    {"question_id": 2, "value": "Good course"}
  ]
}
```

---

## 🎯 Quick Templates Available

### NAAC Templates (All 7 Criteria)

1. **Criterion 1**: Curricular Aspects
2. **Criterion 2**: Teaching-Learning and Evaluation
3. **Criterion 3**: Research, Innovations and Extension
4. **Criterion 4**: Infrastructure and Learning Resources
5. **Criterion 5**: Student Support and Progression
6. **Criterion 6**: Governance, Leadership and Management
7. **Criterion 7**: Institutional Values and Best Practices

### NBA Templates

1. **Course Feedback** - Student feedback on specific courses
2. **Faculty Feedback** - Student evaluation of faculty teaching
3. **PO Attainment** - Self-assessment of Program Outcome attainment
4. **Employer Feedback** - Employer assessment of graduate competencies
5. **Alumni Feedback** - Alumni assessment of program effectiveness

---

## 🐛 Troubleshooting

### Issue: Plugin doesn't appear after uploading

**Solution:**
- Ensure folder is named exactly `elite-feedback-system`
- Check folder is in `/wp-content/plugins/` (not in a subfolder)
- Verify file permissions (755 for directories, 644 for files)

### Issue: Database tables not created

**Solution:**
- Deactivate and reactivate the plugin
- Check PHP error logs
- Ensure MySQL user has CREATE TABLE privileges

### Issue: Forms not displaying

**Solution:**
- Check if form is marked as "Active"
- Verify correct shortcode is used
- Clear WordPress cache

### Issue: Reports not generating

**Solution:**
- Check `/wp-content/uploads/` folder is writable (permissions: 755)
- Create directory manually: `/wp-content/uploads/elite-feedback-system/reports/`
- Increase PHP `max_execution_time` to 60 seconds

---

## 📊 File Permissions (For Hostinger)

Set these permissions via File Manager:

```
elite-feedback-system/               (755)
├── elite-feedback-system.php       (644)
├── includes/                       (755)
├── admin/                          (755)
├── public/                         (755)
└── templates/                      (755)

/wp-content/uploads/elite-feedback-system/  (755)
```

---

## ✅ Verification Checklist

After installation, verify:

- [ ] Plugin appears in WordPress Admin → Plugins
- [ ] "Feedback System" menu appears in admin sidebar
- [ ] Can access Dashboard page
- [ ] Can create a new form
- [ ] Shortcode displays forms on frontend
- [ ] Can submit test feedback
- [ ] Responses appear in Analytics
- [ ] Can generate reports

---

## 📞 Support

If you encounter any issues:

1. Check the troubleshooting section above
2. Review WordPress debug.log file
3. Contact your developer/administrator

**System Requirements:**
- ✅ WordPress 5.8+
- ✅ PHP 7.4+
- ✅ MySQL 5.7+ / MariaDB 10.3+
- ✅ Sufficient disk space for reports

---

**Congratulations!** 🎉 Your feedback system is now ready for NAAC and NBA accreditation data collection.
