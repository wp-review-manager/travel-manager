<?php
namespace WPTravelManager\Classes\Models;
use WPTravelManager\Classes\ArrayHelper as Arr;

class Difficulty extends Model
{
    protected $model = 'tm_difficulty';

    public function getDifficulty() {
        $per_page = sanitize_text_field(Arr::get($_REQUEST, 'per_page', 0));
        $page = sanitize_text_field(Arr::get($_REQUEST, 'page', 1));
        $search = sanitize_text_field(Arr::get($_REQUEST, 'search', ''));
        $orderby = sanitize_text_field(Arr::get($_REQUEST, 'orderby', 'id'));
        $order = sanitize_text_field(Arr::get($_REQUEST, 'order', 'DESC'));
        $offset = ($page - 1) * $per_page;

        $query = $this->table('tm_trip_defaulty')->select('*')->where('trip_defaulty_name', 'LIKE', '%'.$search.'%');
        $total = $query->getCount();
        $response = $query->orderBy($orderby, $order)->limit($per_page)->offset($offset)->get();

        foreach ($response as $key => $value) {
            $response[$key]->images = maybe_unserialize($value->images);
        }

        $data['total'] = $total;
        $data['difficulty'] = $response;

        return $data;

    }

    public function saveDifficulty($data) {
        $id = Arr::get($data, 'id', null);
        if ($id) {
            return TMDBModel('tm_trip_defaulty')->where('id', $id)->update($data);
        } else {
            return TMDBModel('tm_trip_defaulty')->insert($data);
        }
    }

    public static function deleteDifficulty($difficulty_id) {
        return TMDBModel('tm_trip_defaulty')->where('id', $difficulty_id)->delete();
    }

}