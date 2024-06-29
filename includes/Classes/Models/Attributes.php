<?php
namespace WPTravelManager\Classes\Models;
use WPTravelManager\Classes\ArrayHelper as Arr;

class Attributes extends Model
{
    protected $model = 'tm_attributes';

    public function saveAttributes($data) {
        $id = Arr::get($data, 'id', null);
        if ($id) {
            return TMDBModel('tm_attributes')->where('id', $id)->update($data);
        } else {
            return TMDBModel('tm_attributes')->insert($data);
        }
    }
}