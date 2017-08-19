<h2>Background Styles</h2>
<p>There are several background styles to chose from</p>

<br>
<ul class="background-styles-list">
    <li><a href="#blur">Blurred Album Cover</a></li>
    <li><a href="#image">Image Background</a></li>
    <li><a href="#overlay">Overlay</a></li>
    <li><a href="#blur-player">Blur Player</a></li>
    <li><a href="#overlay-simple">Simple Overlay</a></li>
    <li><a href="#dominant-color">Dominant Color Background</a></li>
    <li><a href="#solid-colors">Blue</a></li>
    <li><a href="#solid-colors">Orange</a></li>
    <li><a href="#solid-colors">Green</a></li>
    <li><a href="#solid-colors">Purple</a></li>
    <li><a href="#solid-colors">Pink</a></li>
    <li><a href="#solid-colors">Blue (Alternate)</a></li>
    <li><a href="#solid-colors">White</a></li>
    <li><a href="#solid-colors">White (Alternate)</a></li>
    <li><a href="#solid-colors">Dark</a></li>


</ul>

<h4 id="blur">Blur</h4>
<p>This sets the background as a blurred version of the album cover for the currently playing song
    <br>

<pre>
    <code>
        var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist, {
            styleType:'blur'
        });
    </code>
</pre>
</p>
<h4 id="blur-player">Blur Player</h4>
<p>This is similar to the blur option, but it only blurs the background of the player
    <br>

<pre>
    <code>
        var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist, {
            styleType:'blur-player'
        });
    </code>
</pre>
</p>
<h4 id="overlay">Overlay</h4>
<p>Allows you to set the background as an image and then place an overlay over that image. The image is set to black and
    white before the overlay is applied. Overlays can be a solid color or they can be a gradient
    <br>

<pre>
    <code>
        var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist, {
            styleType:'overlay'
        });
    </code>
</pre>
</p>
<h4 id="overlay-simple">Overlay Simple</h4>
<p>
    This is similar to the overlay option, but the image is not set to black and white.
    <br>

<pre>
    <code>
        var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist, {
            styleType:'overlay-simple'
        });
    </code>
</pre>
</p>

<h4 id="dominant">Dominant Color</h4>
<p>This extracts the dominant color from the ablum cover (or background property) and sets the player backgroun to this color
    <br>

<pre>
    <code>
        var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist, {
            styleType:'dominant-color'
        });
    </code>
</pre>
</p>
<h4 id="image">Image</h4>
<p>This sets the background to an image and allows you to apply filters to the image. You have full access to all of the filters
    provided by Caman JS
    <br>

<pre>
    <code>
        var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist, {
            styleType:'image'
        });
    </code>
</pre>


<pre>
    <code>
        var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist, {
            styleType:'image'
            //you can set image filters that will be applied dynamically using the imageEffects parameter,
            //you can apply any of the effects provided by CamanJS. The imageEffects paremeter accepts an pipe delimited list
            //http://camanjs.com/
            imageEffects:'sepia[20]|contrast[30]'
        });
    </code>
</pre>


</p>
<h4 id="solid-colors">Solid Colors</h4>
<p>This provides several pre designed styles (i.e. blue, orange, white, etc)
    <br>

<pre>
    <code>
        var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist, {
            //solid-blue, solid-orange, solid-green, solid-purple, solid-pink, solid-blue2, solid-white, solid-white2,
            //solid-dark
            styleType:'solid-blue'
        });
    </code>
</pre>