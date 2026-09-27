<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Barangay;
use App\Models\DemographicSector;
use App\Models\SurveyPeriod;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssistantPortalModulesTest extends TestCase
{
    use RefreshDatabase;

    protected User $assistant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->assistant = User::updateOrCreate(
            ['email' => 'assistant@worthyacosta.ph'],
            [
                'name' => 'Assistant Officer',
                'username' => 'assistant',
                'password' => Hash::make('password'),
                'role' => 'assistant',
            ]
        );

        $this->seed(\Database\Seeders\BarangaySeeder::class);
        $this->seed(\Database\Seeders\DemographySeeder::class);
        $this->seed(\Database\Seeders\SurveySeeder::class);
    }

    public function test_assistant_assistance_module_has_forms_datatable_and_view_modal_without_maps(): void
    {
        $response = $this->actingAs($this->assistant)->get(route('assistant.assistance.index'));

        $response->assertStatus(200);
        $response->assertSee('Assistance Records Management');
        $response->assertSee('recordsTable', false);
        $response->assertSee('btn-action-view', false);
        $response->assertSee('addRecordModalBackdrop', false);
        $response->assertSee('editRecordModalBackdrop', false);
        $response->assertSee('viewRecordModalBackdrop', false);
        $response->assertSee('deleteModalBackdrop', false);
        $response->assertSee('toastContainer', false);

        // Crucial: Assistant view must NOT contain interactive map elements
        $response->assertDontSee('interactive-map-container', false);
        $response->assertDontSee('id="mapStage"', false);
        $response->assertDontSee('leaflet.css', false);
    }

    public function test_assistant_demography_module_has_forms_datatable_and_view_modal_without_maps(): void
    {
        $response = $this->actingAs($this->assistant)->get(route('assistant.demography.index'));

        $response->assertStatus(200);
        $response->assertSee('Demography Records Management');
        $response->assertSee('recordsTable', false);
        $response->assertSee('btn-action-view', false);
        $response->assertSee('addRecordModalBackdrop', false);
        $response->assertSee('editRecordModalBackdrop', false);
        $response->assertSee('viewRecordModalBackdrop', false);
        $response->assertSee('deleteModalBackdrop', false);
        $response->assertSee('toastContainer', false);

        $response->assertDontSee('interactive-map-container', false);
        $response->assertDontSee('id="mapStage"', false);
    }

    public function test_assistant_directory_module_has_forms_datatable_and_view_modal_without_maps(): void
    {
        $response = $this->actingAs($this->assistant)->get(route('assistant.directory.index'));

        $response->assertStatus(200);
        $response->assertSee('Community & Political Directory', false);
        $response->assertSee('recordsTable', false);
        $response->assertSee('btn-action-view', false);
        $response->assertSee('addRecordModalBackdrop', false);
        $response->assertSee('editRecordModalBackdrop', false);
        $response->assertSee('viewRecordModalBackdrop', false);
        $response->assertSee('deleteModalBackdrop', false);
        $response->assertSee('toastContainer', false);

        $response->assertDontSee('interactive-map-container', false);
        $response->assertDontSee('id="mapStage"', false);
    }

    public function test_assistant_events_module_has_forms_datatable_and_view_modal_without_maps(): void
    {
        $response = $this->actingAs($this->assistant)->get(route('assistant.events.index'));

        $response->assertStatus(200);
        $response->assertSee('Events Management', false);
        $response->assertSee('recordsTable', false);
        $response->assertSee('btn-action-view', false);
        $response->assertSee('addRecordModalBackdrop', false);
        $response->assertSee('editRecordModalBackdrop', false);
        $response->assertSee('viewRecordModalBackdrop', false);
        $response->assertSee('deleteModalBackdrop', false);
        $response->assertSee('toastContainer', false);

        $response->assertDontSee('interactive-map-container', false);
        $response->assertDontSee('id="mapStage"', false);
    }

    public function test_assistant_issues_module_has_forms_datatable_and_view_modal_without_maps(): void
    {
        $response = $this->actingAs($this->assistant)->get(route('assistant.issues.index'));

        $response->assertStatus(200);
        $response->assertSee('Issues & Grievances Management', false);
        $response->assertSee('recordsTable', false);
        $response->assertSee('btn-action-view', false);
        $response->assertSee('addRecordModalBackdrop', false);
        $response->assertSee('editRecordModalBackdrop', false);
        $response->assertSee('viewRecordModalBackdrop', false);
        $response->assertSee('deleteModalBackdrop', false);
        $response->assertSee('toastContainer', false);

        $response->assertDontSee('interactive-map-container', false);
        $response->assertDontSee('id="mapStage"', false);
    }

    public function test_assistant_survey_module_has_forms_datatable_and_view_modal_without_maps(): void
    {
        $response = $this->actingAs($this->assistant)->get(route('assistant.survey.index'));

        $response->assertStatus(200);
        $response->assertSee('Survey & Preference Ratings', false);
        $response->assertSee('recordsTable', false);
        $response->assertSee('btn-action-view', false);
        $response->assertSee('addRecordModalBackdrop', false);
        $response->assertSee('editRecordModalBackdrop', false);
        $response->assertSee('viewRecordModalBackdrop', false);
        $response->assertSee('deleteModalBackdrop', false);
        $response->assertSee('toastContainer', false);

        $response->assertDontSee('interactive-map-container', false);
        $response->assertDontSee('id="mapStage"', false);
    }

    public function test_assistant_electoral_module_has_view_eye_icon_and_view_modal(): void
    {
        $response = $this->actingAs($this->assistant)->get(route('assistant.electoral'));

        $response->assertStatus(200);
        $response->assertSee('btn-action-view', false);
        $response->assertSee('viewRecordModalBackdrop', false);
        $response->assertSee('openViewModal', false);
        $response->assertDontSee('id="mapStage"', false);
    }

    public function test_assistant_modules_data_endpoints_return_json(): void
    {
        $modules = [
            'assistant.assistance.data',
            'assistant.demography.data',
            'assistant.directory.data',
            'assistant.events.data',
            'assistant.issues.data',
            'assistant.survey.data',
        ];

        foreach ($modules as $routeName) {
            $response = $this->actingAs($this->assistant)->get(route($routeName));
            $response->assertStatus(200);
            $response->assertJson(['success' => true]);
        }
    }
}
