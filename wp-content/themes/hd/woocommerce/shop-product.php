<?php

\defined( '\WPINC' ) || die;

// shop sidebar
if (is_active_sidebar('w-shop-sidebar')) :
    dynamic_sidebar('w-shop-sidebar');
endif;