<?php
namespace WPTravelManager\Views\Trips;
use WPTravelManager\Classes\ArrayHelper as Arr;

class Pagination {
    public function render($total_page) {
        ?>
            
        <p data-trm_page_no="prev" class="trm_all_trips_pag trm_pag_prev trm_pag_disabled"><span class="dashicons dashicons-arrow-left-alt2"></span></p>
        <?php for ($i = 1; $i <= $total_page; $i++) : ?>
            <p class="trm_all_trips_pag <?php echo  $i == 1 ? 'trm_pag_active' : '' ?>" data-trm_page_no="<?php echo $i; ?>"><?php echo $i ?></p>
        <?php endfor; ?>
        <p data-trm_page_no="next" class="trm_all_trips_pag trm_pag_next"><span class="dashicons dashicons-arrow-right-alt2"></span></p>
        <?php
    }
}