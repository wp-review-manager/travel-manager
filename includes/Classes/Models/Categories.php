<?php
namespace WPTravelManager\Classes\Models;
use WPTravelManager\Classes\ArrayHelper as Arr;

class Categories extends Model
{
    protected $model = 'tm_trip_categories';

    public function getCategories() {
        $per_page = sanitize_text_field(Arr::get($_REQUEST, 'per_page', 0));
        $page = sanitize_text_field(Arr::get($_REQUEST, 'page', 1));
        $search = sanitize_text_field(Arr::get($_REQUEST, 'search', ''));
        $orderby = sanitize_text_field(Arr::get($_REQUEST, 'orderby', 'id'));
        $order = sanitize_text_field(Arr::get($_REQUEST, 'order', 'DESC'));
        $offset = ($page - 1) * $per_page;

        $query = $this->table($this->model)->select('*')->where('trip_category_name', 'LIKE', '%'.$search.'%');
        $total = $query->getCount();
        $response = $query->orderBy($orderby, $order)->limit($per_page)->offset($offset)->get();

        foreach ($response as $key => $value) {
            $response[$key]->images = maybe_unserialize($value->images);
        }

        $data['total'] = $total;
        $data['categories'] = $response;

        return $data;

    }

    public function saveCategories($data) {
        $id = Arr::get($data, 'id', null);
        if ($id) {
            return TMDBModel($this->model)->where('id', $id)->update($data);
        } else {
            return TMDBModel($this->model)->insert($data);
        }
    }

    public static function deleteCategories($categories_id) {
        return TMDBModel('tm_trip_categories')->where('id', $categories_id)->delete();
    }


}