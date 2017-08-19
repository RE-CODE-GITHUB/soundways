<h2>API Events</h2>

<br>
<ul class="api-list">
    <li><a href="#playlistInit">playlistInit</a></li>
    <li><a href="#started">started</a></li>
    <li><a href="#initPlaylistAdvance">initPlaylistAdvance</a></li>
    <li><a href="#playlistAdvance">playlistAdvance</a></li>
    <li><a href="#colorsProcessed">colorsProcessed</a></li>
    <li><a href="#settingLayout">settingLayout</a></li>
    <li><a href="#layoutSet">layoutSet</a></li>
    <li><a href="#settingBackground">settingBackground</a></li>
    <li><a href="#backgroundSet">backgroundSet</a></li>
    <li><a href="#layoutSwitching">layoutSwitching</a></li>
    <li><a href="#layoutSwitchComplete">layoutSwitchComplete</a></li>

</ul>

<div class="clear"></div>

<h4 id="playlistInit">playlistInit</h4>
<p>
    This event is fired when the Clarity playlist is initialized
</p>

<pre>
    <code>
    var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist);

    clarity.on('playlistInit', function(){
        //do something in here when the playlist is initialized
    });
    </code>
</pre>




<h4 id="started">started</h4>
<p>
    This event is fired when the plugin has completely started
</p>

<pre>
    <code>
    var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist);

    clarity.on('started', function(){
        //do something when the plugin has completely started
    });
    </code>
</pre>



<h4 id="initPlaylistAdvance">initPlaylistAdvance</h4>
<p>This event fires when song is about to change, but before it has started to play

</p>

<pre>
    <code>
    var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist);

    clarity.on('initPlaylistAdvance', function(e, indexOfNextSong){
        //do something in here when the playlist is about to advance
    });
    </code>
</pre>



<h4 id="playlistAdvance">playlistAdvance</h4>
<p>This event fires when the song is changing

</p>


<pre>
    <code>
    var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist);

    clarity.on('playlistAdvance', function(e, direction){
        //do something in here when the playlist advances.
        //direction has either prev or next in it
    });
    </code>
</pre>



<h4 id="colorsProcessed">colorsProcessed</h4>
<p>The plugin will process the colors for the images associated with each song. This event is fired once the colors have
    been processed
</p>


<pre>
    <code>
    var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist);

    clarity.on('colorsProcessed', function(e, imageColors, lightOrDarkClass){
        //do something in here when the colors for the current cover image are processed.
        //the dominant color is availabe in imageColors.dominant
        //lightOrDarkClass will be light-bg or dark-bg
    });
    </code>
</pre>

<h4 id="settingLayout">settingLayout</h4>
<p>This event fires when the layout has started to be initialized.

</p>


<pre>
    <code>
    var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist);

    clarity.on('settingLayout', function(e, newLayoutName){
        //do something in here when the layout starts switching
        //the options variable holds the options for the new layout
    });
    </code>
</pre>



<h4 id="layoutSet">layoutSet</h4>
<p>This event fires when the layout has been set
</p>


<pre>
    <code>
    var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist);

    clarity.on('layoutSet', function(e){
        //do something in here when the layout starts switching
        //the options variable holds the options for the new layout
    });
    </code>
</pre>


<h4 id="settingBackground">settingBackground</h4>
<p>This event fires when the the background is being generated.

</p>


<pre>
    <code>
        var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist);

        clarity.on('settingBackground', function(e, styleType){
            //do something in here when the background is being generated
            //the styleType variable holds the background style (i.e. blur)
        });
    </code>
</pre>



<h4 id="backgroundSet">backgroundSet</h4>
<p>This event fires when the background has been set
</p>


<pre>
    <code>
        var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist);

        clarity.on('backgroundSet', function(e){
            //do something in here when the background has been Set
        });
    </code>
</pre>



<h4 id="layoutSwitching">layoutSwitching</h4>
<p>This event fires when the layout is being switched to a new layout.

</p>


<pre>
    <code>
    var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist);

    clarity.on('layoutSwitching', function(e, options){
        //do something in here when the layout starts switching
        //the options variable holds the options for the new layout
    });
    </code>
</pre>



<h4 id="layoutSwitchComplete">layoutSwitchComplete</h4>
<p>This event fires when the layout has finished switching

</p>


<pre>
    <code>
    var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist);

    clarity.on('layoutSwitchComplete', function(e){
        //do something in here when the layout has finished switching
    });
    </code>
</pre>