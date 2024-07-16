<?php
namespace WPTravelManager\Classes;
use WPTravelManager\Classes\Routes\AjaxActions;
use WPTravelManager\Classes\Routes\ShortcodeRegister;
use WPTravelManager\Classes\Hooks\Actions;

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
        require TM_DIR . 'includes/autoload.php';
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