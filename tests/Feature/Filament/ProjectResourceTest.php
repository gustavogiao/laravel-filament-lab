<?php

use App\Modules\Project\Filament\Resources\ProjectResource;
use App\Modules\Project\Models\Project;
use App\Modules\User\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can list projects', function () {
    Project::factory()->count(5)->create();

    actingAs($this->user)
        ->get(ProjectResource::getUrl('index'))
        ->assertSuccessful();
});

it('has a create button on the list page', function () {
    actingAs($this->user)
        ->get(ProjectResource::getUrl('index'))
        ->assertSee('New project')
        ->assertSee('projects/create');
});

it('has edit and delete actions in the table', function () {
    $project = Project::factory()->create();

    Livewire::test(ProjectResource\Pages\ListProjects::class)
        ->assertTableActionExists(EditAction::class)
        ->assertTableActionExists(DeleteAction::class);
});

it('has name and description fields in the form', function () {
    Livewire::test(ProjectResource\Pages\CreateProject::class)
        ->assertFormExists()
        ->assertFormFieldExists('name')
        ->assertFormFieldExists('description')
        ->assertFormFieldExists('is_active');
});
