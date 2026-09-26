<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Issue;
use App\Models\Barangay;

class IssueSeeder extends Seeder
{
    /**
     * Run the database seeds for Mariveles Issues Module.
     */
    public function run(): void
    {
        $allBarangayIds = Barangay::pluck('id')->toArray();

        $sampleIssues = [
            [
                'title' => 'Damaged Coastal Access Road Rehabilitation',
                'issue_type' => 'Infrastructure',
                'is_municipal_wide' => false,
                'barangay_ids' => [3, 4], // Cabcaben, Lucanin
                'who_affected' => 'Residents and motorists traversing coastal access roads',
                'details' => 'Continuous wear from heavy transport and storm surges has degraded pavement along the Lucanin-Cabcaben route. Road requires resurfacing and gutter reinforcement.',
                'status' => 'Ongoing',
                'priority' => 'High',
                'date_reported' => '2026-08-14',
                'action_taken' => 'Engineering survey completed; awaiting DPWH and municipal engineering schedule.',
            ],
            [
                'title' => 'Intermittent Water Supply Line Pressure',
                'issue_type' => 'Community',
                'is_municipal_wide' => false,
                'barangay_ids' => [5], // Balon-Anito
                'who_affected' => '350 households in upland puroks of Balon-Anito',
                'details' => 'Residents experience dry taps during peak morning and early evening hours. Booster pump installation and distribution pipe upgrades requested.',
                'status' => 'For Action',
                'priority' => 'Urgent',
                'date_reported' => '2026-09-02',
                'action_taken' => 'Coordination meeting with local water district set for next week.',
            ],
            [
                'title' => 'New Tricycle Fare Tariffs & Route Zoning Regulation',
                'issue_type' => 'Policy',
                'is_municipal_wide' => false,
                'barangay_ids' => [13, 16], // Camaya, Poblacion
                'who_affected' => 'Local commuters, students, and TODA drivers',
                'details' => 'Disagreements on route boundaries between Poblacion and Camaya TODA operators following recent fuel adjustments. Passengers report arbitrary overcharging.',
                'status' => 'Ongoing',
                'priority' => 'Medium',
                'date_reported' => '2026-08-28',
                'action_taken' => 'Public hearing conducted by Municipal Tricycle Franchising Board.',
            ],
            [
                'title' => 'Municipal Comprehensive Flood Mitigation & Drainage Masterplan',
                'issue_type' => 'Municipal-Wide',
                'is_municipal_wide' => true,
                'barangay_ids' => $allBarangayIds,
                'who_affected' => 'All residents and low-lying coastal and riverbank communities across Mariveles',
                'details' => 'Regular flash floods during monsoon season affect multiple arterial roads and estuaries. Requires multi-barangay retention basins and desilting operations.',
                'status' => 'Ongoing',
                'priority' => 'High',
                'date_reported' => '2026-07-10',
                'action_taken' => 'Feasibility masterplan drafted in partnership with Provincial Government of Bataan.',
            ],
            [
                'title' => 'Barangay Boundary Demarcation Alignment Dispute',
                'issue_type' => 'Political',
                'is_municipal_wide' => false,
                'barangay_ids' => [11, 12], // Mt. View, Alas-Asin
                'who_affected' => 'Property owners and tax mapping officers in disputed boundary sitios',
                'details' => 'Confusion over real property tax collection jurisdictions and voter clustering along the ridge boundary between Mt. View and Alas-Asin.',
                'status' => 'For Action',
                'priority' => 'High',
                'date_reported' => '2026-08-05',
                'action_taken' => 'Referred to Sangguniang Bayan Committee on Barangay Affairs and Cadastral Survey Office.',
            ],
            [
                'title' => 'Drainage Clogging along Roman Superhighway Junction',
                'issue_type' => 'Infrastructure',
                'is_municipal_wide' => false,
                'barangay_ids' => [1, 2], // Alion, Batangas II
                'who_affected' => 'Industrial commuters, freight trucks, and commercial establishments',
                'details' => 'Accumulated silt and debris from roadworks caused standing rainwater, slowing down vehicles entering the municipal corridor.',
                'status' => 'Resolved',
                'priority' => 'Low',
                'date_reported' => '2026-06-18',
                'action_taken' => 'DPWH and municipal maintenance team cleared culverts and flushed out canal lines.',
            ],
            [
                'title' => 'Emergency Marine Rescue & Disaster Response Equipment Upgrade',
                'issue_type' => 'Municipal-Wide',
                'is_municipal_wide' => true,
                'barangay_ids' => $allBarangayIds,
                'who_affected' => 'Coastal fisherfolk, tourism operators, and MDRRMO rescue personnel',
                'details' => 'Need for upgraded rubber boats with outboard motors, life vests, and satellite communication units for storm response along Manila Bay and Mariveles Bay.',
                'status' => 'Resolved',
                'priority' => 'Medium',
                'date_reported' => '2026-05-12',
                'action_taken' => 'Procurement finalized and rescue units distributed to MDRRMO coastal stations.',
            ],
            [
                'title' => 'Solid Waste Segregation & Garbage Collection Schedule Compliance',
                'issue_type' => 'Community',
                'is_municipal_wide' => false,
                'barangay_ids' => [6, 8], // Maligaya, Malaya
                'who_affected' => '600 residential households and commercial shops',
                'details' => 'Irregular garbage truck pickup schedules have led to roadside garbage bag piles attracting stray animals. Request for dedicated eco-wardens.',
                'status' => 'Ongoing',
                'priority' => 'Medium',
                'date_reported' => '2026-09-10',
                'action_taken' => 'MENRO issued revised collection timetable; barangay tanods assigned for monitoring.',
            ],
            [
                'title' => 'Public Market Vendor Stall Allocation and Hygiene Standard',
                'issue_type' => 'Policy',
                'is_municipal_wide' => false,
                'barangay_ids' => [16], // Poblacion
                'who_affected' => 'Wet market stall holders, meat vendors, and consumers',
                'details' => 'Informal vendors occupying fire exits and walkways around the public market. Complaints regarding drainage sanitation and wet section odors.',
                'status' => 'For Action',
                'priority' => 'Urgent',
                'date_reported' => '2026-09-15',
                'action_taken' => 'Market administrator inspection held; formal dialogue scheduled with Vendor Association.',
            ],
            [
                'title' => 'Coastal Seawall Reinforcement & Storm Surge Buffer',
                'issue_type' => 'Infrastructure',
                'is_municipal_wide' => false,
                'barangay_ids' => [14, 17], // Baseco Country, Sisiman
                'who_affected' => 'Over 800 coastal residents and fishing boat docking bays',
                'details' => 'Severe waves during monsoon weather eroded coastal stone revetments near Sisiman bayview and Baseco perimeter. Immediate boulder pitching needed.',
                'status' => 'For Action',
                'priority' => 'Urgent',
                'date_reported' => '2026-09-08',
                'action_taken' => 'Budget allocation requested under Municipal Calamity and Mitigation Fund.',
            ],
            [
                'title' => 'Purok Streetlighting Maintenance & Dark Spot Illumination',
                'issue_type' => 'Community',
                'is_municipal_wide' => false,
                'barangay_ids' => [7], // Biaan
                'who_affected' => 'Biaan evening pedestrians, shift workers, and school students',
                'details' => 'Over 15 defective sodium streetlights reported along the secondary access road leading to remote Purok 3 and Purok 4.',
                'status' => 'New',
                'priority' => 'Low',
                'date_reported' => '2026-09-20',
                'action_taken' => 'Barangay captain logged request for solar street light replacement fixtures.',
            ],
            [
                'title' => 'Sangguniang Kabataan Council Resource Allocation Dispute',
                'issue_type' => 'Political',
                'is_municipal_wide' => false,
                'barangay_ids' => [10], // San Isidro
                'who_affected' => 'Youth sector and barangay sports federation members',
                'details' => 'Dispute regarding the allocation of annual youth development fund between sports tournament events and educational scholarship support.',
                'status' => 'Resolved',
                'priority' => 'Low',
                'date_reported' => '2026-07-22',
                'action_taken' => 'DILG Municipal Local Government Operations Officer facilitated mediation meeting; budget reconciled.',
            ],
            [
                'title' => 'Slaughterhouse Waste Disposal & Wastewater Compliance Guidelines',
                'issue_type' => 'Policy',
                'is_municipal_wide' => false,
                'barangay_ids' => [15, 16], // San Carlos, Poblacion
                'who_affected' => 'Adjacent residential communities and municipal slaughterhouse concessionaire',
                'details' => 'Notice from Environmental Management Bureau concerning effluent discharge parameters. Need to inspect septic lagoons and grease traps.',
                'status' => 'Ongoing',
                'priority' => 'Medium',
                'date_reported' => '2026-08-30',
                'action_taken' => 'Bio-filter installation in progress by contracted municipal environmental firm.',
            ],
            [
                'title' => 'Port Arterial Heavy Haul Road Wear & Noise Disturbance',
                'issue_type' => 'Infrastructure',
                'is_municipal_wide' => false,
                'barangay_ids' => [14, 18], // Baseco Country, Ipag
                'who_affected' => 'Trucking logistics operators and residential homeowners along port access corridor',
                'details' => 'Heavy container trucks operating 24/7 causing asphalt rutting, potholes, and noise pollution during late night hours. Speed limit enforcement requested.',
                'status' => 'Ongoing',
                'priority' => 'High',
                'date_reported' => '2026-08-19',
                'action_taken' => 'Traffic management office deployed rumble strips and scheduled road re-blocking.',
            ],
            [
                'title' => 'Potable Water Pipeline Extension to Upland Purok 6',
                'issue_type' => 'Infrastructure',
                'is_municipal_wide' => false,
                'barangay_ids' => [9], // Townsite
                'who_affected' => '120 households currently relying on deep well water delivery',
                'details' => 'Lack of piped water connections in newly settled residential area of Townsite. Extension of main water pipe distribution by 800 meters required.',
                'status' => 'New',
                'priority' => 'High',
                'date_reported' => '2026-09-18',
                'action_taken' => 'Initial petition submitted by homeowners association to Mayor and Water District.',
            ],
            [
                'title' => 'Municipal Anti-Noise Curfew and Public Videoke Regulations',
                'issue_type' => 'Policy',
                'is_municipal_wide' => true,
                'barangay_ids' => $allBarangayIds,
                'who_affected' => 'All residential neighborhoods, evening workers, and business establishments',
                'details' => 'Municipal ordinance imposing 10:00 PM cutoff for outdoor videoke and loud acoustic systems in residential areas. Enforcement guidance provided to barangay tanods.',
                'status' => 'Resolved',
                'priority' => 'Low',
                'date_reported' => '2026-06-05',
                'action_taken' => 'Ordinance implementing rules printed and disseminated to all 18 barangay halls.',
            ],
            [
                'title' => 'Senior Citizen & PWD Medical Assistance Priority Lane Monitoring',
                'issue_type' => 'Community',
                'is_municipal_wide' => true,
                'barangay_ids' => $allBarangayIds,
                'who_affected' => 'Senior citizens, persons with disabilities, and their families',
                'details' => 'Monitoring compliance of public health centers and local pharmacies with the mandatory express lane and 20% discount on essential medicines.',
                'status' => 'Ongoing',
                'priority' => 'Medium',
                'date_reported' => '2026-08-11',
                'action_taken' => 'OSCA and MSWDO conducting surprise spot audits across commercial pharmacies.',
            ],
            [
                'title' => 'Agricultural Irrigation Siltation and Canal Maintenance',
                'issue_type' => 'Infrastructure',
                'is_municipal_wide' => false,
                'barangay_ids' => [1], // Alion
                'who_affected' => 'Fruit orchard owners, crop farmers, and agricultural workers',
                'details' => 'Silting in secondary irrigation canals has hindered water distribution to mango and vegetable farms during dry spells.',
                'status' => 'For Action',
                'priority' => 'Medium',
                'date_reported' => '2026-09-12',
                'action_taken' => 'National Irrigation Administration local chapter scheduled backhoe clearing for next month.',
            ],
        ];

        foreach ($sampleIssues as $data) {
            $issue = Issue::updateOrCreate(
                [
                    'title' => $data['title'],
                ],
                [
                    'issue_type' => $data['issue_type'],
                    'is_municipal_wide' => $data['is_municipal_wide'],
                    'who_affected' => $data['who_affected'],
                    'details' => $data['details'],
                    'status' => $data['status'],
                    'priority' => $data['priority'],
                    'date_reported' => $data['date_reported'],
                    'action_taken' => $data['action_taken'],
                ]
            );

            // Sync barangays
            $issue->barangays()->sync($data['barangay_ids']);
        }
    }
}
