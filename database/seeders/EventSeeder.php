<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barangay;
use App\Models\Event;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run database seeds for Events.
     */
    public function run(): void
    {
        $barangays = Barangay::orderBy('id')->get();
        if ($barangays->isEmpty()) {
            $this->call(BarangaySeeder::class);
            $barangays = Barangay::orderBy('id')->get();
        }

        // Realistic event catalog
        $catalog = [
            'Municipal' => [
                [
                    'name' => 'Mariveles Araw ng Parangal & Town Hall Assembly',
                    'venue' => 'Mariveles People\'s Center, Poblacion',
                    'who_invited' => 'Office of the Municipal Mayor & Sangguniang Bayan',
                    'contact_info' => 'Atty. Mark Del Rosario (Chief of Staff) - 0917-555-0101',
                    'details' => 'Annual recognition of outstanding civic leaders, top taxpayers, and barangay performance awardees.',
                    'request' => 'Keynote message and ceremonial presentation of plaques to awardees.',
                    'speech_required' => true,
                    'theme' => 'Sama-Samang Pagsulong Tungo sa Mas Maunlad na Mariveles',
                    'min_exp' => 250, 'max_exp' => 500,
                ],
                [
                    'name' => 'Municipal Job Fair & DOLE TUPAD Orientation',
                    'venue' => 'Municipal Gymnasium, Camaya',
                    'who_invited' => 'PESO Mariveles & DOLE Regional Field Office',
                    'contact_info' => 'Elena Santos (PESO Officer) - 0919-888-2341',
                    'details' => 'Mega job fair hosting over 45 industrial and manufacturing companies from FAB and Freeport zone.',
                    'request' => 'Inspirational message to jobseekers and ceremonial distribution of employment starter kits.',
                    'speech_required' => true,
                    'theme' => 'Trabaho Para sa Bawat Mariveleño: Tugon sa Bagong Kinabukasan',
                    'min_exp' => 300, 'max_exp' => 600,
                ],
                [
                    'name' => 'Mariveles Coastal Clean-up & Mangrove Planting Summit',
                    'venue' => 'Sisiman Bay Coastal Reserve, Sisiman',
                    'who_invited' => 'MENRO Mariveles & Environmental Youth Coalition',
                    'contact_info' => 'Engr. Victor Mendoza (MENRO) - 0920-333-8899',
                    'details' => 'Municipal-wide environmental advocacy and rehabilitation of coastal barangays.',
                    'request' => 'Provide municipal water supply truck, trash receptacles, and deliver opening remarks.',
                    'speech_required' => true,
                    'theme' => 'Kalikasan Nating Mahal, Pangalagaan Para sa Kinabukasan',
                    'min_exp' => 150, 'max_exp' => 350,
                ],
                [
                    'name' => 'Inter-Barangay Liga Sports Festival Opening Ceremony',
                    'venue' => 'Mariveles Sports Complex, Baseco Country',
                    'who_invited' => 'Municipal Youth and Sports Development Council',
                    'contact_info' => 'Coach Ryan Gomez - 0918-444-1122',
                    'details' => 'Grand opening parade and tournament launch featuring 18 barangay basketball and volleyball delegations.',
                    'request' => 'Ceremonial toss, distribution of official game balls, and athlete inspirational oath.',
                    'speech_required' => true,
                    'theme' => 'Kabataang Mariveleño: Malusog, Disiplinado, at Nagkakaisa',
                    'min_exp' => 400, 'max_exp' => 800,
                ],
            ],
            'Barangay' => [
                [
                    'name' => 'Barangay General Assembly & State of the Barangay Address',
                    'venue' => 'Barangay Covered Court',
                    'who_invited' => 'Barangay Council & Sangguniang Barangay',
                    'contact_info' => 'Punong Barangay & Barangay Secretary - 0927-111-4567',
                    'details' => 'Constitutional bi-annual assembly presenting financial audits, accomplished infrastructure, and upcoming programs.',
                    'request' => 'Resource guest speaker message and update on municipal infrastructure projects allocated to the barangay.',
                    'speech_required' => true,
                    'theme' => 'Tapat at Maaasahang Paglilingkod sa Barangay',
                    'min_exp' => 100, 'max_exp' => 250,
                ],
                [
                    'name' => 'Barangay Health & Wellness Medical Outreach Mission',
                    'venue' => 'Barangay Health Center & Daycare Hall',
                    'who_invited' => 'Barangay Health Workers Association & Rural Health Unit',
                    'contact_info' => 'Nurse Joy Villamor - 0939-222-9012',
                    'details' => 'Free medical consultation, pediatric vitamins distribution, ECG screening, and flu vaccination.',
                    'request' => 'Assistance with medicines supply and medical team coordination.',
                    'speech_required' => false,
                    'theme' => 'Malusog na Pamilya, Masiglang Pamayanan',
                    'min_exp' => 120, 'max_exp' => 280,
                ],
                [
                    'name' => 'Barangay Fiesta Cultural Celebration & Civic Parade',
                    'venue' => 'Barangay Plaza & Major Streets',
                    'who_invited' => 'Fiesta Executive Committee & Pastoral Parish Council',
                    'contact_info' => 'Chairman Carlos Pineda - 0917-889-7766',
                    'details' => 'Annual community patron celebration with street dancing, brass bands, and thanksgiving dinner.',
                    'request' => 'Fiesta message, sponsorship for festival trophies and sound system logistics.',
                    'speech_required' => true,
                    'theme' => 'Pasasalamat sa Biyaya at Pagkakaisa ng Bawat Tahanan',
                    'min_exp' => 200, 'max_exp' => 500,
                ],
                [
                    'name' => 'Barangay Disaster Preparedness & Flood Evacuation Drill',
                    'venue' => 'Multi-Purpose Evacuation Center',
                    'who_invited' => 'BDRRMC & Barangay Tanod Brigade',
                    'contact_info' => 'Officer Dante Cruz - 0928-765-4321',
                    'details' => 'Community simulation exercise on tsunami warning, storm surge evacuation, and search-and-rescue drills.',
                    'request' => 'Emergency response kits inspection and brief safety reminder speech.',
                    'speech_required' => true,
                    'theme' => 'Ligtas na Barangay, May Kahandaan sa Kalamidad',
                    'min_exp' => 80, 'max_exp' => 180,
                ],
            ],
            'Sectoral' => [
                [
                    'name' => 'Senior Citizens General Congress & Wellness Forum',
                    'venue' => 'Federation of Senior Citizens Affairs Office Hall',
                    'who_invited' => 'OSCA Mariveles & Federation of Senior Citizens',
                    'contact_info' => 'Nanay Remedios Aquino (OSCA Head) - 0915-333-7722',
                    'details' => 'Quarterly gathering discussing senior pension guidelines, free maintenance medicine program, and wellness activities.',
                    'request' => 'Speaker message for senior welfare and commitment on birthday cash gifts.',
                    'speech_required' => true,
                    'theme' => 'Pagpupugay sa Karunungan at Pamana ng Nakatatanda',
                    'min_exp' => 90, 'max_exp' => 220,
                ],
                [
                    'name' => 'Fisherfolk Cooperative Consultation & Equipment Distribution',
                    'venue' => 'Fishermen\'s Landing Wharf & Multi-purpose Shed',
                    'who_invited' => 'Samahang Magdaragat ng Mariveles & Municipal FARMC',
                    'contact_info' => 'Mang Danilo Flores (FARMC President) - 0945-667-1123',
                    'details' => 'Dialogue on municipal fishing grounds, marine protection sanctuary boundaries, and fuel subsidy vouchers.',
                    'request' => 'Ceremonial turnover of gill nets, life vests, and marine safety GPS trackers.',
                    'speech_required' => true,
                    'theme' => 'Bantay Dagat at Magdaragat: Tulay sa Kasaganaan',
                    'min_exp' => 70, 'max_exp' => 160,
                ],
                [
                    'name' => 'Solo Parents Welfare Forum & Livelihood Skills Seminar',
                    'venue' => 'Community Training Center, Cabcaben',
                    'who_invited' => 'Mariveles Solo Parents Federation & MSWDO',
                    'contact_info' => 'Maria Theresa Ramos - 0916-445-5678',
                    'details' => 'Orientation on RA 11861 benefits, educational scholarship vouchers for dependents, and breadmaking demo.',
                    'request' => 'Inspiring keynote address and distribution of starter livelihood packages.',
                    'speech_required' => true,
                    'theme' => 'Matatag na Magulang, Maaliwalas na Bukas',
                    'min_exp' => 60, 'max_exp' => 140,
                ],
                [
                    'name' => 'TODA (Tricycle Operators & Drivers) Safety & Dialogue Forum',
                    'venue' => 'Central Terminal Clubhouse, Townsite',
                    'who_invited' => 'Federation of Mariveles TODA (FEDTODA)',
                    'contact_info' => 'Kuya Jun Bautista - 0929-112-9988',
                    'details' => 'Traffic management orientation, franchise guidelines, fuel discount cards, and road safety workshop.',
                    'request' => 'Address drivers on upcoming transport terminal renovations.',
                    'speech_required' => true,
                    'theme' => 'Disiplinado sa Kalsada, Ligtas ang Byahero',
                    'min_exp' => 80, 'max_exp' => 200,
                ],
            ],
            'Political' => [
                [
                    'name' => 'Team Acosta Grassroots Leaders Consultative Assembly',
                    'venue' => 'Convention & Events Pavilion, Alas-Asin',
                    'who_invited' => 'Barangay Core Coordinators & District Volunteers',
                    'contact_info' => 'Secretary Benjie Ocampo - 0917-999-4455',
                    'details' => 'Strategic planning and alignment on legislative agenda, municipal priority projects, and constituent feedback.',
                    'request' => 'Comprehensive keynote address laying out the legislative vision and grassroots action plan.',
                    'speech_required' => true,
                    'theme' => 'Serbisyong May Puso, Tapat at Subok na Aksyon',
                    'min_exp' => 150, 'max_exp' => 350,
                ],
                [
                    'name' => 'Barangay Precinct Leaders & Volunteers Fellowship',
                    'venue' => 'Barangay Community Center, Balon-Anito',
                    'who_invited' => 'Sectoral Volunteer Leaders & Community Wardens',
                    'contact_info' => 'Atty. Carlo Morales - 0918-223-3344',
                    'details' => 'Appreciation night and review of community survey findings regarding municipal basic services.',
                    'request' => 'Message of unity, solidarity, and gratitude to ground volunteers.',
                    'speech_required' => true,
                    'theme' => 'Sama-Sama sa Pagbabago at Tuloy-Tuloy na Kaunlaran',
                    'min_exp' => 100, 'max_exp' => 250,
                ],
                [
                    'name' => 'Youth Leaders Political Empowerment Summit',
                    'venue' => 'Pavilion Hall, Malaya',
                    'who_invited' => 'Youth for Good Governance & Student Leaders Council',
                    'contact_info' => 'Patricia Nicole Cruz - 0922-888-4411',
                    'details' => 'Leadership boot camp educating youth on civic duty, transparent public service, and policy advocacy.',
                    'request' => 'Keynote inspirational address on ethical leadership.',
                    'speech_required' => true,
                    'theme' => 'Boses ng Kabataan, Tanglaw ng Pamahalaan',
                    'min_exp' => 80, 'max_exp' => 180,
                ],
            ],
            'Others' => [
                [
                    'name' => 'Parish Pastoral Council 75th Diamond Jubilee Thanksgiving',
                    'venue' => 'San Nicolas de Tolentino Parish Grounds, Poblacion',
                    'who_invited' => 'Rev. Fr. Gerardo Santos & Parish Pastoral Council',
                    'contact_info' => 'Sister Carmela Diaz - 0917-345-6789',
                    'details' => 'Ecumenical mass, anniversary feast, and ceremonial blessing of the newly refurbished church bell tower.',
                    'request' => 'Guest attendance and brief greetings during the luncheon banquet.',
                    'speech_required' => true,
                    'theme' => 'Pananampalataya, Pag-asa, at Pag-ibig sa Sambayanan',
                    'custom' => 'Religious & Cultural',
                    'min_exp' => 150, 'max_exp' => 300,
                ],
                [
                    'name' => 'Mariveles Chamber of Commerce & FAB Investors Roundtable',
                    'venue' => 'FAB Conference & Exhibition Center, Maligaya',
                    'who_invited' => 'FAB Investors Association & Chamber of Commerce',
                    'contact_info' => 'Mr. Anthony Lim (Chamber President) - 0917-777-1234',
                    'details' => 'Executive business roundtable discussing local employment quotas, corporate social responsibility, and power reliability.',
                    'request' => 'Deliver welcome message on behalf of the municipal leadership.',
                    'speech_required' => true,
                    'theme' => 'Fostering Sustainable Growth and Economic Resilience in Mariveles',
                    'custom' => 'Business & Investment',
                    'min_exp' => 50, 'max_exp' => 120,
                ],
                [
                    'name' => 'School District Alumni Grand Homecoming & Gala Night',
                    'venue' => 'Mariveles National High School Gymnasium, Camaya',
                    'who_invited' => 'General Alumni Association Board of Trustees',
                    'contact_info' => 'Dr. Manuel Roxas - 0918-999-0011',
                    'details' => 'Homecoming celebration raising funds for digital smart classrooms and library solarization project.',
                    'request' => 'Honorary alumnus address and ribbon cutting for the alumni marker.',
                    'speech_required' => true,
                    'theme' => 'Balik-Tanaw sa Paaralang Humubog sa Ating Tagumpay',
                    'custom' => 'Educational & Alumni',
                    'min_exp' => 120, 'max_exp' => 300,
                ],
            ]
        ];

        // Seed structured dataset:
        // 1. Specific events for Cabcaben, Poblacion, Camaya, Alas-Asin, Townsite matching user's spec
        // 2. High-profile upcoming events with SPEECH REQUIRED = YES and attendance statuses
        // 3. Past events with expected vs actual attendance comparisons

        $now = Carbon::now();

        // Let's create upcoming events (between tomorrow and 30 days ahead)
        $upcomingEventsData = [
            [
                'barangay_id' => 3, // Cabcaben
                'name' => 'Cabcaben Coastal Community Fisherfolk Consultation & Relief Aid',
                'event_datetime' => $now->copy()->addDays(2)->setHour(9)->setMinute(0),
                'venue' => 'Cabcaben Fishermen\'s Wharf Covered Area',
                'event_type' => 'Sectoral',
                'custom_type' => null,
                'who_invited' => 'Cabcaben Small Fishermen Association (CASFA)',
                'contact_info' => 'Mario Dela Rosa (President) - 0917-334-1122',
                'details' => 'Distribution of fuel vouchers, fishing gear repairs consultation, and dialogue on sustainable fishing zones.',
                'request' => 'Keynote message and ceremonial distribution of subsidized solar boat lanterns.',
                'speech_required' => true,
                'theme' => 'Maunlad na Karagatan, Masaganang Kinabukasan',
                'expected_attendees' => 180,
                'actual_attendees' => null,
                'attendance_status' => 'Confirmed',
            ],
            [
                'barangay_id' => 16, // Poblacion
                'name' => 'Municipal Youth Leadership & Governance Summit 2026',
                'event_datetime' => $now->copy()->addDays(5)->setHour(13)->setMinute(30),
                'venue' => 'Mariveles People\'s Center, Poblacion',
                'event_type' => 'Municipal',
                'custom_type' => null,
                'who_invited' => 'Mariveles Sangguniang Kabataan Federation',
                'contact_info' => 'SK Fed. President Juan Miguel Ramos - 0918-223-9988',
                'details' => 'Annual gathering of over 300 youth leaders, student council presidents, and campus journalists.',
                'request' => 'Keynote speaker on "Leadership with Integrity and Youth Empowerment". Speech preparation required.',
                'speech_required' => true,
                'theme' => 'Kabataang Mariveleño: Huwaran ng Makatao at Tapat na Pamamahala',
                'expected_attendees' => 350,
                'actual_attendees' => null,
                'attendance_status' => 'Confirmed',
            ],
            [
                'barangay_id' => 13, // Camaya
                'name' => 'Camaya Barangay Health Day & Dental Mission',
                'event_datetime' => $now->copy()->addDays(7)->setHour(8)->setMinute(0),
                'venue' => 'Barangay Camaya Multi-Purpose Hall',
                'event_type' => 'Barangay',
                'custom_type' => null,
                'who_invited' => 'Barangay Health Center & Brgy. Capt. Eduardo Tolentino',
                'contact_info' => 'Kagawad Maria Cruz (Health Committee) - 0928-445-6677',
                'details' => 'Free dental extraction, fluoride treatment for kids, and maintenance medicines distribution.',
                'request' => 'Support for additional dental kits and quick greeting message to healthcare workers.',
                'speech_required' => false,
                'theme' => 'Ngiting Malusog para sa Batang Camaya',
                'expected_attendees' => 200,
                'actual_attendees' => null,
                'attendance_status' => 'For Confirmation',
            ],
            [
                'barangay_id' => 12, // Alas-Asin
                'name' => 'Alas-Asin Federation of Senior Citizens Annual Assembly',
                'event_datetime' => $now->copy()->addDays(10)->setHour(10)->setMinute(0),
                'venue' => 'Alas-Asin Community Covered Plaza',
                'event_type' => 'Sectoral',
                'custom_type' => null,
                'who_invited' => 'Alas-Asin Senior Citizens Association',
                'contact_info' => 'Tatay Ramon Pascual - 0919-556-7788',
                'details' => 'Quarterly general assembly, updates on senior citizen social pension program and birthday cash gift distribution.',
                'request' => 'Message to senior citizens regarding health clinic expansion in Alas-Asin.',
                'speech_required' => true,
                'theme' => 'Pagpapahalaga at Paggalang sa Ating mga Nakatatanda',
                'expected_attendees' => 250,
                'actual_attendees' => null,
                'attendance_status' => 'Confirmed',
            ],
            [
                'barangay_id' => 14, // Baseco Country
                'name' => 'Baseco Country Livelihood Cooperative General Meeting',
                'event_datetime' => $now->copy()->addDays(14)->setHour(14)->setMinute(0),
                'venue' => 'Baseco Multipurpose Cooperative Center',
                'event_type' => 'Others',
                'custom_type' => 'Cooperative & Livelihood',
                'who_invited' => 'Baseco Community Producers Cooperative',
                'contact_info' => 'Lourdes Bautista - 0920-778-9900',
                'details' => 'Presentation of cooperative dividend distribution, micro-enterprise awards, and sewing training graduation.',
                'request' => 'Inspection of new sewing facility and brief inspiring message.',
                'speech_required' => true,
                'theme' => 'Sama-samang Pagtutulungan Tungo sa Matatag na Kabuhayan',
                'expected_attendees' => 130,
                'actual_attendees' => null,
                'attendance_status' => 'Tentative',
            ],
            [
                'barangay_id' => 9, // Townsite
                'name' => 'Townsite Grassroots Leadership & Public Safety Alignment',
                'event_datetime' => $now->copy()->addDays(18)->setHour(15)->setMinute(0),
                'venue' => 'Townsite Barangay Hall Session Room',
                'event_type' => 'Political',
                'custom_type' => null,
                'who_invited' => 'Townsite Ward Leaders & Volunteer Marshals',
                'contact_info' => 'Arnold Santos - 0917-665-4433',
                'details' => 'Coordination meeting regarding local security, traffic calming on main highway, and community assistance requests.',
                'request' => 'Address volunteers and distribute safety vests.',
                'speech_required' => true,
                'theme' => 'Payapang Pamayanan, Sandigan ng Kaunlaran',
                'expected_attendees' => 95,
                'actual_attendees' => null,
                'attendance_status' => 'Confirmed',
            ],
            [
                'barangay_id' => 1, // Alion
                'name' => 'Alion Agro-Tourism Harvest Festival Grand Launch',
                'event_datetime' => $now->copy()->addDays(22)->setHour(9)->setMinute(30),
                'venue' => 'Alion Eco-Park Grounds',
                'event_type' => 'Municipal',
                'custom_type' => null,
                'who_invited' => 'Alion Agri-Tourism Council & Mariveles Tourism Office',
                'contact_info' => 'Tourism Officer Carmencita David - 0918-334-7766',
                'details' => 'Exhibition of native fruits, high-value vegetables, honey products, and ecotourism trail opening.',
                'request' => 'Keynote address and ceremonial harvest ribbon cutting.',
                'speech_required' => true,
                'theme' => 'Masaganang Sakahan, Kaakit-akit na Turismo sa Alion',
                'expected_attendees' => 280,
                'actual_attendees' => null,
                'attendance_status' => 'Confirmed',
            ],
        ];

        foreach ($upcomingEventsData as $item) {
            Event::create(array_merge($item, [
                'status' => 'Upcoming',
                'created_by' => null,
            ]));
        }

        // Now let's populate past events across all 18 barangays with realistic data
        // Ensuring Cabcaben has high counts matching user spec examples
        foreach ($barangays as $bgy) {
            // Generate 2 to 5 past events for each barangay
            $numEvents = ($bgy->id == 3 || $bgy->id == 16) ? rand(4, 6) : rand(2, 4);

            for ($i = 0; $i < $numEvents; $i++) {
                $typeKeys = ['Municipal', 'Barangay', 'Barangay', 'Sectoral', 'Sectoral', 'Political', 'Others'];
                $chosenType = $typeKeys[array_rand($typeKeys)];
                $templates = $catalog[$chosenType];
                $template = $templates[array_rand($templates)];

                $daysAgo = rand(5, 180);
                $eventDate = $now->copy()->subDays($daysAgo)->setHour(rand(8, 17))->setMinute(rand(0, 3) * 15);

                $expected = rand($template['min_exp'], $template['max_exp']);
                // Actual attendance can vary by -15% to +25%
                $variation = rand(-15, 25) / 100.0;
                $actual = max(10, (int) round($expected * (1 + $variation)));

                Event::create([
                    'status' => 'Past',
                    'attendance_status' => 'Confirmed',
                    'barangay_id' => $bgy->id,
                    'name' => $template['name'] . ($chosenType === 'Barangay' ? ' - ' . $bgy->name : ''),
                    'event_datetime' => $eventDate,
                    'venue' => $chosenType === 'Barangay' ? "Barangay {$bgy->name} Covered Court" : $template['venue'],
                    'event_type' => $chosenType,
                    'custom_type' => $template['custom'] ?? null,
                    'who_invited' => $template['who_invited'],
                    'contact_info' => $template['contact_info'],
                    'details' => $template['details'],
                    'request' => $template['request'],
                    'speech_required' => $template['speech_required'],
                    'theme' => $template['theme'],
                    'expected_attendees' => $expected,
                    'actual_attendees' => $actual,
                    'created_by' => null,
                ]);
            }
        }
    }
}
