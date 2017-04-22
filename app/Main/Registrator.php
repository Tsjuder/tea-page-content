<?php

namespace TeaPageContent\App\Main;

use TeaPageContent\App\Modules;

class Registrator {
    private $Shortcode = null;
    
    public function __construct(Modules\Shortcode $Shortcode) {
        $this->Shortcode = $Shortcode;
    }

    public function register_widget() {
        register_widget('\\TeaPageContent\\App\\Modules\\Widget');
    }

    public function register_shortcode() {
        add_shortcode('tea_page_content', [$this->Shortcode, 'tea_page_content']);
    }
}