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

function loadView($name, $data = [])
{
    if (file_exists(basePath("App/views/{$name}.view.php"))) {
        extract($data);

        require basePath("App/views/{$name}.view.php");
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

function loadPartial($name, $data = [])
{

    if (file_exists(basePath("App/views/partials/{$name}.php"))) {
        extract($data);
        require basePath("App/views/partials/{$name}.php");
    } else {
        echo "Partial {$name} not found";
    }
}

/**
 * Inspect a value
 * 
 * @para mixed $value
 * @return void
 */

function inspect($value)
{
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
}

/**
 * Inspect a value and die
 * 
 * @para mixed $value
 * @return void
 */

function inspectAndDie($value)
{
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
    die();
}

/**
 * Format a salary
 * 
 * @param int $salary
 * @return string
 */

function formatSalary($salary)
{
    return '$' . number_format(floatval($salary));
}

function sanitize($value)
{
    return filter_var(trim($value), FILTER_SANITIZE_SPECIAL_CHARS);
}

function redirect($url)
{
    header("Location: {$url}");
    exit;
}
