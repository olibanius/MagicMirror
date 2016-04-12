<?php $rpID = exec("SERIAL=\"$(cat /proc/cpuinfo | grep Serial | cut -d ':' -f 2)\"; echo \$SERIAL"); ?>
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
				.image{
   				position: relative;
				  top: 0;
				  left: 0;
				}
				.watermark{
   				background:url(img/sent.png) no-repeat;
   				width: 100px;
   				height: 100px;
   				position: relative;
   				top: -100px;
   				left: 0;
					display: none;
				}

				#bg {
				  // so if user scrolls it doesn't matter
				  position: fixed;
				  background-color: black;

				  // expand to height & width
				  height: 100%;
				  width: 100%;
				  margin: 0;
				  padding:0;

				  // hidden initially
				  opacity: 0;
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
		<div id="rpid" style="display: none"><?php echo $rpID; ?></div>
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.2.1/annyang.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src='https://code.responsivevoice.org/responsivevoice.js'></script>

    <script>
    if (annyang) {
      var commands = {
        'dictate *text': function(arg2) {
					executeCommand('dictate', arg2);
				},
        'test': function() {
					executeCommand('test');
				},
        'sticky *that': function(that) {
					executeCommand('sticky', that)
				},
        'drop *that': function(that) {
					executeCommand('drop', that);
		    },
        'help': function() {
        	executeCommand('help');
        },
        'show weather': function(cmd) {
					executeCommand('show weather');
	      },
        'show datetime': function(cmd) {
    			executeCommand('show datetime');
        },
        'show calendar': function(cmd) {
    			executeCommand('show calendar');
        },
        'show compliment': function(cmd) {
      		executeCommand('show compliment');
        },
        'show news': function(cmd) {
					executeCommand('show news');
				},
        'show commute' : function(cmd) {
          executeCommand('show commute');
        },
       'animate *that': function(that) {
    			executeCommand('animate', that);
        },
        'refresh page': function(cmd) {
          executeCommand('refresh page');
        },
        'take selfie': function(cmd) {
					executeCommand('take selfie');
        },
        'send selfie': function(cmd) {
					executeCommand('send selfie');
        }
    };

    commands_str = $.map(commands, function(element,index) {return index});

		function executeCommand(cmd, arg2 = false) {
			resetIdleTime();
			switch (cmd) {
				case 'test':
					console.log('test');
					responsiveVoice.speak('Na zdorovje!', 'Russian Female');
					responsiveVoice.speak('Hirven Mulkkuu!', 'Finnish Female');
				break;
				case 'help':
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
				break;
				case 'show weather':
					console.log('Showing weather');
					responsiveVoice.speak("Showing weather");
			var element = $('<div id="weather"><div class="temp"></div><div class="windsun small dimmed"></div><div class="forecast small dimmed"></div></div>');
					addElement(element, false);
					weather.init();
				break;
				case 'show datetime':
					responsiveVoice.speak("Showing datetime");
					var element = $('<div id="datetime"><div class="time" id="time"></div><div class="date small dimmed"></div></div>');
					addElement(element, false);
					time.init();
				break;
				case 'show calendar':
					responsiveVoice.speak("Showing calendar");
					var element = $('<div id="calendar" class="calendar xxsmall"></div>');
					addElement(element, false);
					calendar.init();
				break;
				case 'show compliment':
					responsiveVoice.speak("Showing compliment");
					var element = $('<div id="compliment" class="compliment light"></div>');
					addElement(element, false);
					compliments.init();
				break;
				case 'show news':
					responsiveVoice.speak("Showing news");
					var element = $('<div id="news" class="news medium"></div>');
					addElement(element, false);
					news.init();
				break;
				case 'show commute':
					responsiveVoice.speak('Showing commute');
					var element = $('<div id="commute" class="small dimmed"><iframe border="0" src="vasttrafik.php"></div>');
					addElement(element, false);
				break;
				case 'sticky':
					console.log('sticky '+arg2);
					responsiveVoice.speak('Will now stick '+arg2);
					keepElement(arg2);
				break;
				case 'drop':
					console.log('drop '+arg2);
					responsiveVoice.speak('Will now be dropping '+arg2);
					findAndHide(arg2);
				break;
				case 'dictate':
					console.log('text! '+arg2);
					responsiveVoice.speak('Dictating '+arg2);
					$('#text').text(arg2);
				break;
				case 'refresh page':
					responsiveVoice.speak('Refreshing page.');
					setTimeout(function() {
						location.reload();
					}, 1500);
				break;
				case 'animate':
					responsiveVoice.speak('Animating '+arg2);
					query = arg2.replace(/ /g, "+");
					var url = "https://api.giphy.com/v1/gifs/search?q="+query+"&api_key=dc6zaTOxFJmzC";
					var output = $.getJSON(url, function(giphyresult) {
							console.log(giphyresult);
							var giphUrl = giphyresult.data[0].images.fixed_width.url;
							var element = $('<img width="300" id="animation" src="'+giphUrl+'">')
							addElement(element, false);
					});
				break;
				case 'take selfie':
					if ($('#rpid').html() != '') {
						var output = $.getJSON("picture.php", function(imgsrc) {
							var element = $('<div class="image"><img src="/'+imgsrc+'" id="selfie" class="selfie" alt="selfie"></div><div class="watermark"></div>');
							//alert('<img src="'+imgsrc+'" id="selfie" class="selfie" alt="selfie">');
							addElement(element, false);
						});
					} else {
						responsiveVoice.speak('Smile! - Taking selfie.');
						var name = 'test.jpg';
						var element = $('<div class="image"><img src="img/selfie-stick-hipster.jpg" id="selfie" class="selfie" alt="selfie"></div><div class="watermark"></div>');
						//var element = '<iframe src="localhost://picture.php?name='+name+'">';
						addElement(element, false);
					}
					break;
					case 'send selfie':
						responsiveVoice.speak('Emailing selfie.');
						if ($('#rpid').html() != '') {
							$('.selfie').each(function( index ) {
								//console.log( index + ": " + $( this ).attr('src') );
								var output = $.getJSON("sendPicture.php?name="+$(this).attr('src'), function(imgsrc) {
									//var element = $('<img src="/'+imgsrc+'" id="selfie" class="selfie" alt="selfie">');
									//alert('<img src="'+imgsrc+'" id="selfie" class="selfie" alt="selfie">');
									//addElement(element, false);
								});
							});
						} else {
							// Don't do shit on dev
							console.log("Pretending to email selfie from dev.");
						}
						$('.watermark').show();
					break;
				}
		}

		var idleTime = 0;
		$(document).ready(function () {
		    //Increment the idle time counter every minute.
		    //var idleInterval = setInterval(timerIncrement, 60000); // 1 minute
				var idleInterval = setInterval(timerIncrement, 1000);
		    //Zero the idle timer on mouse movement.
		    $(this).mousemove(function (e) {
		        idleTime = 0;
		    });
		    $(this).keypress(function (e) {
		        idleTime = 0;
		    });
		});

		function timerIncrement() {
		    idleTime = idleTime + 1;
				console.log(idleTime);
		    if (idleTime >= 10) {
		        console.log('Should start motion detection now.')
						idleTime = 0;
						$('#bg').animate({ opacity: 0 }, 1000);
		    }
		}

		function resetIdleTime() {
			console.log('Resetting idleTime');
			idleTime = 0;
		}

		$(document).keypress(function(e) {
		    if(e.which == 13) {
					var name = 'test.jpg';
					var element = $('<div class="image"><img src="img/bad-weather.jpg" id="selfie" class="selfie" alt="selfie"></div><div class="watermark"></div>');//var element = '<iframe src="localhost://picture.php?name='+name+'">';
					addElement(element, false);
					var name = 'test.jpg';
					var element = $('<div class="image"><img src="img/bad-weather.jpg" id="selfie" class="selfie" alt="selfie"></div><div class="watermark"></div>');
					//var element = '<iframe src="localhost://picture.php?name='+name+'">';
					addElement(element, false);

					$('.selfie').each(function( index ) {
	  					console.log( index + ": " + $( this ).attr('src') );
					});

					}
		});

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
				if (!done) {
					console.log('All slots are busy, sorry.');
				}
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

<script>
//setTimeout(function(){
    //window.location.reload(1);
//}, 300000);
</script>

<div id="bg">
<div>
	<a style="font-size: 12px; clear: both;" href="#" onclick="location.reload()">debug</a>
</div>
<span id="text" class="text" style="white-space: pre-wrap;"></span>
<div class="slot" id="slot1" style="display: none; float: left; clear: left"></div>
<div class="slot" id="slot2" style="display: none; float: right; clear: right"></div>
<div class="slot" id="slot3" style="display: none; float: left; clear: left"></div>
<div class="slot" id="slot3" style="display: none; float: right; clear: right"></div>
<div class="slot" id="slot5" style="display: none; float: left; clear: left"></div>
<div class="slot" id="slot6" style="display: none; float: right; clear: right"></div>
<div class="slot" id="slot7" style="display: none; float: left; clear: left"></div>
<div class="slot" id="slot8" style="display: none; float: right; clear: right"></div>
</div>

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
