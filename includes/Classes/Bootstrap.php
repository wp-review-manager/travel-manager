<?php
namespace WPTravelManager\Classes;
use WPTravelManager\Classes\Routes\AjaxActions;
use WPTravelManager\Classes\Routes\ShortcodeRegister;
use WPTravelManager\Classes\Hooks\Actions;
use WPTravelManager\Classes\Modules\CustomPageRegister;

class Bootstrap {
    public function Boot () {
        $this->loadClasses();
        $this->ActivatePlugin();
        (new ShortcodeRegister())->register();
        (new AjaxActions())->register();
        (new AdminMenuHandler())->renderMenu();
        (new Actions());
    }

    public function loadClasses()
    {
        require TRM_DIR . 'includes/autoload.php';
    }

    public function ActivatePlugin()
    {
        //activation deactivation hook
        register_activation_hook(__FILE__, function ($newWorkWide) {
            require_once(TRM_DIR . 'includes/Classes/Activator.php');
            $activator = new \WPTravelManager\Classes\Activator();
            $activator->migrateDatabases($newWorkWide);
        });
        // Some time require to run the migration
        
        require_once(TRM_DIR . 'includes/Classes/Activator.php');
        $activator = new \WPTravelManager\Classes\Activator();
        $activator->migrateDatabases(false);

        add_action('after_setup_theme', function () {
            (new CustomPageRegister())->registerPage();
        });

    }
  

}