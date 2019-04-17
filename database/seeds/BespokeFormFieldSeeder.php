<?php

use App\Models\Leads\BespokeFormField;
use Illuminate\Database\Seeder;

class BespokeFormFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $field = BespokeFormField::create([
            'name'  => 'UK Phone Number',
            'class' => 'PhoneNumber',
            'icon'  => 'fa-phone',
            'type'  => 'phone-number',
        ]);

        $field->rules()->sync([1, 10]);

        $field = BespokeFormField::create([
            'name'            => 'Date (dd/mm/yyyy)',
            'class'           => 'Date',
            'icon'            => 'fa-calendar',
            'has_placeholder' => 1,
            'has_default'     => 0,
            'type'            => 'date',
        ]);

        $field->rules()->sync([1, 10]);

        $field = BespokeFormField::create([
            'name'  => 'Text',
            'class' => null,
            'icon'  => 'fa-keyboard-o',
            'type'  => 'text',
        ]);

        $field->rules()->sync([1, 10, 6]);

        $field = BespokeFormField::create([
            'name'  => 'Number',
            'class' => 'Number',
            'icon'  => 'fa-calculator',
            'type'  => 'number',
        ]);

        $field->rules()->sync([1, 10, 2, 7, 8, 9]);

        $field = BespokeFormField::create([
            'name'  => 'Email',
            'class' => null,
            'icon'  => 'fa-envelope-o',
            'type'  => 'email',
        ]);

        $field->rules()->sync([1, 10, 3]);

        $field = BespokeFormField::create([
            'name'  => 'Text Area',
            'class' => null,
            'icon'  => 'fa-keyboard-o',
            'type'  => 'textarea',
        ]);

        $field->rules()->sync([1, 10, 6]);

        $field = BespokeFormField::create([
            'name'  => 'File/Image',
            'class' => 'File',
            'icon'  => 'fa-file',
            'type'  => 'file',
        ]);

        $field->rules()->sync([1, 10, 4, 5]);

        $field = BespokeFormField::create([
            'name'            => 'Select (drop down)',
            'class'           => null,
            'has_options'     => 1,
            'has_placeholder' => 0,
            'has_default'     => 1,
            'icon'            => 'fa-caret-down',
            'type'            => 'select',
        ]);

        $field->rules()->sync([1, 10, 2, 6, 7, 8, 9]);

        $field = BespokeFormField::create([
            'name'            => 'Select Multiple (drop down)',
            'class'           => 'MultipleSelect',
            'has_options'     => 1,
            'has_placeholder' => 0,
            'has_default'     => 1,
            'icon'            => 'fa-caret-down',
            'type'            => 'select-multiple',
        ]);

        $field->rules()->sync([1, 10, 2, 6, 7, 8, 9]);

        $field = BespokeFormField::create([
            'name'            => 'Checkbox',
            'class'           => 'MultipleSelect',
            'has_options'     => 1,
            'has_placeholder' => 0,
            'has_default'     => 0,
            'icon'            => 'fa-check',
            'type'            => 'checkbox',
        ]);

        $field->rules()->sync([1, 10, 2, 6, 7, 8, 9]);

        $field = BespokeFormField::create([
            'name'            => 'Radio Button',
            'class'           => null,
            'has_options'     => 1,
            'has_placeholder' => 0,
            'has_default'     => 1,
            'icon'            => 'fa-circle',
            'type'            => 'radio',
        ]);

        $field->rules()->sync([1, 10, 2, 6, 7, 8, 9]);
    }
}
