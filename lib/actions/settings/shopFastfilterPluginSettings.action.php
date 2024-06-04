<?php

class shopFastfilterPluginSettingsAction extends waViewAction
{
    public function execute()
    {
        $model = new shopFastfilterSettingsModel();

        // Получение категорий
        $categories = $model->query("SELECT id, name FROM shop_category")->fetchAll();
        
        // Получение характеристик
        $features = $model->query("SELECT id, name, code FROM shop_feature")->fetchAll();

        // Получение текущих настроек
        $settings = $model->getAllSettings();

        // Преобразование default_values из JSON в массив
        foreach ($settings as &$setting) {
            $setting['default_values'] = json_decode($setting['default_values'], true);
        }

        $this->view->assign('categories', $categories);
        $this->view->assign('features', $features);
        $this->view->assign('settings', $settings);
        $this->view->assign('pluginUrl', $this->getPluginPath());
    }

    
    public function getPluginPath()
    {
		$plugin = shopFastfilterPlugin::getInstance('settings');
		$plugin_url = $plugin->getPluginStaticUrl(true);
        
        return $plugin_url;
    }
}
