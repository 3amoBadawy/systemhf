# Development Guide - SystemHF

## 🚀 **Getting Started**

### **Prerequisites**
- PHP 8.2+
- MySQL 8.0+
- Node.js 18+
- Composer 2.0+
- Git

### **Initial Setup**
```bash
# Clone repository
git clone https://github.com/3amoBadawy/systemhf.git
cd systemhf

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Verify setup
composer quality
```

## 🔧 **Quality Tools & CI/CD**

### **Current Setup Status**
- ✅ **GitHub Actions**: Complete CI/CD workflow
- ✅ **Pre-commit Hooks**: Quality gates enforced
- ✅ **Quality Tools**: All tools configured and working
- ⚠️ **Quality Issues**: 656 PHPStan errors (being resolved)

### **Quality Tools Available**
```bash
# Code style
./vendor/bin/pint                    # Fix code style
./vendor/bin/pint --test            # Check code style

# Static analysis
./vendor/bin/phpstan analyse        # Run PHPStan (Level 8)
./vendor/bin/psalm                  # Run Psalm analysis
./vendor/bin/phpmd app text phpmd.xml  # Run PHPMD

# Testing
php artisan test                     # Run all tests
php artisan test --coverage         # Run with coverage

# Comprehensive check
composer quality                     # Run all quality checks
```

### **Pre-commit Hook (ENFORCED)**
The pre-commit hook automatically runs before each commit:
1. **Laravel Pint**: Code style check
2. **PHPStan**: Static analysis (Level 8)
3. **Tests**: Full test suite execution

**Commits will fail** if any quality check fails.

## 📋 **Development Workflow**

### **Daily Development Process**
```bash
# 1. Start work
git pull origin main
composer install
npm install

# 2. Create feature branch
git checkout -b feature/your-feature-name

# 3. Make changes
# ... edit files ...

# 4. Quality checks (before committing)
composer quality

# 5. Fix any issues found
./vendor/bin/pint                    # Fix code style
./vendor/bin/phpstan analyse        # Fix static analysis
php artisan test                     # Fix test failures

# 6. Commit (quality checks run automatically)
git add .
git commit -m "feat: your feature description"

# 7. Push and create PR
git push origin feature/your-feature-name
```

### **Quality Check Commands**
```bash
# Quick style fix
./vendor/bin/pint

# Check static analysis
./vendor/bin/phpstan analyse --level=8

# Run specific tests
php artisan test --filter=UserTest

# Full quality check
composer quality
```

## 🏗️ **Architecture Guidelines**

### **Model Standards**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 */
class YourModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relationship to parent model
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ParentModel::class);
    }

    /**
     * Relationship to child models
     */
    public function children(): HasMany
    {
        return $this->hasMany(ChildModel::class);
    }
}
```

### **Service Layer Standards**
```php
<?php

namespace App\Services;

use App\Models\YourModel;
use App\Repositories\YourModelRepository;
use Illuminate\Database\Eloquent\Collection;

class YourModelService
{
    public function __construct(
        private YourModelRepository $repository
    ) {}

    /**
     * Get all models with pagination
     */
    public function getAllPaginated(int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    /**
     * Create new model
     */
    public function create(array $data): YourModel
    {
        // Validation
        $this->validateData($data);

        // Business logic
        $processedData = $this->processData($data);

        // Create model
        return $this->repository->create($processedData);
    }

    /**
     * Validate input data
     */
    private function validateData(array $data): void
    {
        // Add validation logic
    }

    /**
     * Process data before creation
     */
    private function processData(array $data): array
    {
        // Add processing logic
        return $data;
    }
}
```

### **Controller Standards**
```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\YourModelRequest;
use App\Services\YourModelService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class YourModelController extends Controller
{
    public function __construct(
        private YourModelService $service
    ) {}

    /**
     * Display a listing of models
     */
    public function index(): View
    {
        $models = $this->service->getAllPaginated();
        
        return view('your-models.index', compact('models'));
    }

    /**
     * Store a newly created model
     */
    public function store(YourModelRequest $request): RedirectResponse
    {
        $model = $this->service->create($request->validated());

        return redirect()
            ->route('your-models.show', $model)
            ->with('success', 'Model created successfully.');
    }
}
```

## 🧪 **Testing Standards**

### **Test Structure**
```php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\YourModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class YourModelTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Test model creation
     */
    public function test_can_create_model(): void
    {
        $data = [
            'name' => 'Test Model',
            'description' => 'Test Description',
        ];

        $response = $this->actingAs($this->user)
            ->post(route('your-models.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('your_models', $data);
    }

    /**
     * Test validation errors
     */
    public function test_validation_errors_on_create(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('your-models.store'), []);

        $response->assertSessionHasErrors(['name']);
    }
}
```

### **Test Coverage Requirements**
- **Minimum 80% coverage** required
- **All public methods** must have tests
- **Edge cases** must be covered
- **Error conditions** must be tested
- **Integration tests** for complex workflows

## 🔍 **Quality Issue Resolution**

### **Common PHPStan Errors & Fixes**

#### **1. Missing Generic Types**
```php
// ❌ Wrong
public function users(): HasMany
{
    return $this->hasMany(User::class);
}

// ✅ Correct
public function users(): HasMany<User>
{
    return $this->hasMany(User::class);
}
```

#### **2. Missing Static Methods**
```php
// ❌ Wrong - calling static method that doesn't exist
$user = User::findOrFail($id);

// ✅ Correct - use Eloquent methods
$user = User::query()->findOrFail($id);
```

#### **3. Property Access Issues**
```php
// ❌ Wrong - accessing undefined property
$name = $this->user->name;

// ✅ Correct - check if property exists
$name = $this->user?->name ?? 'Unknown';
```

### **Code Style Issues**
```bash
# Fix all code style issues automatically
./vendor/bin/pint

# Check what would be fixed
./vendor/bin/pint --test
```

## 📊 **Performance Guidelines**

### **Database Optimization**
```php
// ❌ Wrong - N+1 query problem
foreach ($users as $user) {
    echo $user->profile->name; // Query for each user
}

// ✅ Correct - Eager loading
$users = User::with('profile')->get();
foreach ($users as $user) {
    echo $user->profile->name; // No additional queries
}
```

### **Caching Strategies**
```php
// Cache expensive operations
public function getExpensiveData(): array
{
    return Cache::remember('expensive_data', 3600, function () {
        return $this->repository->getExpensiveData();
    });
}
```

## 🚨 **Common Pitfalls & Solutions**

### **Quality Check Failures**
1. **PHPStan Errors**: Fix static analysis issues first
2. **Code Style**: Run `./vendor/bin/pint` to fix
3. **Test Failures**: Fix failing tests before committing
4. **Coverage Issues**: Add tests for uncovered code

### **Development Blockers**
1. **Pre-commit Hook Failing**: Fix quality issues locally
2. **GitHub Actions Failing**: Check Actions tab for details
3. **Merge Conflicts**: Resolve conflicts and re-run quality checks
4. **Branch Protection**: Ensure quality checks pass before merge

## 📚 **Resources & References**

### **Quality Tools Documentation**
- **Laravel Pint**: [https://laravel.com/docs/pint](https://laravel.com/docs/pint)
- **PHPStan**: [https://phpstan.org/](https://phpstan.org/)
- **Psalm**: [https://psalm.dev/](https://psalm.dev/)
- **PHPMD**: [https://phpmd.org/](https://phpmd.org/)

### **Laravel Best Practices**
- **Eloquent Relationships**: [https://laravel.com/docs/eloquent-relationships](https://laravel.com/docs/eloquent-relationships)
- **Testing**: [https://laravel.com/docs/testing](https://laravel.com/docs/testing)
- **Validation**: [https://laravel.com/docs/validation](https://laravel.com/docs/validation)

### **Team Resources**
- **GitHub Repository**: [https://github.com/3amoBadawy/systemhf](https://github.com/3amoBadawy/systemhf)
- **GitHub Actions**: Check Actions tab for CI/CD status
- **Issues**: Report bugs and feature requests via GitHub Issues

---

**Last Updated**: January 2025  
**Version**: 2.0.0  
**Status**: Active Development  
**Next Review**: February 2025



