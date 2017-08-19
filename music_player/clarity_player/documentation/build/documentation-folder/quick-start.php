<div id="quick-start">
    <h2>Quick Start</h2>

    <p>
        Make sure you have included the latest version of jQuery in your page as well as the MediaBox javascript and css
        file. After that it’s really simple, just add the following code to your page:
    </p>
<pre>
    <code>
        // myPlaylist should be an array with the songs that should be loaded into the player.
        var player = $('body').ttwClarityPlayer(myPlaylist);
    </code>
</pre>

    <p>To customize Clarity, just pass in an object containing your desired options</p>
<pre>
    <code>
        var player = $('body').ttwClarityPlayer(myPlaylist, {
            styleType:'solid-blue',
            layout:'album-cover-wall'
        });
    </code>
</pre>

    <ul>
        <li>You can view the full list of configuration options here: <a
                href="options.html">Options</a></li>
        <li>You can read more about setting up a playlist here: <a href="playlist.html">Playlist</a></li>
        <li>For more detailed instructions on setting up Clarity see here: <a
                href="detailed-instructions.html">Detailed Instructions</a></li>
    </ul>



</div>