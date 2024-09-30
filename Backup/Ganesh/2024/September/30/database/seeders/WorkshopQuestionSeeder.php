<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkshopQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            [
                'workshop_question' => 'કટિંગ પહેલા નું pvc રેપિંગ',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'workshop_question' => 'કાતરા કાચ ફિટ થયા પહેલા અને કાચ ફિટ થયા પછી ચેક કરવા',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'workshop_question' => 'કાતરા માં કલર કર્યો કે નહિ',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'workshop_question' => 'કાચ હલે છે કે નહિ',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'workshop_question' => 'કટિંગ પહેલા નું pvc રેપિંગ',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'workshop_question' => 'કાતરા ફાઇલિંગ અને કલર ચેક કરવા',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'workshop_question' => '16*45 માં કાય ફિટ થયા પછી સિલિકોન ચેક કરવી',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'workshop_question' => 'પિવોટ કલર સેમ છે કે નહિ ચેક કરવા',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'workshop_question' => 'મિજાગરા સીધા છે કે નહિ ચેક કરવા',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'workshop_question' => 'કાચ માં જતા પહેલા માપ ચેક કરવા',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'workshop_question' => 'ડીસ્પેચ વખતે માલ ડેમેજ ચેક કરવો',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'workshop_question' => '1 mm બોર્ડર માં ગ્લાસ માં ગેપ ચેક કરવી 1 mm',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'workshop_question' => '1 mm બોર્ડર માં ગ્લાસ ની ધાર માં સિલિકોન ચેક કરવી 1 mm',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'workshop_question' => '1 mm બોર્ડર માં ગ્લાસ ની ધાર ચેક કરવી 1 mm',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'workshop_question' => '16*45 માં ટોપ બોટમ ની કલર ચેક કરવો',
                'project_id' => 0,
                'chk' => 'on'
            ],
        ];

        DB::table('workshop_questions')->insert($questions);
    }
}
