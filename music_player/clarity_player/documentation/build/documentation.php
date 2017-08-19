<?php  error_reporting(E_ALL);
ini_set('display_errors', 'On'); ?>

<?php $is_dev = false; ?>

<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title></title>
    <link rel="stylesheet" href="css/foundation.css"/>
    <link rel="stylesheet" href="css/styles.css"/>
    <link rel="stylesheet" href="css/github.css"/>


</head>




<?php $page = 'documentation'; ?>
<?php $additional_classes = 'wide-page'; ?>
<body data-page="<?php echo $page; ?>">
<?php



$url = "documentation-folder/$section.php";

?>

<div class="sub-header">
    <h1>Documentation</h1>
</div>

<div id="content">


    <div class="row">
        <div class="small-3 columns sidebar">
            <aside >
                <ul>
                    <li><h3>Installation & Usage</h3></li>
                    <li><a <?php is_current('quick-start'); ?> href="quick-start.html">Quick Start</a></li>
                    <li><a <?php is_current('detailed-installation'); ?> href="detailed-installation.html">Detailed Installation</a></li>
                    <li><a <?php is_current('playlist'); ?> href="playlist.html">Playlist Setup</a></li>
                    <li><a <?php is_current('options'); ?> href="options.html">Plugin Options</a></li>


                </ul>

                <ul>
                    <li>
                        <h3>Layouts & Styles</h3>
                    </li>
                    <li><a <?php is_current('layouts'); ?> href="layouts.html">Layouts</a></li>
                </ul>

                <ul>
                    <li><h3>API</h3></li>
                    <li><a <?php is_current('api'); ?> href="api.html">Overview</a></li>
                    <li><a <?php is_current('api-methods'); ?> href="api-methods.html">Methods</a></li>
                    <li><a <?php is_current('api-events'); ?> href="api-events.html">Events</a></li>

                </ul>



            </aside>
        </div>
        <div class="small-9 columns">

            <select id="mobile-documentation-nav">
                    <option value="quick-start">Quick Start</option>
                    <option value="detailed-installation">Detailed Installation</option>
                    <option value="playlist">Playlist Setup</option>
                    <option value="options">Plugin Options</option>
                    <option value="background-styles">Background Styles</option>
                    <option value="layouts">Layouts</option>
                    <option value="api">API Overview</option>
                    <option value="api-methods">API Methods</option>
                    <option value="api-events">API Events</option>
            </select>
            <?php

            if(file_exists($url))
                include($url);

            ?>


        </div>
    </div>


</div>
<script type="text/javascript" src="js/highlight.pack.js"></script>
<script type="text/javascript">
    console.log(hljs);
    hljs.initHighlightingOnLoad();
</script>
</body>
</html>
