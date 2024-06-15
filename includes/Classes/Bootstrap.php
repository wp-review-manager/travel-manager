<?php
namespace WPTravelManager\Classes;
use WPTravelManager\Classes\Actions\AjaxActions;

class Bootstrap {
    public function Boot () {
        $this->loadClasses();
        $this->ActivatePlugin();
        $this->registerShortCodes();
        (new AjaxActions())->register();
        (new AdminMenuHandler())->renderMenu();
    }

    public function loadClasses()
    {
        require TM_DIR . 'includes/autoload.php';
    }

    public function registerShortCodes()
    {
        // your shortcode here
    }

    public function ActivatePlugin()
    {
        //activation deactivation hook
        register_activation_hook(__FILE__, function ($newWorkWide) {
            require_once(TM_DIR . 'includes/Classes/Activator.php');
            $activator = new \WPTravelManager\Classes\Activator();
            $activator->migrateDatabases($newWorkWide);
        });
    }
}