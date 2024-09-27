<?php

namespace App\Helpers;

use App\Models\Permission;
use App\Models\UserPermission;
use DOMDocument;
use Rmunate\Utilities\SpellNumber;

class NumberFormatHelper
{
    public static function convertToWords($amount)
    {
        $textWord =  SpellNumber::value($amount)
            ->locale('en')
            ->currency('Dollars')
            ->fraction('cents')
            ->toMoney();

        // Capitalize all words
        $capitalizedString = ucwords($textWord);

        // Define words to exclude
        $excludedWords = ['And'];

        // Convert excluded words back to lowercase
        foreach ($excludedWords as $word) {
            $capitalizedString = str_replace(ucwords($word), strtolower($word), $capitalizedString);
        }

        return $capitalizedString;
    }
    public static function stateListDropdown()
    {
        $data =  '<option value="AL">Alabama</option>
	<option value="AK">Alaska</option>
	<option value="AZ">Arizona</option>
	<option value="AR">Arkansas</option>
	<option value="CA">California</option>
	<option value="CO">Colorado</option>
	<option value="CT">Connecticut</option>
	<option value="DE">Delaware</option>
	<option value="DC">District Of Columbia</option>
	<option value="FL">Florida</option>
	<option value="GA">Georgia</option>
	<option value="HI">Hawaii</option>
	<option value="ID">Idaho</option>
	<option value="IL">Illinois</option>
	<option value="IN">Indiana</option>
	<option value="IA">Iowa</option>
	<option value="KS">Kansas</option>
	<option value="KY">Kentucky</option>
	<option value="LA">Louisiana</option>
	<option value="ME">Maine</option>
	<option value="MD">Maryland</option>
	<option value="MA">Massachusetts</option>
	<option value="MI">Michigan</option>
	<option value="MN">Minnesota</option>
	<option value="MS">Mississippi</option>
	<option value="MO">Missouri</option>
	<option value="MT">Montana</option>
	<option value="NE">Nebraska</option>
	<option value="NV">Nevada</option>
	<option value="NH">New Hampshire</option>
	<option value="NJ">New Jersey</option>
	<option value="NM">New Mexico</option>
	<option value="NY">New York</option>
	<option value="NC">North Carolina</option>
	<option value="ND">North Dakota</option>
	<option value="OH">Ohio</option>
	<option value="OK">Oklahoma</option>
	<option value="OR">Oregon</option>
	<option value="PA">Pennsylvania</option>
	<option value="RI">Rhode Island</option>
	<option value="SC">South Carolina</option>
	<option value="SD">South Dakota</option>
	<option value="TN">Tennessee</option>
	<option value="TX">Texas</option>
	<option value="UT">Utah</option>
	<option value="VT">Vermont</option>
	<option value="VA">Virginia</option>
	<option value="WA">Washington</option>
	<option value="WV">West Virginia</option>
	<option value="WI">Wisconsin</option>
	<option value="WY">Wyoming</option>';
        $dom = new DOMDocument();
        $dom->loadHTML($data);

        $options = $dom->getElementsByTagName('option');
        $countries = [];

        foreach ($options as $option) {
            $value = $option->getAttribute('value');
            $name = $option->nodeValue;
            $countries[] = ['code' => $value, 'name' => $name];
        }
        return $countries;
    }
}
