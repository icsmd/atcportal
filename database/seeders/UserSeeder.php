<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

/**
 * Class UserTableSeeder.
 */
class UserSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        $user = User::create([
            'name' => 'Prince Al',
            'tel' => '09101111111',
            'email' => 'prince_al@atcportal.org.ph',
            'password' => config('app.env') == 'production' ? '@tcportaladmin' : 'secret',
            'email_verified_at' => now(),
        ]);

        $user->assignRole(config('atc.access.role.admin'));

        if (config('app.env') == 'training') {
            $users = [
                ['name' => 'Mr. Joe Louie Addu', 'email' => 'joelouieaddu@yahoo.com', 'number' => '+639196993481', 'password' => 'coolpaint48'],
                ['name' => 'PCPL Roxel Taguba', 'email' => 'roxeltaguba15@gmail.com', 'number' => '+639273904951', 'password' => 'loudclam52'],
                ['name' => 'Mr. David Macabio', 'email' => 'davemacabio_6@yahoo.com', 'number' => '+639557396545', 'password' => 'sweetsugar67'],
                ['name' => 'OIC-ARD Jose Ferdinando F Toledo', 'email' => 'marquindavid@yahoo.com', 'number' => '+639218947114', 'password' => 'uglylift20'],
                ['name' => 'PLTC Victor Basil Morales', 'email' => 'victorbasilb.morales@rocketmail.com', 'number' => '+639171555904', 'password' => 'jadeflock12'],
                ['name' => 'ARD Roxellen Arzaga', 'email' => 'region4nica@gmail.com', 'number' => '+639175792586', 'password' => 'cutering12'],
                ['name' => 'PMAJ Ma. Kristhyl A Hernandez', 'email' => 'intelcidg4a@gmail.com', 'number' => '+639262508312', 'password' => 'angrylake58'],
                ['name' => 'RD Ariel Perlado', 'email' => 'galahadm357@yahoo.com', 'number' => '+639954870188', 'password' => 'bluewar88'],
                ['name' => 'Sean Ramos ARD CI', 'email' => 'ard.rcid6@gmail.com', 'number' => '+639668625203', 'password' => 'windyedge54'],
                ['name' => 'PMAJ JESS P BAYLON - Chief Intel CIDG RFU 6', 'email' => 'jessbaylon23881@gmail.com', 'number' => '+639173773650', 'password' => 'windyrose96'],
                ['name' => 'Alvin T. Devaras', 'email' => 'atd_0568@yahoo.com', 'number' => '+639173216300', 'password' => 'ivoryfire92'],
                ['name' => 'PMAJ Duane Francis J. Ducducan', 'email' => 'duanefrancis08@gmail.com', 'number' => '+639173081904', 'password' => 'roundreptile58'],
                ['name' => 'D’Fernando Echeverria', 'email' => 'dfernandoecheverria@yahoo.com', 'number' => '+639176348646', 'password' => 'swiftfrog54'],
                ['name' => 'CO Michael Batomalaque', 'email' => 'miconefour@gmail.com', 'number' => '+639157093539', 'password' => 'megapage18'],
                ['name' => 'PCpl Alex Greggor O Avenido', 'email' => '347avenido@gmail.com', 'number' => '+639380210073', 'password' => 'longcart44'],
                ['name' => 'Ms. Charmilyn D Cacdac', 'email' => 'lucasnorth180194@yahoo.com.ph', 'number' => '+639777085993', 'password' => 'hugeapple19'],
                ['name' => 'Irene Lauzon', 'email' => 'It.lauzon66@gmail.com', 'number' => '+639162237427', 'password' => 'heavycave38'],
                ['name' => 'Joe Eric Tuclaud', 'email' => 'jetuclaud@gmail.com', 'number' => '+639776181262', 'password' => 'darkhome83'],
                ['name' => 'PCOL Erosito N Miranda', 'email' => 'caragacidu2006@yahoo.com', 'number' => '+639685760717', 'password' => 'murkyengine91'],
                ['name' => 'Mr Kerry Manalo', 'email' => 'kerrymanalo@gmail.com', 'number' => '+639760227120', 'password' => 'bentwood11'],
                ['name' => 'Sir Boyet', 'email' => 'elvis_giordano2012@yahoo.com.ph', 'number' => '+639175509042', 'password' => 'muddybone14'],
                ['name' => 'Maki Falgui', 'email' => 'makifalgui@gmail.com', 'number' => '+639177260911', 'password' => 'fuzzysound81'],
                ['name' => 'Atty Mae', 'email' => 'maearcillas.afp@gmail.com', 'number' => '+639178779164', 'password' => 'cutepaper79'],
                ['name' => 'Atty Weng', 'email' => 'alo@nica.gov.ph', 'number' => '+639154790544', 'password' => 'megaclam18'],

            ];

            foreach ($users as $user) {
                User::create([
                    'name' => $user['name'],
                    'tel' => $user['number'],
                    'email' => $user['email'],
                    'password' => $user['password'],
                    'email_verified_at' => now(),
                ]);
            }
        }

        if (config('app.env') == 'local') {
            $user = User::create([
                'name' => 'Super Applicant',
                'tel' => '09101111111',
                'email' => 'applicant@applicant.com',
                'password' => 'secret',
                'email_verified_at' => now(),
            ]);

            $user->givePermissionTo(config('atc.access.permission.applicant'));

            $user = User::create([
                'name' => 'No Permission',
                'tel' => '09101111111',
                'email' => 'user@user.com',
                'password' => 'secret',
                'email_verified_at' => now(),
            ]);

            $user = User::create([
                'name' => 'Super Secretariat',
                'tel' => '09101111111',
                'email' => 'secretariat@secretariat.com',
                'password' => 'secret',
                'email_verified_at' => now(),
            ]);

            $user->givePermissionTo(config('atc.access.permission.atc_secretariat'));

            $user = User::create([
                'name' => 'Super DOJ',
                'tel' => '09101111111',
                'email' => 'doj@doj.com',
                'password' => 'secret',
                'email_verified_at' => now(),
            ]);

            $user->givePermissionTo(config('atc.access.permission.doj'));

            $user = User::create([
                'name' => 'Super DILG',
                'tel' => '09101111111',
                'email' => 'dilg@dilg.com',
                'password' => 'secret',
                'email_verified_at' => now(),
            ]);

            $user->givePermissionTo(config('atc.access.permission.dilg'));
        }

        // $user = User::create([
        //     'name' => 'Super DND',
        //     'tel' => '09101111111',
        //     'email' => 'dnd@dnd.com',
        //     'password' => 'secret',
        //     'email_verified_at' => now(),
        // ]);

        // $user->givePermissionTo(config('atc.access.permission.dnd'));

        // $user = User::create([
        //     'name' => 'Super ES',
        //     'tel' => '09101111111',
        //     'email' => 'es@es.com',
        //     'password' => 'secret',
        //     'email_verified_at' => now(),
        // ]);

        // $user->givePermissionTo(config('atc.access.permission.es'));

        // $user = User::create([
        //     'name' => 'Super SND',
        //     'tel' => '09101111111',
        //     'email' => 'snd@snd.com',
        //     'password' => 'secret',
        //     'email_verified_at' => now(),
        // ]);

        // $user->givePermissionTo(config('atc.access.permission.snd'));

        // $user = User::create([
        //     'name' => 'Super NSA',
        //     'tel' => '09101111111',
        //     'email' => 'nsa@nsa.com',
        //     'password' => 'secret',
        //     'email_verified_at' => now(),
        // ]);

        // $user->givePermissionTo(config('atc.access.permission.nsa'));

        // $user = User::create([
        //     'name' => 'Super SFA',
        //     'tel' => '09101111111',
        //     'email' => 'sfa@sfa.com',
        //     'password' => 'secret',
        //     'email_verified_at' => now(),
        // ]);

        // $user->givePermissionTo(config('atc.access.permission.sfa'));

        // $user = User::create([
        //     'name' => 'Super SICT',
        //     'tel' => '09101111111',
        //     'email' => 'sict@sict.com',
        //     'password' => 'secret',
        //     'email_verified_at' => now(),
        // ]);

        // $user->givePermissionTo(config('atc.access.permission.sict'));

        // $user = User::create([
        //     'name' => 'Super SOF',
        //     'tel' => '09101111111',
        //     'email' => 'sof@sof.com',
        //     'password' => 'secret',
        //     'email_verified_at' => now(),
        // ]);

        // $user->givePermissionTo(config('atc.access.permission.sof'));

        // $user = User::create([
        //     'name' => 'Super ED',
        //     'tel' => '09101111111',
        //     'email' => 'ed@ed.com',
        //     'password' => 'secret',
        //     'email_verified_at' => now(),
        // ]);

        // $user->givePermissionTo(config('atc.access.permission.ed'));

        // $user = User::create([
        //     'name' => 'Super AMLC',
        //     'tel' => '09101111111',
        //     'email' => 'amlc@amlc.com',
        //     'password' => 'secret',
        //     'email_verified_at' => now(),
        // ]);

        // $user->givePermissionTo(config('atc.access.permission.amlc'));

        $this->enableForeignKeys();
    }
}
