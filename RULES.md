# SystemHF Project Rules & Standards

## 🎯 **Project Overview**

SystemHF is a Laravel-based business management system with strict quality standards and automated CI/CD enforcement. This document outlines the rules, standards, and processes that must be followed.

## 🚀 **CI/CD & Quality Enforcement**

### **Quality Gates (MANDATORY)**
- **All commits must pass quality checks** before being allowed
- **Pre-commit hooks are enforced** - cannot bypass quality checks
- **GitHub Actions run automatically** on every push and PR
- **Branch protection rules** require quality checks to pass

### **Quality Tools Configuration**
- **Laravel Pint**: Code style enforcement (PSR-12)
- **PHPStan**: Static analysis (Level 8 - Maximum)
- **Psalm**: Additional static analysis
- **PHPMD**: Mess detection and code smell identification
- **Deptrac**: Dependency analysis and architecture validation
- **PHPUnit**: Test execution and coverage

## 📋 **Commit Rules**

### **Pre-commit Requirements**
1. **Code Style**: Must pass Laravel Pint
2. **Static Analysis**: Must pass PHPStan Level 8
3. **Tests**: Must pass all tests
4. **No Quality Issues**: Zero tolerance for quality violations

### **Commit Message Standards**
```bash
# Format: type(scope): description
feat(auth): add user authentication system
fix(database): resolve connection timeout issue
docs(api): update endpoint documentation
test(users): add user model tests
refactor(services): improve business logic service
```

### **Branch Naming Convention**
```bash
feature/user-management
bugfix/database-connection
hotfix/security-vulnerability
refactor/authentication-system
```

## 🔧 **Code Quality Standards**

### **PHPStan Level 8 Requirements**
- **Zero static analysis errors** allowed
- **Generic type specifications** required for Eloquent relationships
- **Property access validation** enforced
- **Method signature compatibility** required
- **Return type specifications** mandatory

### **Laravel Pint Standards**
- **PSR-12 compliance** required
- **Consistent formatting** across all files
- **No style violations** tolerated
- **Automatic fixing** available via `./vendor/bin/pint`

### **Test Coverage Requirements**
- **Minimum 80% coverage** required
- **All public methods** must have tests
- **Feature tests** for all routes
- **Unit tests** for all services
- **Integration tests** for complex workflows

## 🏗️ **Architecture Rules**

### **Model Standards**
- **Eloquent traits** must be properly specified
- **Relationship methods** must have generic types
- **Fillable properties** must be defined
- **Casts** must be properly typed
- **Scopes** must be properly documented

### **Service Layer Rules**
- **Interface contracts** must be implemented
- **Method signatures** must match interfaces
- **Error handling** required for all operations
- **Logging** mandatory for critical operations
- **Validation** required for all inputs

### **Repository Pattern**
- **Base repository interface** must be followed
- **Generic types** required for collections
- **Query building** must be type-safe
- **Pagination** must return proper types
- **Error handling** for database operations

## 📊 **Quality Metrics & Monitoring**

### **Daily Quality Checks**
```bash
# Before starting work
composer quality

# During development
./vendor/bin/phpstan analyse --level=8
./vendor/bin/pint --test
php artisan test
```

### **Quality Reports**
- **PHPStan baseline** updated regularly
- **Code coverage reports** generated on each run
- **Quality metrics** tracked over time
- **Performance benchmarks** maintained

### **Quality Thresholds**
- **Static Analysis**: 0 errors (PHPStan Level 8)
- **Code Style**: 0 violations (Laravel Pint)
- **Test Coverage**: Minimum 80%
- **Security**: 0 vulnerabilities (composer audit)

## 🚫 **Prohibited Practices**

### **Code Quality Violations**
- **Bypassing quality checks** (pre-commit hooks)
- **Committing with errors** (quality gates)
- **Ignoring static analysis** warnings
- **Skipping tests** for new features
- **Using deprecated methods** or patterns

### **Architecture Violations**
- **Direct database queries** in controllers
- **Business logic** in views or routes
- **Hardcoded values** in code
- **Missing error handling** for critical operations
- **Inconsistent naming** conventions

### **Security Violations**
- **Exposing sensitive data** in logs or errors
- **Missing input validation** for user data
- **SQL injection** vulnerabilities
- **XSS vulnerabilities** in output
- **Missing authentication** checks

## ✅ **Required Practices**

### **Code Organization**
- **PSR-4 autoloading** compliance
- **Consistent file structure** following Laravel conventions
- **Proper namespacing** for all classes
- **Clear separation** of concerns
- **Documentation** for complex logic

### **Error Handling**
- **Try-catch blocks** for external operations
- **Proper logging** for all errors
- **User-friendly error messages** (no technical details)
- **Graceful degradation** for non-critical failures
- **Error reporting** to monitoring systems

### **Performance Standards**
- **Eager loading** for relationships
- **Database indexing** for frequently queried fields
- **Caching** for expensive operations
- **Query optimization** for complex operations
- **Memory management** for large datasets

## 🔄 **Development Workflow**

### **Feature Development**
1. **Create feature branch** from main
2. **Implement feature** following standards
3. **Write tests** for all functionality
4. **Run quality checks** locally
5. **Create pull request** with quality checks passing
6. **Code review** by team members
7. **Merge** after approval and CI checks

### **Bug Fixes**
1. **Create bugfix branch** from main
2. **Identify root cause** of the issue
3. **Implement fix** following standards
4. **Add regression tests** to prevent recurrence
5. **Run quality checks** locally
6. **Create pull request** with fix
7. **Merge** after review and CI checks

### **Hotfixes**
1. **Create hotfix branch** from main
2. **Implement critical fix** following standards
3. **Minimal changes** to resolve issue
4. **Test thoroughly** in staging environment
5. **Deploy to production** after approval
6. **Merge back** to main and develop branches

## 📚 **Documentation Requirements**

### **Code Documentation**
- **PHPDoc blocks** for all public methods
- **Inline comments** for complex logic
- **README files** for major components
- **API documentation** for endpoints
- **Database schema** documentation

### **Process Documentation**
- **Setup instructions** for new developers
- **Deployment procedures** documented
- **Troubleshooting guides** for common issues
- **Architecture decisions** recorded
- **Change logs** maintained

## 🚨 **Violation Consequences**

### **Quality Violations**
- **Commits blocked** until issues resolved
- **Pull requests rejected** if quality checks fail
- **Code review required** for all changes
- **Additional testing** may be required
- **Performance impact** on development velocity

### **Repeated Violations**
- **Additional review** requirements
- **Mentoring sessions** for improvement
- **Temporary restrictions** on merge permissions
- **Performance review** with team lead
- **Training requirements** for quality tools

## 🎉 **Compliance Benefits**

### **Code Quality**
- **Reduced bugs** and production issues
- **Easier maintenance** and refactoring
- **Better performance** and scalability
- **Improved security** and reliability
- **Faster development** velocity

### **Team Benefits**
- **Consistent codebase** across team
- **Easier onboarding** for new developers
- **Better collaboration** and code reviews
- **Reduced technical debt** over time
- **Professional development** and growth

---

**Last Updated**: January 2025  
**Version**: 2.0.0  
**Status**: Active Enforcement  
**Next Review**: February 2025
