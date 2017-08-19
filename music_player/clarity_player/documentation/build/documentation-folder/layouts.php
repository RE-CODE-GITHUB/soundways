<h2>Layouts</h2>
<p>There are several layouts to chose from</p>

<br>
<ul class="layouts-list">
    <li><a href="#album-covers">Album Cover Slider</a></li>
    <li><a href="#album-cover-and-list">Album Cover & List</a></li>
    <li><a href="#single-album-cover">Single Album Cover</a></li>

    <li><a href="#list">List</a></li>
    <li><a href="#blank">Blank</a></li>

</ul>

<h4 id="album-covers">Album Cover Slider</h4>
<p>Displays a carousel of 3 album covers. The middle cover is the current song, the left cover is the previous song and
    the right cover is the next song
    <br>

<pre>
    <code>
        var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist, {
            layout:'album-covers'
        });
    </code>
</pre>

</p>


<h4 id="single-album-cover">Single Album Cover</h4>
<p>Displays the album cover for the current song
    <br>

<pre>
    <code>
        var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist, {
            layout:'single-album-cover'
        });
    </code>
</pre>
</p>


<h4 id="album-cover-and-list">Album Cover & List</h4>
<p>
    Displays the album cover of the current song alongside a list of all songs in the playlist
    <br>

<pre>
    <code>
        var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist, {
            layout:'album-cover-and-list'
        });
    </code>
</pre>
</p>

<

<h4 id="list">List</h4>
<p>A list of all songs in the playlist
    <br>

<pre>
    <code>
        var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist, {
            layout:'list'
        });
    </code>
</pre>
</p>



<h4 id="blank">Blank</h4>
<p>This is a blank layout
    <br>

<pre>
    <code>
        var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist, {
            layout:'blank'
        });
    </code>
</pre>
</p>



