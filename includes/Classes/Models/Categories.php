<?php
namespace WPTravelManager\Classes\Models;
use WPTravelManager\Classes\ArrayHelper as Arr;

class Categories extends Model
{
    protected $model = 'tm_categories';


    public function saveCategories($data) {
        $id = Arr::get($data, 'id', null);
        if ($id) {
            return TMDBModel('tm_categories')->where('id', $id)->update($data);
        } else {
            return TMDBModel('tm_categories')->insert($data);
        }
    }
}