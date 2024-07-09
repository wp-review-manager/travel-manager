<?php
namespace WPTravelManager\Classes\Models;
use WPTravelManager\Classes\ArrayHelper as Arr;

class PricingCategories extends Model
{
    protected $model = 'tm_pricing_categories';
    
   


    public function savePricingCategories($data) {
        $id = Arr::get($data, 'id', null);
        if ($id) {
            return TMDBModel('tm_pricing_categories')->where('id', $id)->update($data);
        } else {
            return TMDBModel('tm_pricing_categories')->insert($data);
        }
    }
}