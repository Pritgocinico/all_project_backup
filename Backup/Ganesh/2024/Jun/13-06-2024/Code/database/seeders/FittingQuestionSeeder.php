<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FittingQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            [
                'fitting_question' => 'લાઈન લેવલ અને ગ્રુવ',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'fitting_question' => 'મિજાગરા સીધા છે કે નહિ ચેક કરવા',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'fitting_question' => 'ગ્લાસ હલે છે કે નહિ ચેક કરવા',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'fitting_question' => 'શટર હાંફે છે કે નહિ ચેક કરવા',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'fitting_question' => 'સ્લાઈડિંગ શટર ફિટ કર્યા પછી ઊંચું થાય છે કે નહિ ચેક કરવું',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'fitting_question' => 'લોડિંગ અનલોડીંગ માં ડેમેજ ચેક કરવું',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'fitting_question' => 'ટોપલીફ્ટ શટર માં ગેસપંપ લાગયા પછી ભટકાય છે કે નહિ ચેક કરવું',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'fitting_question' => 'સાઈડ પર કચરો હોય તો ઉપડાવવો',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'fitting_question' => 'વધારા ના સ્ક્રૂ સાઈડ પર હોય તો મને અને અજય ને બંને ને ફોન કરવો',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'fitting_question' => 'કટિંગ વખત નું રેપિંગ કાઢ્યું કે નહિ ચેક કરવું',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'fitting_question' => 'ગ્લાસ ની સફાઈ બરાબર છે કે હિ ચેક કરવું',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'fitting_question' => '16*45 હેન્ડલ હલે છે કે નહિ ચેક કરવા',
                'project_id' => 0,
                'chk' => 'on'
            ],
            [
                'fitting_question' => '16*45 શાવર માં અને ડોર માં ગેપ બંધ કરવા માટે રબ્બર ગાસ્કેટ મારી કે નહિ ચેક કરવું',
                'project_id' => 0,
                'chk' => 'on'
            ],
            
        ];

        DB::table('fitting_questions')->insert($questions);
    }
}
