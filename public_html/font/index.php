<?php header("Location: ".str_pad("", 3*(sizeof(explode('/', $_SERVER['REQUEST_URI']))-2), '../', STR_PAD_LEFT)); die(); ?>