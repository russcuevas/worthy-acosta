<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Directory;
use App\Models\Barangay;

class DirectorySeeder extends Seeder
{
    /**
     * Run the database seeds for Directory Module.
     */
    public function run(): void
    {
        // First ensure barangays are present
        if (Barangay::count() === 0) {
            $this->call(BarangaySeeder::class);
        }

        Directory::truncate();

        // 1. Cabcaben (Barangay ID: 3) - Specifically seed to match the exact user specification example:
        // Total Contacts: 207
        // Saint: 95, Sinner: 62, Savable: 50
        // Sectoral: 18, Barangay Officials: 12, Neighborhood Association: 9, Coordinators: 15, Leaders: 25, Supporters: 120, Others: 8
        $cabcabenId = 3;

        $typesDistribution = [
            'Sectoral' => 18,
            'Barangay Officials' => 12,
            'Neighborhood Association' => 9,
            'Coordinator' => 15,
            'Leader' => 25,
            'Supporter' => 120,
            'Others' => 8,
        ];

        // 95 Saint, 62 Sinner, 50 Savable across 207 contacts
        $labelsList = array_merge(
            array_fill(0, 95, 'Saint'),
            array_fill(0, 62, 'Sinner'),
            array_fill(0, 50, 'Savable')
        );
        shuffle($labelsList);

        $firstNames = ['Juan', 'Maria', 'Jose', 'Ana', 'Carlos', 'Elena', 'Roberto', 'Grace', 'Antonio', 'Lourdes', 'Danilo', 'Carmela', 'Eduardo', 'Rosalinda', 'Ricardo', 'Teresa', 'Fernando', 'Corazon', 'Rolando', 'Luzviminda', 'Manuel', 'Remedios', 'Ramon', 'Flordeliza', 'Vicente', 'Jocelyn', 'Rogelio', 'Rowena', 'Arturo', 'Maritess', 'Renato', 'Divina', 'Danilo', 'Marilou', 'Nestor', 'Arlene', 'Wilfredo', 'Gloria', 'Noel', 'Imelda'];
        $lastNames = ['Dela Cruz', 'Santos', 'Reyes', 'Bautista', 'Mendoza', 'Garcia', 'Torres', 'Tomas', 'Andres', 'Villanueva', 'Ramos', 'Castro', 'Flores', 'Alvarez', 'Soriano', 'Aquino', 'Navarro', 'Mercado', 'Valenzuela', 'Pascual', 'Espiritu', 'Castillo', 'Rivera', 'Manalo', 'Salazar', 'Cortez', 'Domingo', 'Guerrero', 'Serrano', 'Santiago'];

        $sampleRoles = [
            'Sectoral' => ['Senior Citizen President', 'TODA President', 'Fisherfolk Association Head', 'Women Federation Leader', 'Farmers Council Chair', 'Youth Sector Representative', 'Market Vendors Rep'],
            'Barangay Officials' => ['Punong Barangay', 'Barangay Kagawad', 'SK Chairperson', 'Barangay Secretary', 'Barangay Treasurer', 'Chief Tanod', 'Lupon Member'],
            'Neighborhood Association' => ['HOA President', 'Sitio Leader', 'Purok Chairman', 'Block Coordinator', 'Community Association Head', 'Phase 2 HOA VP'],
            'Coordinator' => ['Barangay Area Coordinator', 'Cluster Coordinator', 'Logistics Coordinator', 'Youth Mobilizer', 'Senior Outreach Coordinator', 'Sitio Focal Person'],
            'Leader' => ['Core Precinct Leader', 'Sitio Lead Organizer', 'Volunteer Captain', 'Grassroots Team Leader', 'Women’s Group Head', 'Purok Mobilizer'],
            'Supporter' => ['Active Member', 'Community Supporter', 'Volunteer', 'Local Advocate', 'Senior Supporter', 'Youth Volunteer', 'Household Lead'],
            'Others' => ['Church Lay Worker', 'Civil Society Rep', 'Local Entrepreneur', 'Advisory Elder', 'Community Liaison', 'NGO Representative'],
        ];

        $notesTemplates = [
            'Regular attendee in barangay assemblies; very responsive to mobile updates.',
            'Point person for community outreach programs and civic drives.',
            'Coordinates logistical planning and updates for local residents.',
            'High credibility within the purok; can assist in municipal announcements.',
            'Active volunteer for neighborhood health and clean-up programs.',
            'Key liaison for sectoral consultations and senior citizens affairs.',
            'Prefers mobile SMS or WhatsApp for coordination.',
            'Long-time community resident and respected neighborhood advocate.',
        ];

        $cabcabenRecords = [];
        $labelIndex = 0;

        foreach ($typesDistribution as $type => $count) {
            $roles = $sampleRoles[$type];
            for ($i = 0; $i < $count; $i++) {
                $fName = $firstNames[array_rand($firstNames)];
                $lName = $lastNames[array_rand($lastNames)];
                $role = $roles[array_rand($roles)];
                $lbl = $labelsList[$labelIndex++];
                $phone = '09' . rand(10, 99) . '-' . rand(100, 999) . '-' . rand(1000, 9999);
                $note = $notesTemplates[array_rand($notesTemplates)];

                $cabcabenRecords[] = [
                    'barangay_id' => $cabcabenId,
                    'contact_type' => $type,
                    'name' => "$fName $lName",
                    'position' => $role,
                    'contact_number' => $phone,
                    'other_info' => $note,
                    'internal_label' => $lbl,
                    'created_at' => now()->subDays(rand(1, 90)),
                    'updated_at' => now()->subDays(rand(0, 30)),
                ];
            }
        }

        Directory::insert($cabcabenRecords);

        // 2. Seed other 17 Barangays with realistic counts (between 15 and 65 records per barangay)
        $allBarangays = Barangay::where('id', '!=', $cabcabenId)->get();
        $types = ['Sectoral', 'Barangay Officials', 'Neighborhood Association', 'Coordinator', 'Leader', 'Supporter', 'Others'];
        $labels = ['Saint', 'Sinner', 'Savable'];

        $bulkRecords = [];

        foreach ($allBarangays as $bgy) {
            // Count per barangay
            $totalForBgy = rand(20, 75);

            for ($k = 0; $k < $totalForBgy; $k++) {
                $type = $types[array_rand($types)];
                $roles = $sampleRoles[$type];
                $role = $roles[array_rand($roles)];
                $lbl = $labels[array_rand($labels)];

                $fName = $firstNames[array_rand($firstNames)];
                $lName = $lastNames[array_rand($lastNames)];
                $phone = '09' . rand(10, 99) . '-' . rand(100, 999) . '-' . rand(1000, 9999);
                $note = $notesTemplates[array_rand($notesTemplates)];

                $bulkRecords[] = [
                    'barangay_id' => $bgy->id,
                    'contact_type' => $type,
                    'name' => "$fName $lName",
                    'position' => $role,
                    'contact_number' => $phone,
                    'other_info' => $note,
                    'internal_label' => $lbl,
                    'created_at' => now()->subDays(rand(1, 90)),
                    'updated_at' => now()->subDays(rand(0, 30)),
                ];

                if (count($bulkRecords) >= 500) {
                    Directory::insert($bulkRecords);
                    $bulkRecords = [];
                }
            }
        }

        if (count($bulkRecords) > 0) {
            Directory::insert($bulkRecords);
        }
    }
}
