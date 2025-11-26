<?php

/**
 * Get the base path
 * 
 * 
 * @param string $path
 * @return string
 */

function basePath($path = '')
{
    return __DIR__ . '/' . $path;
}

/**
 * Load a view
 * 
 * @param string $name
 * @return void
 */

function loadView($name)
{
    if (file_exists(basePath("views/{$name}.view.php"))) {
        require basePath("views/{$name}.view.php");
    } else {
        echo "View {$name} not found";
    }
}


/**
 * Load a partial
 * 
 * @param string $name
 * @return void
 */

function loadPartial($name)
{

    if (file_exists(basePath("views/partials/{$name}.php"))) {
        require basePath("views/partials/{$name}.php");
    } else {
        echo "Partial {$name} not found";
    }
}
