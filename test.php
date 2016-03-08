<html>
<head>
    <title>Testsidan</title>
<style>
    .hidden {
        display: none;
    }
</style>
</head>
<body>
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.2.1/annyang.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script>
if (annyang) {
  // Let's define our first command. First the text we expect, and then the function it should call

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
      cmds = ['show tps report', 'hide tps report', 'dictate *text', 'test', 'help', 'mirror *cmd (weather, animate)'];
      $.each(cmds, function(index, value ) {
        help +=  value + "\n";
      });
      $('#text').text("Here is help...\n\n"+help);
    },
    'show weather': function(cmd) {
      console.log('Showing weather');
      responsiveVoice.speak("Showing weather");
      var element = $('<img src="bad-weather.jpg" id="weather" alt="weather">');
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
    'take picture': function(cmd) {
      responsiveVoice.speak('Smile! - Taking picture.');
      var name = 'test.jpg';
      var url = "localhost://picture.php?name=sune";
      var output = $.getJSON(url, function(giphyresult) {
          console.log(giphyresult);
      });
      <?php
      $name = $_GET['name'];
      //$x = shell_exec('/usr/bin/fswebcam '.$name);
      //var_dump($x);
      //echo file_get_contents("localhost://picture.php?name=sune");
      ?>
      //var element = $('<?php echo file_get_contents("localhost://picture.php?name=sune"); ?>')
      //var element = $('<img src="bad-weather.jpg" id="weather" alt="weather">');
      //var element = '<iframe src="localhost://picture.php?name='+name+'">';
      addElement(element, false);
    }
    /*
    'mirror *cmd': function(cmd) {
      responsiveVoice.speak(cmd);
      console.log('Command: '+cmd);
      $('#text').text(cmd);
      if (cmd.indexOf('weather') >= 0) {
        console.log('Showing weather');
        var element = $('<img src="bad-weather.jpg" id="weather" alt="weather">');
        addElement(element, false);

      } else if (cmd.indexOf('animate') >= 0) {
        console.log('Giphy');
        var query = cmd.substr(cmd.indexOf("animate") + 8);
        query = query.replace(/ /g, "+");
        var url = "https://api.giphy.com/v1/gifs/search?q="+query+"&api_key=dc6zaTOxFJmzC";
        var output = $.getJSON(url, function(giphyresult) {
            console.log(giphyresult);
            var giphUrl = giphyresult.data[0].images.fixed_width.url;
            $('#giphy').html('<img width="300" src="'+giphUrl+'">')
        });
      }
    }
    */
  };

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
              console.log(lmnt.selector);
              if (lmnt.selector == element.selector) {
                console.log("Found element "+lmnt.selector);
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
      $('body').find("div.slot").each(function(slot, lmnt){
        if (!done) {
          console.log(lmnt);
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
    $('body div.slot').find("#"+elementName).each(function(index, lmnt){
      hideElement($(lmnt));
    });
  }

  function hideElement(element) {
    element.fadeOut();
    setTimeout(function() {
      element.parent().html('');
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
  console.log('annyang init');
}

// Wolfram APPID: X33PEJ-QJ327L5QQJ
</script>
<script src='https://code.responsivevoice.org/responsivevoice.js'></script>

<h2>Hello world</h2>
<p id="text" style="white-space: pre-wrap;"></p>
<div class="slot" id="slot1" style="display: none; float: left; clear: left"></div>
<div class="slot" id="slot2" style="display: none; float: right; clear: right"></div>
<div class="slot" id="slot3" style="display: none; float: left; clear: left"></div>
<div class="slot" id="slot3" style="display: none; float: right; clear: right"></div>
<div class="slot" id="slot5" style="display: none; float: left; clear: left"></div>
<div class="slot" id="slot6" style="display: none; float: right; clear: right"></div>
<div id="giphy"></div>
</body>
</html>
