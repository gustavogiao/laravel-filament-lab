# Laravel + Filament + Livewire Best Practices

> **Project Context:** This document outlines the architectural patterns, coding standards, and best practices established in the Laravel Filament Lab project. These guidelines have been derived from analyzing the current codebase implementation and serve as a reference for maintaining consistency and quality.

---

## Table of Contents

1. [Project Architecture](#project-architecture)
2. [PHP Standards](#php-standards)
3. [Model Design](#model-design)
4. [Action Pattern](#action-pattern)
5. [Data Transfer Objects (DTOs)](#data-transfer-objects-dtos)
6. [Enums & Value Objects](#enums--value-objects)
7. [Filament Resources](#filament-resources)
8. [Authorization & Security](#authorization--security)
9. [Database Design](#database-design)
10. [API Development](#api-development)
11. [Testing](#testing)
12. [Code Quality Tools](#code-quality-tools)

---

## 1. Project Architecture

### Modular Monolith Structure

Organize code into domain-specific modules under `app/Modules/`:

```
app/
├── Models/
│   └── BaseModel.php                    # Shared base model
├── Modules/
│   ├── Project/                         # Project management domain
│   │   ├── Actions/
│   │   ├── DTOs/
│   │   ├── Enums/
│   │   ├── Filament/
│   │   │   ├── Resources/
│   │   │   ├── Pages/
│   │   │   └── Widgets/
│   │   ├── Models/
│   │   └── Policies/
│   ├── Task/                            # Task management domain
│   ├── Telemetry/                       # Medical IoT domain
│   └── Shared/                          # Cross-cutting concerns
└── Providers/
```

**Principles:**
- ✅ Each module is self-contained with its own Actions, DTOs, Models, and Policies
- ✅ Modules represent business domains, not technical layers
- ✅ Shared utilities go in `Shared/` module
- ✅ Module boundaries prevent tight coupling

### Benefits of This Structure

1. **Easy Navigation** - Related code lives together
2. **Clear Boundaries** - Domain logic stays within modules
3. **Scalability** - Add modules without affecting others
4. **Team Collaboration** - Multiple developers can work on different modules

---

## 2. PHP Standards

### Strict Type Declarations

**Always** use strict types at the top of every PHP file:

```php
<?php

declare(strict_types=1);

namespace App\Modules\Project\Actions;

final class CreateProjectAction
{
    // ...
}
```

**Why?**
- Catches type errors at runtime
- Enforces type safety
- Makes code behavior predictable
- Prevents implicit type coercion bugs

### Final Classes

**Always** declare concrete classes as `final`:

```php
// ✅ GOOD
final class Project extends BaseModel
{
    // ...
}

final class CreateProjectAction
{
    // ...
}

// ❌ BAD
class Project extends BaseModel  // Missing 'final'
{
    // ...
}
```

**Why?**
- Prevents unintended inheritance
- Makes code easier to reason about
- Allows for better optimization
- Follows composition over inheritance principle

**Exceptions:**
- Abstract base classes (e.g., `BaseModel`)
- Classes explicitly designed for extension

### Type Hints Everywhere

**Always** type hint parameters and return types:

```php
// ✅ GOOD
public function execute(ProjectData $data): Project
{
    // ...
}

// ❌ BAD
public function execute($data)  // Missing type hints
{
    // ...
}
```

**Rules:**
- Type hint all parameters
- Type hint all return types (use `void` if no return)
- Use nullable types (`?string`) when appropriate
- Use union types (`string|int`) when needed
- Use `array` shapes in PHPDoc for complex arrays

---

## 3. Model Design

### Base Model Pattern

Use a shared base model for common functionality:

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

abstract class BaseModel extends Model
{
    use HasFactory;
    use HasUuids;           // All models use UUIDs
    use LogsActivity;       // Automatic activity logging

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
```

### Avoid Fillable, Use Guarded

**Never** use `$fillable`. Instead, use `$guarded = []` and control mass assignment at the action/service layer:

```php
// ✅ GOOD
final class Project extends BaseModel
{
    /**
     * @var list<string>
     */
    protected $guarded = [];
}

// ❌ BAD
final class Project extends BaseModel
{
    protected $fillable = ['name', 'description', 'status'];
}
```

**Why?**
- Fillable lists become outdated as models evolve
- Easier to add new attributes without updating lists
- Mass assignment protection happens in actions/DTOs
- More flexible for development

### Comprehensive PHPDoc Annotations

**Always** document model properties with PHPDoc:

```php
/**
 * @property string $id
 * @property string $name
 * @property string|null $description
 * @property ProjectStatus $status
 * @property bool $is_active
 * @property string $owner_id
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read CarbonInterface|null $deleted_at
 * @property-read User $owner
 * @property-read Collection<int, Task> $tasks
 */
final class Project extends BaseModel
{
    // ...
}
```

**Benefits:**
- IDE autocomplete support
- Static analysis accuracy
- Self-documenting code
- Catches typos early

### Strong Type Casting

**Always** cast attributes to their proper types:

```php
public function casts(): array
{
    return [
        'name' => 'string',
        'description' => 'string',
        'status' => ProjectStatus::class,       // Enum casting
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
```

**Rules:**
- Cast all attributes explicitly
- Use enum classes for status/type fields
- Use `datetime` for timestamps
- Cast boolean flags properly
- Cast JSON columns to `array` or `collection`

### Model Relationships

**Always** type hint relationships and use specific return types:

```php
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Project extends BaseModel
{
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
```

### Model Query Scopes

Keep complex queries in the model using scopes:

```php
final class Patient extends BaseModel
{
    public function activeDevice(): ?Device
    {
        $device = $this->devices()
            ->wherePivot('is_active', true)
            ->first();

        return $device instanceof Device ? $device : null;
    }

    public function hasActiveDevice(): bool
    {
        return $this->devices()
            ->wherePivot('is_active', true)
            ->exists();
    }
}
```

### Model Attributes (PHP 8 Attributes)

Use PHP 8 attributes for model configuration:

```php
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;

#[UsePolicy(ProjectPolicy::class)]
#[UseFactory(ProjectFactory::class)]
final class Project extends BaseModel
{
    // ...
}
```

---

## 4. Action Pattern

### Single Responsibility Actions

**Always** encapsulate business logic in single-purpose action classes:

```php
<?php

declare(strict_types=1);

namespace App\Modules\Project\Actions;

use App\Modules\Project\DTOs\ProjectData;
use App\Modules\Project\Models\Project;

final class CreateProjectAction
{
    public function execute(ProjectData $data): Project
    {
        $project = new Project;

        $project->name = $data->name;
        $project->description = $data->description;
        $project->status = $data->status;
        $project->owner_id = $data->owner_id;

        $project->save();

        return $project;
    }
}
```

**Rules:**
- One action = one business operation
- Actions receive DTOs, not arrays
- Actions return models or void
- Actions are final classes
- Actions use dependency injection

### Complex Actions with Side Effects

For operations with multiple steps:

```php
final class AssignDeviceToPatientAction
{
    public function execute(Patient $patient, string $deviceId): PatientDeviceAssignment
    {
        // Step 1: Deactivate current patient assignments
        $patient->deviceAssignments()
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'unassigned_at' => now(),
            ]);

        // Step 2: Deactivate current device assignments
        PatientDeviceAssignment::query()
            ->where('device_id', $deviceId)
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'unassigned_at' => now(),
            ]);

        // Step 3: Create new active assignment
        return $patient->deviceAssignments()->create([
            'device_id' => $deviceId,
            'assigned_at' => now(),
            'is_active' => true,
        ]);
    }
}
```

### Action Dependency Injection

Use constructor injection for dependencies:

```php
final class CreateProjectWithTasksAction
{
    public function __construct(
        private CreateProjectAction $createProject,
        private CreateTaskAction $createTask,
    ) {}

    public function execute(ProjectData $projectData, array $taskDataCollection): Project
    {
        $project = $this->createProject->execute($projectData);

        foreach ($taskDataCollection as $taskData) {
            $this->createTask->execute($taskData->withProjectId($project->id));
        }

        return $project;
    }
}
```

### When to Use Actions

✅ **Use Actions for:**
- Creating/updating/deleting models
- Complex business logic
- Operations with side effects
- Multi-step processes
- Operations that need to be reusable

❌ **Don't Use Actions for:**
- Simple queries (use model scopes)
- Display logic (belongs in views/components)
- Validation (use FormRequests)

---

## 5. Data Transfer Objects (DTOs)

### Readonly DTOs

**Always** use readonly DTOs for data transfer:

```php
<?php

declare(strict_types=1);

namespace App\Modules\Project\DTOs;

use App\Modules\Project\Enums\ProjectStatus;

final readonly class ProjectData
{
    public function __construct(
        public string $name,
        public ?string $description,
        public ProjectStatus $status,
        public string $owner_id,
    ) {}
}
```

**Why?**
- Immutability prevents accidental changes
- Type safety at compile time
- Clear data contracts
- Easier to reason about

### DTO Factory Methods

Add static factory methods for convenience:

```php
final readonly class ProjectData
{
    public function __construct(
        public string $name,
        public ?string $description,
        public ProjectStatus $status,
        public string $owner_id,
    ) {}

    /**
     * @param array{
     *   name: string,
     *   description?: string|null,
     *   status: ProjectStatus,
     *   owner_id: string,
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'] ?? null,
            status: $data['status'],
            owner_id: $data['owner_id'],
        );
    }

    public static function fromRequest(Request $request): self
    {
        return self::fromArray($request->validated());
    }
}
```

### DTO Methods (Immutable Modifications)

Add helper methods that return new instances:

```php
final readonly class ProjectData
{
    // ... constructor ...

    public function withStatus(ProjectStatus $status): self
    {
        return new self(
            name: $this->name,
            description: $this->description,
            status: $status,
            owner_id: $this->owner_id,
        );
    }

    public function withOwnerId(string $ownerId): self
    {
        return new self(
            name: $this->name,
            description: $this->description,
            status: $this->status,
            owner_id: $ownerId,
        );
    }
}
```

### PHPDoc Array Shapes

Document array structures with PHPDoc:

```php
/**
 * @param array{
 *   heart_rate?: int|null,
 *   blood_pressure_systolic?: int|null,
 *   blood_pressure_diastolic?: int|null,
 *   body_temperature?: float|null,
 *   recorded_at: string,
 * } $data
 */
public static function fromArray(array $data): self
{
    // ...
}
```

---

## 6. Enums & Value Objects

### Rich Enums with Behavior

Use backed enums that implement Filament interfaces:

```php
<?php

declare(strict_types=1);

namespace App\Modules\Project\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use App\Enums\Heroicon;

enum ProjectStatus: string implements HasColor, HasIcon, HasLabel
{
    case Draft = 'draft';
    case InProgress = 'in_progress';
    case Finished = 'finished';

    public function getLabel(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::InProgress => 'In Progress',
            self::Finished => 'Finished',
        };
    }

    public function getColor(): array
    {
        return match ($this) {
            self::Draft => Color::Gray,
            self::InProgress => Color::Yellow,
            self::Finished => Color::Green,
        };
    }

    public function getIcon(): Heroicon
    {
        return match ($this) {
            self::Draft => Heroicon::DocumentText,
            self::InProgress => Heroicon::Clock,
            self::Finished => Heroicon::CheckCircle,
        };
    }

    public function getChartColor(): string
    {
        return match ($this) {
            self::Draft => Color::Gray[500],
            self::InProgress => Color::Yellow[500],
            self::Finished => Color::Green[500],
        };
    }
}
```

### Enum Business Logic

Add business methods to enums:

```php
enum TaskPriority: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Urgent = 'urgent';

    public function getSortOrder(): int
    {
        return match ($this) {
            self::Low => 1,
            self::Medium => 2,
            self::High => 3,
            self::Urgent => 4,
        };
    }

    public function isHigherThan(self $other): bool
    {
        return $this->getSortOrder() > $other->getSortOrder();
    }
}
```

### Permission Enums

Use enums for permission management:

```php
enum PermissionsEnum: string
{
    case View = 'view';
    case ViewAny = 'view_any';
    case Create = 'create';
    case Update = 'update';
    case Delete = 'delete';
    case Restore = 'restore';
    case ForceDelete = 'force_delete';

    public function buildPermissionFor(string $classPath): string
    {
        return $this->value.'_'.Str::snake(Relation::getMorphAlias($classPath));
    }
}
```

### When to Use Enums vs Constants

✅ **Use Enums for:**
- Status fields (draft, in_progress, completed)
- Types/categories with behavior
- Permission/role lists
- Anything with a fixed set of values

❌ **Use Constants for:**
- Configuration values
- Magic numbers
- API keys/endpoints

---

## 7. Filament Resources

### Extract Form and Table Schemas

**Never** define forms and tables inline. Extract them to separate classes:

```php
// ✅ GOOD - Extracted schemas
final class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    public static function form(Schema $schema): Schema
    {
        return ProjectForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectTable::configure($table);
    }
}

// ❌ BAD - Inline schemas
final class ProjectResource extends Resource
{
    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name'),  // Don't do this
            // ...
        ]);
    }
}
```

### Form Schema Structure

```php
<?php

declare(strict_types=1);

namespace App\Modules\Project\Filament\Resources\ProjectResource;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Schema;

final class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Project Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->autofocus(),

                        Textarea::make('description')
                            ->rows(4)
                            ->maxLength(1000),

                        Select::make('status')
                            ->options(ProjectStatus::class)
                            ->required()
                            ->default(ProjectStatus::Draft),

                        Select::make('owner_id')
                            ->relationship('owner', 'name')
                            ->required()
                            ->searchable(),
                    ])
                    ->columns(1),
            ]);
    }
}
```

### Table Schema Structure

```php
<?php

declare(strict_types=1);

namespace App\Modules\Project\Filament\Resources\ProjectResource;

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

final class ProjectTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Project $record): ?string => $record->description),

                TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('owner.name')
                    ->label('Owner')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('tasks_count')
                    ->counts('tasks')
                    ->label('Tasks')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active Projects'),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
```

### Resource Pages with Actions

**Always** use action classes in resource pages:

```php
<?php

declare(strict_types=1);

namespace App\Modules\Project\Filament\Resources\ProjectResource\Pages;

use App\Modules\Project\Actions\CreateProjectAction;
use App\Modules\Project\DTOs\ProjectData;
use App\Modules\Project\Filament\Resources\ProjectResource;
use App\Modules\Shared\Filament\Concerns\RedirectsToIndex;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

final class CreateProject extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = ProjectResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Add current user as owner
        $data['owner_id'] = auth()->id();

        // Use action class for business logic
        return app(CreateProjectAction::class)
            ->execute(ProjectData::fromArray($data));
    }
}
```

### Custom Widgets

Create reusable base widgets for consistency:

```php
<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

abstract class BaseVitalChartWidget extends ChartWidget
{
    use InteractsWithPatient;

    protected int $readingsLimit = 20;
    protected int $cacheTtl = 60;

    abstract protected function readingsQuery(): Collection;
    abstract protected function datasets(Collection $readings): array;

    protected function getData(): array
    {
        if (! $this->hasPatient()) {
            return ['datasets' => [], 'labels' => []];
        }

        $readings = Cache::remember(
            $this->cacheKey(),
            $this->cacheTtl,
            fn () => $this->readingsQuery()
        );

        return [
            'datasets' => $this->datasets($readings),
            'labels' => $this->labels($readings),
        ];
    }

    protected function labels(Collection $readings): array
    {
        return $readings
            ->pluck('recorded_at')
            ->map(fn ($date) => $date->format('H:i'))
            ->toArray();
    }

    protected function cacheKey(): string
    {
        return sprintf(
            'chart:%s:patient:%s:limit:%d',
            static::class,
            $this->patientId,
            $this->readingsLimit
        );
    }
}
```

### Livewire Traits for Shared Behavior

```php
<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Filament\Concerns;

use Livewire\Attributes\On;

trait InteractsWithPatient
{
    public ?string $patientId = null;

    #[On('patientUpdated')]
    public function updatePatientId(?string $patientId): void
    {
        if ($this->patientId !== $patientId) {
            $this->patientId = $patientId;
        }
    }

    protected function hasPatient(): bool
    {
        return filled($this->patientId);
    }
}
```

---

## 8. Authorization & Security

### Gate-Based Super Admin

Define super admin bypass at the gate level:

```php
use Illuminate\Support\Facades\Gate;

Gate::before(function (User $user): ?bool {
    return $user->hasRole(Roles::SuperAdmin) ? true : null;
});
```

### Policy-Based Authorization

Create policies for all models:

```php
<?php

declare(strict_types=1);

namespace App\Modules\Project\Policies;

use App\Modules\Permissions\Enums\PermissionsEnum;
use App\Modules\Project\Models\Project;
use App\Modules\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class ProjectPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can(
            PermissionsEnum::ViewAny->buildPermissionFor(Project::class)
        );
    }

    public function view(User $user, Project $project): bool
    {
        return
            $user->can(PermissionsEnum::View->buildPermissionFor(Project::class))
            && $project->owner_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can(
            PermissionsEnum::Create->buildPermissionFor(Project::class)
        );
    }

    public function update(User $user, Project $project): bool
    {
        // Business rule: Can't update archived projects
        if ($project->is_archived) {
            return false;
        }

        return
            $user->can(PermissionsEnum::Update->buildPermissionFor(Project::class))
            && $project->owner_id === $user->id;
    }

    public function delete(User $user, Project $project): bool
    {
        return
            $user->can(PermissionsEnum::Delete->buildPermissionFor(Project::class))
            && $project->owner_id === $user->id;
    }
}
```

### RBAC Configuration

Create a declarative configuration file:

```php
<?php

// config/rbac.php

use App\Modules\Permissions\Enums\PermissionsEnum;
use App\Modules\Permissions\Enums\Roles;
use App\Modules\Project\Models\Project;
use App\Modules\Task\Models\Task;
use App\Modules\User\Models\User;

return [
    'permissions' => [
        Roles::SuperAdmin->value => [
            Project::class => PermissionsEnum::cases(),
            Task::class => PermissionsEnum::cases(),
            User::class => PermissionsEnum::cases(),
        ],
        Roles::User->value => [
            Project::class => [
                PermissionsEnum::ViewAny,
                PermissionsEnum::View,
                PermissionsEnum::Create,
                PermissionsEnum::Update,
            ],
            Task::class => [
                PermissionsEnum::ViewAny,
                PermissionsEnum::View,
                PermissionsEnum::Create,
                PermissionsEnum::Update,
                PermissionsEnum::Delete,
            ],
        ],
    ],
];
```

### Ownership-Based Access Control

**Always** check ownership in policies:

```php
public function view(User $user, Project $project): bool
{
    return
        $user->can(PermissionsEnum::View->buildPermissionFor(Project::class))
        && $project->owner_id === $user->id;  // Ownership check
}
```

### Hashed API Tokens

For API authentication, **never** store tokens in plain text:

```php
// Store hashed token
$device->api_token_hash = hash('sha256', $token);
$device->save();

// Verify token
$device = Device::where('api_token_hash', hash('sha256', $token))->first();
```

---

## 9. Database Design

### UUIDs as Primary Keys

**Always** use UUIDs for primary keys:

```php
Schema::create('projects', function (Blueprint $table) {
    $table->uuid('id')->primary();

    // ... other columns

    $table->timestamps();
});
```

**Benefits:**
- No sequential ID leakage
- Better for distributed systems
- Avoid enumeration attacks
- Merge-friendly

### Foreign Key Constraints

**Always** define foreign key constraints:

```php
Schema::create('tasks', function (Blueprint $table) {
    $table->uuid('id')->primary();

    // Foreign key with cascade delete
    $table->foreignUuid('project_id')
        ->constrained('projects')
        ->cascadeOnDelete();

    $table->foreignUuid('submitted_by_id')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->timestamps();
});
```

### Soft Deletes for Main Entities

Use soft deletes for important entities:

```php
Schema::create('projects', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('name');
    // ... other columns
    $table->timestamps();
    $table->softDeletes();  // Adds deleted_at
});
```

### Enum Columns

Use native enum columns for status fields:

```php
Schema::create('projects', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('name');
    $table->enum('status', ['draft', 'in_progress', 'finished'])
        ->default('draft');
    $table->timestamps();
});
```

### Pivot Tables with Metadata

Add metadata to pivot tables when needed:

```php
Schema::create('patient_device_assignments', function (Blueprint $table) {
    $table->uuid('id')->primary();

    $table->foreignUuid('patient_id')->constrained()->cascadeOnDelete();
    $table->foreignUuid('device_id')->constrained()->cascadeOnDelete();

    // Pivot metadata
    $table->timestamp('assigned_at');
    $table->timestamp('unassigned_at')->nullable();
    $table->boolean('is_active')->default(true);

    $table->timestamps();

    // Ensure only one active assignment per patient/device
    $table->unique(['patient_id', 'is_active'], 'unique_active_patient');
    $table->unique(['device_id', 'is_active'], 'unique_active_device');
});
```

### Migration Naming

Follow Laravel conventions:

- `create_projects_table` - Creating a table
- `add_status_to_projects_table` - Adding columns
- `create_project_task_pivot_table` - Creating pivot table

---

## 10. API Development

### Form Request Validation

**Always** use Form Requests for validation:

```php
<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreVitalSignsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;  // Authorization handled by middleware
    }

    public function rules(): array
    {
        return [
            'heart_rate' => [
                'nullable',
                'numeric',
                'between:30,220',
            ],
            'blood_pressure_systolic' => [
                'nullable',
                'numeric',
                'between:70,200',
            ],
            'blood_pressure_diastolic' => [
                'nullable',
                'numeric',
                'between:40,130',
            ],
            'respiratory_rate' => [
                'nullable',
                'numeric',
                'between:5,40',
            ],
            'body_temperature' => [
                'nullable',
                'numeric',
                'between:30,45',
            ],
            'recorded_at' => [
                'nullable',
                'date',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'heart_rate.between' => 'Heart rate must be between 30 and 220 bpm.',
            'blood_pressure_systolic.between' => 'Systolic BP must be between 70 and 200 mmHg.',
            'blood_pressure_diastolic.between' => 'Diastolic BP must be between 40 and 130 mmHg.',
            'respiratory_rate.between' => 'Respiratory rate must be between 5 and 40 breaths/min.',
            'body_temperature.between' => 'Body temperature must be between 30°C and 45°C.',
        ];
    }
}
```

### Custom Middleware for Authentication

Create middleware for custom authentication:

```php
<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Http\Middleware;

use App\Modules\Telemetry\Models\Device;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class AuthenticateDevice
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        abort_if(! $token, 401, 'Unauthorized: Missing device token');

        $device = Device::where('api_token_hash', hash('sha256', $token))->first();

        abort_if(! $device, 401, 'Unauthorized: Invalid device token');

        // Add device to request attributes
        $request->attributes->set('device', $device);

        return $next($request);
    }
}
```

### Controller with Action Pattern

```php
<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Telemetry\Actions\RecordVitalSignsAction;
use App\Modules\Telemetry\Http\Requests\StoreVitalSignsRequest;
use Illuminate\Http\JsonResponse;

final class TelemetryController extends Controller
{
    public function store(
        StoreVitalSignsRequest $request,
        RecordVitalSignsAction $action
    ): JsonResponse {
        $device = $request->attributes->get('device');

        $reading = $action->execute($device, $request->validated());

        return response()->json([
            'message' => 'Vital signs recorded successfully',
            'data' => $reading,
        ], 201);
    }
}
```

### API Routes

```php
// routes/api.php
use App\Modules\Telemetry\Http\Controllers\TelemetryController;
use App\Modules\Telemetry\Http\Middleware\AuthenticateDevice;

Route::middleware(['device.auth'])
    ->prefix('telemetry')
    ->group(function (): void {
        Route::post('vital-signs', [TelemetryController::class, 'store']);
    });
```

---

## 11. Testing

### Pest Testing Framework

Use Pest for cleaner test syntax:

```php
<?php

declare(strict_types=1);

use App\Modules\Permissions\Enums\Roles;
use App\Modules\Project\Models\Project;
use App\Modules\User\Models\User;

it('restricts access based on permissions and ownership', function (): void {
    $user = User::factory()->create();
    $user->assignRole(Roles::User->value);

    $otherUser = User::factory()->create();
    $projectOfOther = Project::factory()->create(['owner_id' => $otherUser->id]);
    $myProject = Project::factory()->create(['owner_id' => $user->id]);

    expect($user->can('view', $projectOfOther))->toBeFalse()
        ->and($user->can('view', $myProject))->toBeTrue();
});
```

### Filament Resource Testing

Test Filament resources with Livewire testing:

```php
use App\Modules\Project\Filament\Resources\ProjectResource\Pages\ListProjects;
use Livewire\Livewire;

it('has edit and delete actions in the table', function (): void {
    $project = Project::factory()->create(['owner_id' => $this->user->id]);

    Livewire::test(ListProjects::class)
        ->assertTableActionExists('edit', record: $project)
        ->assertTableActionExists('delete', record: $project);
});

it('can render the create page', function (): void {
    $this->get(ProjectResource::getUrl('create'))
        ->assertSuccessful();
});
```

### Factory Patterns

Create comprehensive factories:

```php
<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Project\Models;

use App\Modules\Project\Enums\ProjectStatus;
use App\Modules\Project\Models\Project;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
final class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(ProjectStatus::cases()),
            'owner_id' => User::factory(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProjectStatus::Draft,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProjectStatus::InProgress,
        ]);
    }

    public function finished(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProjectStatus::Finished,
        ]);
    }
}
```

### Test Organization

```
tests/
├── Feature/
│   ├── Modules/
│   │   ├── Permissions/
│   │   │   └── RBACTest.php
│   │   ├── Project/
│   │   │   ├── ProjectResourceTest.php
│   │   │   └── ProjectPolicyTest.php
│   │   └── Task/
│   │       └── TaskResourceTest.php
│   └── ...
└── Unit/
    └── ...
```

---

## 12. Code Quality Tools

### Laravel Pint

Use Laravel Pint for code formatting:

```json
{
    "preset": "laravel"
}
```

**Run formatting:**
```bash
./vendor/bin/pint
```

### Rector for Refactoring

Configure Rector for automated refactoring:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/tests',
    ])
    ->withTypeCoverageLevel(0)
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(0);
```

### Larastan for Static Analysis

Add PHPStan configuration:

```neon
# phpstan.neon
includes:
    - ./vendor/larastan/larastan/extension.neon

parameters:
    level: 5
    paths:
        - app
        - tests
    excludePaths:
        - app/Providers
    checkMissingIterableValueType: false
```

**Run analysis:**
```bash
./vendor/bin/phpstan analyse
```

### Composer Scripts

Add helpful scripts to `composer.json`:

```json
{
    "scripts": {
        "lint": "./vendor/bin/pint --parallel",
        "test:lint": "./vendor/bin/pint --parallel --test",
        "test": [
            "@php artisan config:clear",
            "@test:lint",
            "@php artisan test"
        ],
        "analyse": "./vendor/bin/phpstan analyse",
        "refactor": "./vendor/bin/rector process"
    }
}
```

### Pre-commit Hooks

Consider adding Git hooks for quality checks:

```bash
#!/bin/sh
# .git/hooks/pre-commit

composer test:lint
composer analyse
```

---

## Summary: Core Principles

### 1. Type Safety First
- ✅ Strict types in every file
- ✅ Type hint everything
- ✅ Use enums for fixed values
- ✅ Readonly DTOs

### 2. Single Responsibility
- ✅ One action = one operation
- ✅ Extract form/table schemas
- ✅ Small, focused classes

### 3. Immutability
- ✅ Readonly properties
- ✅ Final classes
- ✅ Avoid setters

### 4. Security by Default
- ✅ UUIDs not sequential IDs
- ✅ Hash sensitive data
- ✅ Policy-based authorization
- ✅ Ownership checks

### 5. Documentation
- ✅ PHPDoc annotations
- ✅ Type hints
- ✅ Self-documenting code

### 6. Testing
- ✅ Pest for clean tests
- ✅ Factories for test data
- ✅ Feature tests for business logic

### 7. Code Quality
- ✅ Automated formatting (Pint)
- ✅ Static analysis (Larastan)
- ✅ Refactoring tools (Rector)

---

## Quick Reference Checklist

When creating a new feature, ensure:

- [ ] Strict types declared
- [ ] Final classes used
- [ ] Models use `$guarded = []` (not fillable)
- [ ] PHPDoc annotations on models
- [ ] Business logic in Actions
- [ ] DTOs are readonly
- [ ] Enums for status fields
- [ ] Form/Table extracted from Resource
- [ ] Policies for authorization
- [ ] Ownership checks in policies
- [ ] UUIDs as primary keys
- [ ] Foreign key constraints
- [ ] Form Request validation
- [ ] Type hints everywhere
- [ ] Tests written
- [ ] Code formatted with Pint

---

**Last Updated:** February 4, 2026  
**Project:** Laravel Filament Lab  
**Maintained By:** Project Team
