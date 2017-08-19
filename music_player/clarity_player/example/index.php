<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title></title>
    <link rel="stylesheet" href="clarity/css/style.css"/>
    <link rel="stylesheet" href="styles.css"/>
</head>

<body>


<div id="example-wrapper">
    <ul id="example-size-picker">
        <li data-size="mobile">Mobile</li>
        <li data-size="large">Large</li>
        <li data-size="full">Full Screen</li>
    </ul>
    <div id="example-outer">
        <div id="example">

        </div>
    </div>
</div>


</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="clarity/ttw-clarity-player.js"></script>
<script src="clarity/yepnope.js"></script>
<script type="text/javascript">
    $(function () {
        var myPlaylist = [
            {
                mp3:'playlist/Capital_Cities-Safe_And_Sound_Carlos_Serrano_Remix.mp3',
                duration:'1:25',
                cover:'playlist/covers/capital-cities.jpg',
                title:'Safe And Sound (Carlos Serrano Remix)',
                artist:'Capital Cities',
                background:'assets/site-mix/bgs/bg4.jpg'
            },
            {
                mp3:'playlist/ODESZA_f_Madelyn_Grant-Sun_Models.mp3',
                duration:'1:25',
                cover:'playlist/covers/odesza.jpg',
                title:'Sun Models',
                artist:'ODESZA f. Madelyn Grant',
                background:'assets/site-mix/bgs/bg5.jpg'
            },
            {
                mp3:'playlist/BANKS-BRAIN_TA-KU_REMIX.mp3',
                duration:'1:25',
                cover:'playlist/covers/banks.jpg',
                title:'Brain (Ta-Ku Remix)',
                artist:'BANKS',
                background:'assets/site-mix/bgs/bg6.jpg'
            },

            {
                mp3:'playlist/ASTR-Part_Of_Me.mp3',
                duration:'1:25',
                cover:'playlist/covers/astr.jpg',
                title:'Part Of Me',
                artist:'ASTR',
                background:'assets/site-mix/bgs/bg7.jpg'
            }
        ];

        var clarity = $('#example').ttwClarityPlayer(myPlaylist);


        $('#example-size-picker').on('click', 'li', function(){
            $('#example').attr('data-size', $(this).data('size'));
            clarity.manageLayout();
        });
    });
</script>
</body>
</html>