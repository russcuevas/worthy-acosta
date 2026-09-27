<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\ElectoralRecord;
use App\Models\ElectionYear;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssistantElectoralTest extends TestCase
{
    use RefreshDatabase;

    protected User $assistant;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::updateOrCreate(
            ['email' => 'admin@worthyacosta.ph'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

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
    }

    public function test_assistant_sees_electoral_forms_and_datatable_without_maps(): void
    {
        $response = $this->actingAs($this->assistant)->get(route('assistant.electoral'));

        $response->assertStatus(200);
        $response->assertSee('Electoral Records Management');
        $response->assertSee('Electoral Data Table');
        $response->assertSee('id="electoralDataTable"', false);
        $response->assertSee('id="btnOpenAddRecordModal"', false);
        $response->assertSee('Encode / Add Election Data');
        $response->assertSee('id="recordModalBackdrop"', false);
        $response->assertSee('id="deleteRecordModalBackdrop"', false);

        // Crucial: Assistant view must NOT have the map stage or map assets
        $response->assertDontSee('id="mapStage"', false);
        $response->assertDontSee('mariveles-map.png');
        $response->assertDontSee('id="mapControls"', false);

        // Verify DataTables pagination and responsiveness
        $response->assertSee('pagingType: \'full_numbers\'', false);
        $response->assertSee('dataTables_paginate', false);
        $response->assertSee('.table-responsive', false);
        $response->assertSee('@media (max-width: 768px)', false);

        // Verify custom positions and other positions fields in Year modals
        $response->assertSee('id="inputAddCustomPosNew"', false);
        $response->assertSee('id="btnAddCustomPosNew"', false);
        $response->assertSee('id="inputAddCustomPosEdit"', false);
        $response->assertSee('id="btnAddCustomPosEdit"', false);
        $response->assertSee('Barangay Captain');
        $response->assertSee('SK Chairman');
    }

    public function test_admin_still_has_map_visualizer_on_admin_electoral(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.electoral'));

        $response->assertStatus(200);
        $response->assertSee('id="mapStage"', false);
        $response->assertSee('mariveles-map.png');
    }

    public function test_assistant_can_fetch_electoral_json_data(): void
    {
        $response = $this->actingAs($this->assistant)->getJson(route('assistant.electoral.data', [
            'year' => '2025',
            'position' => 'Mayor',
        ]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data',
            'metrics' => [
                'total_registered',
                'total_actual',
                'overall_turnout',
                'encoded_count',
            ],
        ]);
    }

    public function test_assistant_can_save_electoral_record(): void
    {
        $payload = [
            'year' => '2025',
            'position' => 'Mayor',
            'barangay_id' => '1',
            'registered_voters' => 5000,
            'actual_votes' => 4200,
            'candidates' => [
                ['name' => 'Candidate Alpha', 'party' => 'Team A', 'votes' => 2500, 'color' => '#1E3A8A'],
                ['name' => 'Candidate Beta', 'party' => 'Team B', 'votes' => 1700, 'color' => '#DC2626'],
            ],
        ];

        $response = $this->actingAs($this->assistant)->postJson(route('assistant.electoral.save'), $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('electoral_records', [
            'year' => '2025',
            'position' => 'Mayor',
            'barangay_id' => '1',
            'registered_voters' => 5000,
            'actual_votes' => 4200,
            'winner_name' => 'Candidate Alpha',
        ]);
    }

    public function test_assistant_can_delete_electoral_record(): void
    {
        $record = ElectoralRecord::create([
            'year' => '2025',
            'position' => 'Mayor',
            'barangay_id' => '2',
            'barangay_name' => 'Alas-asin',
            'registered_voters' => 3000,
            'actual_votes' => 2400,
            'winner_name' => 'Sample Winner',
            'winner_votes' => 1500,
            'turnout_percentage' => 80.00,
            'candidates_data' => [
                ['name' => 'Sample Winner', 'votes' => 1500, 'color' => '#1E3A8A'],
            ],
        ]);

        $response = $this->actingAs($this->assistant)->postJson(route('assistant.electoral.delete', $record->id));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseMissing('electoral_records', [
            'id' => $record->id,
        ]);
    }

    public function test_assistant_can_add_and_delete_election_year(): void
    {
        // Add Year 2031 with custom / other positions
        $addResponse = $this->actingAs($this->assistant)->postJson(route('assistant.electoral.add_year'), [
            'year' => '2031',
            'positions' => ['Mayor', 'Vice Mayor', 'Senator', 'Party-list'],
        ]);

        $addResponse->assertStatus(200);
        $addResponse->assertJson(['success' => true]);
        $this->assertDatabaseHas('election_years', ['year' => '2031']);

        // Check electoral records created for custom positions
        $this->assertDatabaseHas('electoral_records', [
            'year' => '2031',
            'position' => 'Senator',
            'barangay_id' => '1',
        ]);
        $this->assertDatabaseHas('electoral_records', [
            'year' => '2031',
            'position' => 'Party-list',
            'barangay_id' => '1',
        ]);

        // Update Year positions
        $updateResponse = $this->actingAs($this->assistant)->postJson(route('assistant.electoral.update_year'), [
            'year' => '2031',
            'title' => '2031 National & Local Elections',
            'positions' => ['Mayor', 'Vice Mayor', 'Senator', 'Party-list', 'SK Chairman'],
        ]);
        $updateResponse->assertStatus(200);
        $updateResponse->assertJson(['success' => true]);

        // Delete Year 2031
        $deleteResponse = $this->actingAs($this->assistant)->postJson(route('assistant.electoral.delete_year'), [
            'year' => '2031',
        ]);

        $deleteResponse->assertStatus(200);
        $deleteResponse->assertJson(['success' => true]);
        $this->assertDatabaseMissing('election_years', ['year' => '2031']);
    }
}
