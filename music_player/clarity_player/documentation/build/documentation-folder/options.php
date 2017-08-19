<h2>Options</h2>

<p>You can customize the bahavior of Clarity by passing in options to the constructor</p>

<pre>
    <code>
        var clarity = $('#myPlayer').ttwClarityPlayer(myPlaylist, {
            styleType:'blur',
            layout:'single-album-cover',
            smallScreenLayout:'info'
        });

    </code>
</pre>


<p>The full list of options for Clarity</p>

<table>
    <thead>
    <tr>
        <th width="125">Option</th>
        <th>Description</th>
        <th width="150">Default</th>
        <th width="300">Valid values</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>autoPlay</td>
        <td>Determines whether music should automatically start playing when the player loads. </td>
        <td>false</td>
        <td>Boolean (true, false)</td>
    </tr>

    <tr>
        <td>layout</td>
        <td>This sets the layout for the player.</td>
        <td>'album-covers'</td>
        <td>'album-covers'<br/>'single-album-cover'<br>'album-cover-and-list'<br>'blank'<br></td>
    </tr>
    <tr>
        <td>smallScreenLayout</td>
        <td>The layout to display when the container is less than the size set in smallScreenSize</td>
        <td>'single-album-cover'</td>
       </td>
    </tr>


    <tr>
        <td>autoLoadDependencies</td>
        <td>Determines whether to auto load dependencies</td>
        <td>true</td>
        <td>Boolean (true/false)</td>
    </tr>
    <tr>
        <td>backgroundImageSource</td>
        <td>Determines which attribute in the playlist item the background should be loaded from</td>
        <td>'cover' </td>
        <td>'cover'<br>'image'</td>
    </tr>
    <tr>
        <td>pluginPath</td>
        <td>The path to the plugin files relative to the current document. </td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>smallScreenSize</td>
        <td>The width in pixels that will trigger the smallScreenLayout</td>
        <td>600</td>
        <td>integer</td>
    </tr>


    <tr>
        <td>groupAlbumCoversInMiddle</td>
        <td>Specifically for the album-covers layout, this option will determine whether the album covers should be
        grouped in the middle or use all of the horizonal space</td>
        <td>true</td>
        <td>Boolean(true/false)</td>
    </tr>
    <tr>
        <td>additionalLayoutConfigs</td>
        <td>An array of additional layout and background configurations</td>
        <td>false</td>
        <td>An array of objects. Each object represents on layout configuration.
<pre>
    <code>
    [
        {layout:'info', styleType:'blur'},
        {layout:'list', styleType:'solid-blue'}
    ]
    </code>
</pre>
        </td>
    </tr>
    <tr>
        <td>jPlayer</td>
        <td>Allows you to pass in jPlayer options.</td>
        <td>{}</td>
        <td>Any valid jPlayer option</td>
    </tr>



    </tbody>
</table>