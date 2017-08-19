<?php

$sections = array(
    'api-events',
    'api-methods',
    'api',
    'detailed-installation',
    'faqs',
    'layouts',
    'options',
    'playlist',
    'quick-start'
);

function is_current($compare_against){
    global $section;

    if($compare_against == $section)
        echo "class='active'";
}

foreach($sections as $this_section){
    $section = $this_section;
    ob_start();
    ob_flush();
    include('documentation.php');
    $page = ob_get_clean();

    file_put_contents('../' . $this_section . '.html', $page);
}

file_put_contents('../index.html', file_get_contents('../quick-start.html'));