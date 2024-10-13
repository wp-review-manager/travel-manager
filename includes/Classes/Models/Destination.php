<?php
namespace WPTravelManager\Classes\Models;
use WPTravelManager\Classes\ArrayHelper as Arr;

class Destination extends Model
{
    protected $model = 'tm_destinations';
    
    public function getDestination($select = '*', $where = []) {
        // Sanitize input values
        $per_page = (int) sanitize_text_field(Arr::get($_REQUEST, 'per_page', 0));
        $page = (int) sanitize_text_field(Arr::get($_REQUEST, 'page', 1));
        $search = sanitize_text_field(Arr::get($_REQUEST, 'search', ''));
        $orderby = sanitize_text_field(Arr::get($_REQUEST, 'orderby', 'id'));
        $order = sanitize_text_field(Arr::get($_REQUEST, 'order', 'DESC'));
        $offset = ($page - 1) * $per_page;
    
        // Build the query with pagination, search, and sorting
        $query = $this->table($this->model)
                      ->select($select)
                      ->where('place_name', 'LIKE', '%' . $search . '%');
                      
        // Get total count for pagination
        $total = $query->getCount();
        
        // Fetch paginated results
        $response = $query->orderBy($orderby, $order)
                          ->limit($per_page)
                          ->offset($offset)
                          ->get();
    
        // Handle images deserialization only if necessary
        if ($select == '*' || (is_array($select) && in_array('images', $select))) {
            foreach ($response as $key => $value) {
                $response[$key]->images = maybe_unserialize($value->images);
            }
        }
    
        // Prepare response data
        return [
            'total' => $total,
            'destinations' => $response
        ];
    }
    

    public static function deleteDestination($destination_id) {
        return TMDBModel('tm_destinations')->where('id', $destination_id)->delete();
    }

    public function saveDestination($data) {
        $id = Arr::get($data, 'id', null);
        if ($id) {
            return TMDBModel('tm_destinations')->where('id', $id)->update($data);
        } else {
            return TMDBModel('tm_destinations')->insert($data);
        }
    }
}