<html>
<head>
	<title>Magic Mirror</title>
	<style type="text/css">
		<?php include('css/main.css') ?>
        .slot {
            padding: 10px;
        }

        .slot .text {
            font-size: 32px;
        }
        
        iframe {
            border: 0;
            height: 75%;
        }

	</style>
	<link rel="stylesheet" type="text/css" href="css/weather-icons.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.css">
	<script type="text/javascript">
		var gitHash = '<?php echo trim(`git rev-parse HEAD`) ?>';
	</script>
	<meta name="google" value="notranslate" />
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<link rel="icon" href="data:;base64,iVBORw0KGgo=">
</head>

<body>
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.2.1/annyang.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src='https://code.responsivevoice.org/responsivevoice.js'></script>

    <script>
    if (annyang) {
      var commands = {
        'show tps report': function() {
          console.log('Showing tps report');
          var element = $('<img src="tpscover.jpg" id="tpsreport" alt="tpsreport">');
          addElement(element, false);
        },
        'dictate *text': function(stuff) {
          console.log('text! '+stuff);
          responsiveVoice.speak('Dictating '+stuff);
          $('#text').text(stuff);
        },
        'test': function() {
          console.log('test');
          responsiveVoice.speak('Na zdorovje!', 'Russian Female');
          responsiveVoice.speak('Hirven Mulkkuu!', 'Finnish Female');
        },
        'sticky *that': function(that) {
          console.log('sticky '+that);
          responsiveVoice.speak('Will now stick '+that);
          keepElement(that);
        },
        'drop *that': function(that) {
          console.log('drop '+that);
          responsiveVoice.speak('Will now be dropping '+that);
          findAndHide(that);
        },
        'help': function() {
          console.log('Showing help');
          responsiveVoice.speak("Showing help");
          responsiveVoice.speak("Visar hjelp", 'Swedish Female');
          var help = '';
          $.each(commands_str, function(index, value ) {
            help +=  value + "<br/>";
          });
          //$('#text').text("Here is help...\n\n"+help);
          var element = $('<div id="help" class="text">'+help+'</div>');
          addElement(element, false);
        },
        'show weather': function(cmd) {
          console.log('Showing weather');
          responsiveVoice.speak("Showing weather");
		  var element = $('<div id="weather"><div class="temp"></div><div class="windsun small dimmed"></div><div class="forecast small dimmed"></div></div>');
          addElement(element, false);
          weather.init();
        },
        'show datetime': function(cmd) {
          responsiveVoice.speak("Showing datetime");
          var element = $('<div id="datetime"><div class="time" id="time"></div><div class="date small dimmed"></div></div>');
          addElement(element, false);
          time.init();
        },
        'show calendar': function(cmd) {
          responsiveVoice.speak("Showing calendar");
          var element = $('<div id="calendar" class="calendar xxsmall"></div>');
          addElement(element, false);
          calendar.init();
        },
        'show compliment': function(cmd) {
          responsiveVoice.speak("Showing compliment");
          var element = $('<div id="compliment" class="compliment light"></div>');
          addElement(element, false);
          compliments.init();
        },
        'show news': function(cmd) {
          responsiveVoice.speak("Showing news");
          var element = $('<div id="news" class="news medium"></div>');
          addElement(element, false);
          news.init();
        },
        'show commute' : function(cmd) {
          responsiveVoice.speak('Showing commute');
          //var url = "vasttrafik.php";
          //var output = $.getJSON(url, function(giphyresult) {
          //    console.log(giphyresult);
          //});
          var element = $('<div id="commute" class="small dimmed"><iframe border="0" src="vasttrafik.php"></div>');
          addElement(element, false);
        },
       'animate *that': function(that) {
          query = that.replace(/ /g, "+");
          var url = "https://api.giphy.com/v1/gifs/search?q="+query+"&api_key=dc6zaTOxFJmzC";
          var output = $.getJSON(url, function(giphyresult) {
              console.log(giphyresult);
              var giphUrl = giphyresult.data[0].images.fixed_width.url;
              var element = $('<img width="300" id="animation" src="'+giphUrl+'">')
              addElement(element, false);
          });
        },
        'refresh page': function(cmd) {
          responsiveVoice.speak('Refreshing page.');
          setTimeout(function() {
            location.reload();
          }, 1500);
        },
        'take selfie': function(cmd) {
          responsiveVoice.speak('Smile! - Taking selfie.');
          var name = 'test.jpg';
          var element = $('<img src="bad-weather.jpg" id="selfie" alt="selfie">');
          //var element = '<iframe src="localhost://picture.php?name='+name+'">';
          addElement(element, false);
        },
        'email selfie': function(cmd) {
          responsiveVoice.speak('Emailing selfie.');
          // Todo!!
        }
    };

    commands_str = $.map(commands, function(element,index) {return index});

    var elementList = [];
    keepElements = [];
    function addElement(element, keep) {
        var name = element.selector;
        elementList.push(element);
        showElement(element);
        if (keep) {
          keepElements.push(element);
        } else {
          setTimeout(function() {
            var foundElement = false;
            keepElements.forEach(function(lmnt) {
                if (!foundElement) {
                  if (lmnt.selector == element.selector) {
                    console.log("Found element: "+lmnt.selector);
                    //responsiveVoice.speak("Found "+element.selector);
                    foundElement = true;
                    element = lmnt;
                  }
                }
            });
            if (!foundElement) {
              //responsiveVoice.speak("Could not find "+element.selector);
              hideElement(element);
            }
         }, 15000 );
       }
    }

    function showElement(element) {
        // Find first open slot
        var done = false;
        $('body').find("div.slot").each(function(slot, lmnt) {
            if (!done) {
                var slot = $(lmnt);
                if (slot.html() == '') {
                    slot.html(element);
                    slot.show();
                    done = true;
                }
            }
        });
        // TODO: fixa vad som händer när alla slots är fyllda
    }

    function findAndHide(elementName) {
        var done = false;
        console.log('Trying to drop '+elementName);
        $('body div.slot').find("#"+elementName).each(function(index, lmnt){
            hideElement($(lmnt));
        });
    }

    function hideElement(element) {
        element.fadeOut();
        setTimeout(function() {
            element.parent('.slot').empty();
        }, 1000);
    }

    function keepElement(that) {
        console.log('Setting last element');
        var element = elementList[elementList.length - 1];
        console.log('looking for #'+that);
        var foundElement = false;
        elementList.forEach(function(lmnt) {
            if (!foundElement) {
                console.log(lmnt.selector);
                if (lmnt.selector == '#'+that) {
                    console.log("Found element "+lmnt.selector);
                    foundElement = true;
                    element = lmnt;
                }
            }
        });

        keepElements.push(element);
        element.show();
    }

    // Add our commands to annyang
    annyang.addCommands(commands);

    // Start listening. You can call this here, or attach this call to an event, button, etc.
    annyang.start();
}

jQuery(document).ready(function($) {
    
    var element = $('<div id="datetime"><div class="time" id="time"></div><div class="date small dimmed"></div></div>');
    addElement(element, true);
    time.init();

    var element = $('<div id="weather"><div class="temp"></div><div class="windsun small dimmed"></div><div class="forecast small dimmed"></div></div></div>');
    addElement(element, true);
    weather.init();

    var element = $('<div id="calendar" class="calendar xxsmall"></div>');
    addElement(element, true);
    calendar.init();
          
    var element = $('<div id="compliment" class="compliment light"></div>');
    addElement(element, false);
    compliments.init();

    //var element = $('<div id="news" class="news medium"></div>');
    //addElement(element, false);
    //news.init();
});

</script>

<?php $rpID = exec("SERIAL=\"$(cat /proc/cpuinfo | grep Serial | cut -d ':' -f 2)\"; echo \$SERIAL"); ?>
<div id="rpid"><?php echo $rpID; ?></div>

<script>
//setTimeout(function(){
    //window.location.reload(1);
//}, 300000);
</script>

<p id="text" class="text" style="white-space: pre-wrap;"></p>
<div class="slot" id="slot1" style="display: none; float: left; clear: left"></div>
<div class="slot" id="slot2" style="display: none; float: right; clear: right"></div>
<div class="slot" id="slot3" style="display: none; float: left; clear: left"></div>
<div class="slot" id="slot3" style="display: none; float: right; clear: right"></div>
<div class="slot" id="slot5" style="display: none; float: left; clear: left"></div>
<div class="slot" id="slot6" style="display: none; float: right; clear: right"></div>

<script src="js/jquery.js"></script>
<script src="js/jquery.feedToJSON.js"></script>
<script src="js/ical_parser.js"></script>
<script src="js/moment-with-locales.min.js"></script>
<script src="js/config.js"></script>
<script src="js/rrule.js"></script>
<script src="js/version/version.js"></script>
<script src="js/calendar/calendar.js"></script>
<script src="js/compliments/compliments.js"></script>
<script src="js/weather/weather.js"></script>
<script src="js/time/time.js"></script>
<script src="js/news/news.js"></script>
<script src="js/main.js?nocache=<?php echo md5(microtime()) ?>"></script>
<?php  include(dirname(__FILE__).'/controllers/modules.php');?>

</body>
</html>
