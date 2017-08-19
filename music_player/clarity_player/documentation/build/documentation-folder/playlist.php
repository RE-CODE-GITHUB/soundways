<div id="playlist">
    <h2>Playlist Setup</h2>
    <h4>Overview</h4>

    <p>
        The Clarity playlist is specified as an array of objects. Each object in the array represents one audio track.
        The properties on the object dictate how the plugin will process the media item. For example, the following
        represents one audio track in a MediaBox playlist

    </p>


<pre><code>
    {mp3:'http://www.somewebsite.com/audio/somemedia.mp3'}
</code></pre>


    <p>
        Here's a simple playlist array with three tracks.
    </p>

<pre><code>
    var playlist = [
        {mp3:'http://www.somewebsite.com/audio/1.mp3'},
        {mp3:'http://www.somewebsite.com/audio/2.mp3'},
        {mp3:'http://www.somewebsite.com/audio/3.mp3'}
    ]
</code></pre>


    <p>Clarity is built on <a href="#">jPlayer</a>, which requires one of the following formats to be supplied:

        MP3
        M4A

        Note that, there is little to no benefit in providing both, due to the way support pans out with HTML5 browsers
        supporting either both or neither.

        You can read more about the formats that jPlayer supports here: <a href="#">jPlayer formats</a>

    <p>

    <h4>Setting additional information about each playlist item</h4>

    <p>
        In addition to the mp3/m4a path, there are several other properties that you can include about each playlist
        item.
        These are:
    </p>

    <ol>
        <li>cover - This is the url to an image file for the album cover</li>
        <li>background - If you would like a background image associated with this playlist item, this is the url to that image</li>
        <li>title - The title of the playlist item</li>
        <li>artist - The artist for this playlist item</li>
        <li>duration - the duration in minutes in seconds</li>This is the url to an image file for the album cover</li>
    </ol>

    <p>A sample playlist item with all properties</p>

    <pre><code>
    {
        mp3: 'http://www.somewebsite.com/audio/somemedia.mp3',
        title: 'Test audio file',
        artist: 'Test artist',
        cover: 'http://www.somewebsite.com/audio/somemedia.jpg',
        background: 'http://www.somewebsite.com/audio/somemedia_bg.jpg',
        duration:'3:54'
    }
    </code></pre>

    <p>A sample playlist with three items</p>

    <pre><code>

    var playlist = [
        {
            mp3: 'http://www.somewebsite.com/audio/1.mp3',
            title: 'Test audio file',
            artist: 'Test artist',
            cover: 'http://www.somewebsite.com/audio/1.jpg',
            background: 'http://www.somewebsite.com/audio/1_bg.jpg',
            duration:'3:54'
        },
        {
            mp3: 'http://www.somewebsite.com/audio/2.mp3',
            title: 'Test audio file 2 ',
            artist: 'Test artist',
            cover: 'http://www.somewebsite.com/audio/2.jpg',
            background: 'http://www.somewebsite.com/audio/2.jpg',
            duration:'2:13'
        },
        {
            mp3: 'http://www.somewebsite.com/audio/3.mp3',
            title: 'Test audio file 3',
            artist: 'Test artist',
            cover: 'http://www.somewebsite.com/audio/3.jpg',
            background: 'http://www.somewebsite.com/audio/3_bg.jpg',
            duration:'6:42'
        }
    ]


    </code></pre>


</div>