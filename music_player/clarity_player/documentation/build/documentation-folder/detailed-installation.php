<div id="detailed-installation">

    <h2>Detailed Installation</h2>
    <ol>
        <li>
            <p>There are several assets (js and css files that you need to include in your page before you can use the plugin. These are:</p>
            <ul>
                <li>jQuery</li>
                <li>Yep Nope dependency loader - this is included in the plugin folder</li>
                <li>The Clarity plugin javascript file</li>
                <li>The Clarity css file</li>
            </ul>
             <br>
            <p>You should have the following markup in your page:</p>
<pre>
    <code>
    &lt;link href="path/to/plugin/css/style.css" rel="stylesheet" /&gt;
    &lt;script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"&gt;&lt;/script&gt;
    &lt;script src="path/to/plugin/plugin/yepnope.js"&gt;&lt;/script&gt;
    &lt;script src="path/to/plugin/plugin/ttw-music-player.js"&gt;&lt;/script&gt;
    </code>
</pre>

            <p>Clarity uses several other javascript files, but these will be auto loaded by the plugin.</p>
        </li>

        <li>
            <p>You will need to initialize the Clarity plugin. The basic format for initializing Clarity is:</p>

<pre><code>$('someSelector').ttwClarityPlayerPlayer(yourPlaylist, options);</code></pre>

            <p>Here's an example where I initialize Clarity on a div element with an id = 'music-player'</p>

<pre>
    <code>
    //first let's creat the playlist

    var myPlaylist = [
    {
        mp3:'playlist-folder/1.mp3',
        duration:'1:25',
        cover:'playlist-folder/cover-art/1.jpg',
        title:'Some Song Title',
        artist:'Some Artist'
    },
    {
        mp3:'playlist-folder/2.mp3',
        duration:'3:45',
        cover:'playlist-folder/cover-art/2.jpg',
        title:'Some Other Song Title',
        artist:'Some Other Artist'
    }
    ];

    //now let's initialize the plugin
    $('#music-player').ttwClarityPlayerPlayer(myPlaylist, {
       styleType:'blur'
    });
    </code>
</pre>
        </li>


    </ol>
</div>