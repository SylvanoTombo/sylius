<?php


namespace App;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

class ExportConfigurationAdminListener
{
    public function addAdminMenu(MenuBuilderEvent $event)
    {
        $menu = $event->getMenu();
        $configurationMenu = $menu->getChild('configuration');

        $configurationMenu->addChild('export', [
            'route' => 'app_admin_export_configuration_index'
        ])
        ->setLabel('Export')
        ->setLabelAttribute('icon', 'save')
        ;
    }
}
