<?php

use Illuminate\Database\Seeder;

class OrganizatorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organizator = [
            ["name" => "Աշխատանքի եւ սոցիալական հարցերի նախարարություն"],
            ["name" => "Առողջապահության նախարարություն"],
            ["name" => "Արդարադատության նախարարություն"],
            ["name" => "Արտակարգ իրավիճակների նախարարություն"],
            ["name" => "Արտաքին գործերի նախարարություն"],
            ["name" => "Բարձր տեխնոլոգիական արդյունաբերության նախարարություն"],
            ["name" => "Էկոնոմիկայի նախարարություն"],
            ["name" => "Կրթության, գիտության, մշակույթի և սպորտի նախարարությու"],
            ["name" => "Շրջակա միջավայրի նախարարություն"],
            ["name" => "Պաշտպանության նախարարություն"],
            ["name" => "Տարածքային կառավարման և ենթակառուցվածքների նախարարություն"],
            ["name" => "Ֆինանսների նախարարություն"],
            ["name" => "Երևանի քաղաքապետարան"],
            ["name" => "Պետական եկամուտների կոմիտե"],
            ["name" => "Կառավարություն"],
            ["name" => "Ազգային անվտանգության ծառայություն"],
            ["name" => "Ոստիկանություն"],
            ["name" => "Վարչապետին ենթակա մարմիններ"],
            ["name" => "Ազգային անվտանգության ծառայություն"],
            ["name" => "Ոստիկանություն"],
            ["name" => "Պետական վերահսկողական ծառայություն"],
            ["name" => "Կառավարությանը ենթակա մարմիններ"],
            ["name" => "Առողջապահական և աշխատանքի տեսչական մարմին"],
            ["name" => "Բնապահպանության և ընդերքի տեսչական մարմին"],
            ["name" => "Կադաստրի կոմիտե"],
            ["name" => "Կրթության տեսչական մարմին"],
            ["name" => "Միջուկային անվտանգության կարգավորման կոմիտե"],
            ["name" => "Շուկայի վերահսկողության տեսչական մարմին"],
            ["name" => "Պետական եկամուտների կոմիտե"],
            ["name" => "Սննդամթերքի անվտանգության տեսչական մարմին"],
            ["name" => "Վիճակագրական կոմիտե"],
            ["name" => "Քաղաքաշինության կոմիտե"],
            ["name" => "Քաղաքաշինության, տեխնիկական և հրդեհային անվտանգության տեսչական մարմին"],
            ["name" => "Նախագահի աշխատակազմ"],
            ["name" => "Ազգային ժողով"],
            ["name" => "Դատախազություն"],
        ];
 
        foreach($organizator as $val){
            DB::table('organizator')->insert([
                'name' => $val['name'],
                'is_state' => 1,
            ]);
        }
    }
}
