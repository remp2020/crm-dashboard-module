<?php

namespace Crm\DashboardModule;

use Crm\ApplicationModule\CrmModule;
use Crm\ApplicationModule\Menu\MenuContainerInterface;
use Crm\ApplicationModule\Menu\MenuItem;

class DashboardModule extends CrmModule
{
    public function registerAdminMenuItems(MenuContainerInterface $menuContainer)
    {
        // submenu items registered before this main menu item is attached should be ignored as it's only this module
        // responsibility to register the main menu item with proper label, icon and position

        $mainMenu = new MenuItem('', '#dashboard', 'fa fa-dashboard', 5);

        $menuItem = new MenuItem('Dashboard', ':Dashboard:Dashboard:', 'fa fa-dashboard', 100);
        $mainMenu->addChild($menuItem);

        $menuContainer->attachMenuItem($mainMenu);
    }
}
