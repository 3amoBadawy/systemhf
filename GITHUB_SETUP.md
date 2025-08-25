# 🚀 GitHub Integration & CI/CD Setup Guide

## 📋 **Prerequisites**

- GitHub account
- Git installed on your machine
- Cursor IDE
- Laravel project ready

## 🔗 **Step 1: Connect Cursor to GitHub**

### **Option A: Clone Existing Repository**
```bash
# In Cursor terminal
cd /var/www/systemhf

# Check git status
git status

# Add GitHub remote (replace with your details)
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git

# Or use SSH (recommended)
git remote add origin git@github.com:YOUR_USERNAME/YOUR_REPO_NAME.git

# Verify remote
git remote -v
```

### **Option B: Initialize New Repository**
```bash
# Initialize git
git init

# Add all files
git add .

# Initial commit
git commit -m "Initial commit: Laravel SystemHF project"

# Add GitHub remote
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git

# Push to GitHub
git push -u origin main
```

## ⚙️ **Step 2: GitHub Repository Setup**

### **1. Create Repository on GitHub**
- Go to [github.com](https://github.com)
- Click "New repository"
- Name: `systemhf` (or your preferred name)
- Description: "Laravel Business Management System"
- Make it Private or Public
- Don't initialize with README (we already have one)

### **2. Configure Branch Protection**
- Go to Settings → Branches
- Add rule for `main` branch
- Check "Require status checks to pass before merging"
- Check "Require branches to be up to date before merging"
- Search for "Quality Check & CI/CD" status check
- Check "Require pull request reviews before merging"

## 🚀 **Step 3: GitHub Actions Setup**

### **1. Push Your Code**
```bash
# Add all files
git add .

# Commit changes
git commit -m "Add GitHub Actions workflow and CI/CD setup"

# Push to GitHub
git push origin main
```

### **2. Verify Actions Run**
- Go to your GitHub repository
- Click "Actions" tab
- You should see "Quality Check & CI/CD" workflow running
- Wait for it to complete

### **3. Check Workflow Results**
- Green checkmark = All checks passed
- Red X = Issues found (check logs)

## 🎯 **Step 4: Local Development Workflow**

### **1. Pre-commit Hook (Already Set Up)**
The pre-commit hook will automatically run quality checks before each commit:

```bash
# Try to commit (hook will run automatically)
git commit -m "Your commit message"

# If checks fail, fix issues and try again
./vendor/bin/pint          # Fix code style
./vendor/bin/phpstan analyse # Fix static analysis issues
php artisan test            # Fix failing tests
```

### **2. Daily Development Commands**
```bash
# Before starting work
composer install
npm install

# During development
composer quality           # Run all quality checks
composer test             # Run tests only
./vendor/bin/pint         # Fix code style
./vendor/bin/phpstan analyse # Check static analysis

# Before committing
git add .
git commit -m "Your message"  # Pre-commit hook runs automatically
git push origin your-branch
```

## 🔧 **Step 5: Troubleshooting**

### **Common Issues:**

#### **1. Pre-commit Hook Not Working**
```bash
# Make sure it's executable
chmod +x .git/hooks/pre-commit

# Check if it exists
ls -la .git/hooks/pre-commit
```

#### **2. GitHub Actions Fail**
- Check the Actions tab for error details
- Common issues:
  - Missing dependencies
  - Database connection issues
  - Test failures
  - Code style violations

#### **3. Quality Checks Failing Locally**
```bash
# Fix code style
./vendor/bin/pint

# Fix static analysis
./vendor/bin/phpstan analyse --level=8

# Run tests
php artisan test
```

## 📊 **Step 6: Monitor Quality**

### **1. GitHub Actions Dashboard**
- Go to Actions tab
- Monitor workflow runs
- Check for failed checks

### **2. Local Quality Monitoring**
```bash
# Run comprehensive quality check
composer quality

# Check specific tools
composer pint --test
composer analyse
composer psalm
composer test
```

### **3. Quality Reports**
```bash
# PHPStan report
./vendor/bin/phpstan analyse --generate-baseline

# Psalm report
./vendor/bin/psalm --show-info=true

# PHPMD report
./vendor/bin/phpmd app html phpmd.xml --reportfile phpmd-report.html
```

## 🎉 **Step 7: Best Practices**

### **1. Commit Messages**
```bash
# Good commit messages
git commit -m "feat: add user authentication system"
git commit -m "fix: resolve database connection issue"
git commit -m "docs: update API documentation"
git commit -m "test: add user model tests"
```

### **2. Branch Strategy**
```bash
# Create feature branch
git checkout -b feature/user-management

# Work on feature
# ... make changes ...

# Push feature branch
git push origin feature/user-management

# Create Pull Request on GitHub
# Merge after review and CI checks pass
```

### **3. Regular Maintenance**
```bash
# Update dependencies
composer update
npm update

# Run quality checks
composer quality

# Update baseline files
./vendor/bin/phpstan analyse --generate-baseline
```

## 🔐 **Step 8: Security & Access**

### **1. SSH Keys (Recommended)**
```bash
# Generate SSH key
ssh-keygen -t ed25519 -C "your_email@example.com"

# Add to SSH agent
eval "$(ssh-agent -s)"
ssh-add ~/.ssh/id_ed25519

# Add public key to GitHub
cat ~/.ssh/id_ed25519.pub
# Copy this to GitHub Settings → SSH and GPG keys
```

### **2. Personal Access Token (Alternative)**
- Go to GitHub Settings → Developer settings → Personal access tokens
- Generate new token with repo access
- Use token as password when pushing

## 📈 **Step 9: Advanced Features**

### **1. Code Coverage**
- GitHub Actions automatically uploads coverage to Codecov
- View coverage reports in Pull Requests
- Monitor code coverage trends

### **2. Dependency Scanning**
- GitHub automatically scans for security vulnerabilities
- Receive alerts for outdated packages
- Run `composer audit` locally

### **3. Automated Testing**
- Tests run on every push and PR
- Block merges if tests fail
- Maintain high code quality

## 🎯 **Next Steps**

1. **Push your code to GitHub**
2. **Verify GitHub Actions are running**
3. **Set up branch protection rules**
4. **Start using pre-commit hooks**
5. **Monitor quality metrics**
6. **Set up team collaboration**

## 📞 **Need Help?**

- Check GitHub Actions logs for detailed error messages
- Review Laravel documentation for framework-specific issues
- Use GitHub Issues for project-specific problems
- Check the Actions tab for workflow status

---

**🎉 Congratulations!** You now have a fully integrated CI/CD pipeline with GitHub Actions and local quality enforcement.
