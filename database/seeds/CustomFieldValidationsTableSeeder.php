<?php

use App\Models\Leads\CustomFieldValidation;
use Illuminate\Database\Seeder;

class CustomFieldValidationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomFieldValidation::create([
            'name' => 'Required',
            'rule' => 'required',
        ]);

        CustomFieldValidation::create([
            'name' => 'Number',
            'rule' => 'nullable|numeric',
        ]);

        CustomFieldValidation::create([
            'name' => 'Email Address',
            'rule' => 'nullable|email',
        ]);

        CustomFieldValidation::create([
            'name' => 'File',
            'rule' => 'nullable|file',
        ]);

        CustomFieldValidation::create([
            'name' => 'Image',
            'rule' => 'nullable|image',
        ]);

        CustomFieldValidation::create([
            'name' => 'Website Address',
            'rule' => 'nullable|url',
        ]);

        CustomFieldValidation::create([
            'name'        => 'Number Between',
            'rule'        => 'nullable|numeric|between:',
            'has_args'    => true,
            'helper_text' => 'comma (,) seperated - 1,2',
        ]);

        CustomFieldValidation::create([
            'name'        => 'Number Below',
            'rule'        => 'nullable|numeric|max:',
            'has_args'    => true,
            'helper_text' => 'single number - 18',
        ]);

        CustomFieldValidation::create([
            'name'        => 'Number Above',
            'rule'        => 'nullable|numeric|min:',
            'has_args'    => true,
            'helper_text' => 'single number - 18',
        ]);

        CustomFieldValidation::create([
            'name'      => 'Unique',
            'rule'      => 'FileValidationUniqueJson',
            'has_args'  => false,
            'has_class' => true,
        ]);
    }
}
