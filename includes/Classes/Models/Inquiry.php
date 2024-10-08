<?php
namespace WPTravelManager\Classes\Models;
use WPTravelManager\Classes\ArrayHelper as Arr;

class Inquiry extends Model
{
    protected $model = 'tm_inquiry';
    
    public function getEnquiries() {
        $per_page = sanitize_text_field(Arr::get($_REQUEST, 'per_page', 0));
        $page = sanitize_text_field(Arr::get($_REQUEST, 'page', 1));
        $search = sanitize_text_field(Arr::get($_REQUEST, 'search', ''));
        $orderby = sanitize_text_field(Arr::get($_REQUEST, 'orderby', 'id'));
        $order = sanitize_text_field(Arr::get($_REQUEST, 'order', 'DESC'));
        $offset = ($page - 1) * $per_page;

        $query = $this->table($this->model)->select('*')->where('name', 'LIKE', '%'.$search.'%');
        $total = $query->getCount();
        $response = $query->orderBy($orderby, $order)->limit($per_page)->offset($offset)->get();

        $data['total'] = $total;
        $data['enquiries'] = $response;

        return $data;

    }

    public function saveInquiry($data) {
  
        $id = Arr::get($data, 'id', null);
        
        if ($id) {
            return TMDBModel('tm_inquiry')->where('id', $id)->update($data);
        } else {
            return TMDBModel('tm_inquiry')->insert($data);
        }
    }

    public static function deleteEnquiries($enquiries_id) {
        return TMDBModel('tm_inquiry')->where('id', $enquiries_id)->delete();
    }
}