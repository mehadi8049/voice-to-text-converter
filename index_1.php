
<head>

<link href="files/style.css" rel="stylesheet" type="text/css">

	<script src="files/jquery.js"></script>
	<script>	
		( function( $ ) {
		$( document ).ready(function() {
		$('#cssmenu').prepend('<div id="menu-button"><img border="0" class="logo" src="files/menu.png" width="24"></div>');
			$('#cssmenu #menu-button').on('click', function(){
				var menu = $(this).next('ul');
				if (menu.hasClass('open')) {
					menu.removeClass('open');
				}
				else {
					menu.addClass('open');
				}
			});
		});
		} )( jQuery );
	</script>
	<script type="text/javascript">
		var constraints = { audio: true, video:false }

		navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
		    console.log("getUserMedia() success, stream created, initializing Recorder.js ...");

		    /* assign to gumStream for later use */
		    gumStream = stream;

		    /* use the stream */
		    input = audioContext.createMediaStreamSource(stream);

		}).catch(function(err) {
		    
		});
	</script>
</head>

<body>
	<!-- Box Start -->
		<div style="margin: 10px">
						<div style="margin: 5px">		<script>
(function(e, p){
		var m = location.href.match(/platform=(win8|win|mac|linux|cros)/);
		e.id = (m && m[1]) ||
					 (p.indexOf('Windows NT 6.2') > -1 ? 'win8' : p.indexOf('Windows') > -1 ? 'win' : p.indexOf('Mac') > -1 ? 'mac' : p.indexOf('CrOS') > -1 ? 'cros' : 'linux');
		e.className = e.className.replace(/\bno-js\b/,'js');
	})(document.documentElement, window.navigator.userAgent)
		</script>
<style>
		#results {
		font-size: 18px;
		font-weight: bold;
		border: 3px solid #ddd;
		padding: 10px;
		text-align: left;
		min-height: 150px;
	font-family: SolaimanLipi;
		}
</style>
<link rel="stylesheet" href="voice/shoelacee.css">
	<div id="div_start">
					<button id="start_button" onclick="startButton(event)" style="background: #fff; border: 1px solid #ddd; border-radius: 6px; height: 50px; width: 50px; padding: 0px"><img alt="Start" id="start_img" src="voice/mic.gif"></button>
				</div>
						<h2>Bangla Voice To Text</h2>
						<p class="page-description">A simple composer that allows you to take text by recording your voice</p>   
						<p class="no-browser-support">Only For <span style="color: red; font-weight: bold">Google Chrome</span> User</p>

		<div class="browser-landing" id="main">
			
			<div class="compact marquee">
				<div id="info">
					<p id="info_speak_now" style="display:none, color:red;">
						<span style="color: red; font-weight: bold">Start Speak...</span>
					</p>
					<p id="info_no_speech" style="display:none">
						No speech was detected. You may need to adjust your microphone settings.
					</p>
					<p id="info_no_microphone" style="display:none">
						No microphone was found. Ensure that a microphone is installed and that microphone settings are configured correctly.
					</p>
					<p id="info_allow" style="display:none">
						Click the "Allow" button above to enable your microphone.
					</p>
					<p id="info_denied" style="display:none">
						Permission to use microphone was denied.
					</p>
					<p id="info_blocked" style="display:none">
						Permission to use microphone is blocked. To change, go to chrome://settings/contentExceptions#media-stream
					</p>
					<p id="info_upgrade" style="display:none">
						Speech Recognition is not supported by this browser. Upgrade to <a href="//www.google.com/chrome" target="_blank">Chrome</a> version 25 or later.
					</p>
				</div>
				
				<div id="results">
					<span class="final" id="final_span"></span> <span class="interim" id="interim_span"></span>
				</div>
				</div>
				<div class="compact marquee" id="div_language" style="width: 150px">
					<select style="display: none" id="select_language" onchange="updateCountry()">
						</select>&nbsp;&nbsp; <select id="select_dialect">
						</select>
				</div>
			</div>
		</div>	
<script>
var langs =
[
	['বাংলা',
		['bn-BD', 'বাংলাদেশ'],
		['bn-IN', 'কলকাতা'],
		['en-EN', 'English'],
	],
];

for (var i = 0; i < langs.length; i++) {
	select_language.options[i] = new Option(langs[i][0], i);
}
select_language.selectedIndex = 0;
updateCountry();
select_dialect.selectedIndex = 0;
showInfo('info_start');

function updateCountry() {
	for (var i = select_dialect.options.length - 1; i >= 0; i--) {
		select_dialect.remove(i);
	}
	var list = langs[select_language.selectedIndex];
	for (var i = 1; i < list.length; i++) {
		select_dialect.options.add(new Option(list[i][1], list[i][0]));
	}
	select_dialect.style.visibility = list[1].length == 1 ? 'hidden' : 'visible';
}

var create_email = false;
var final_transcript = '';
var recognizing = false;
var ignore_onend;
var start_timestamp;
if (!('webkitSpeechRecognition' in window)) {
	upgrade();
} else {
	start_button.style.display = 'inline-block';
	var recognition = new webkitSpeechRecognition();
	recognition.continuous = true;
	recognition.interimResults = true;

	recognition.onstart = function() {
		recognizing = true;
		showInfo('info_speak_now');
		start_img.src = 'voice/mic-animate.gif';
	};

	recognition.onerror = function(event) {
		if (event.error == 'no-speech') {
			start_img.src = 'voice/mic.gif';
			showInfo('info_no_speech');
			ignore_onend = true;
		}
		if (event.error == 'audio-capture') {
			start_img.src = 'voice/mic.gif';
			showInfo('info_no_microphone');
			ignore_onend = true;
		}
		if (event.error == 'not-allowed') {
			if (event.timeStamp - start_timestamp < 100) {
				showInfo('info_blocked');
			} else {
				showInfo('info_denied');
			}
			ignore_onend = true;
		}
	};

	recognition.onend = function() {
		recognizing = false;
		if (ignore_onend) {
			return;
		}
		start_img.src = 'voice/mic.gif';
		if (!final_transcript) {
			showInfo('info_start');
			return;
		}
		showInfo('');
		if (window.getSelection) {
			window.getSelection().removeAllRanges();
			var range = document.createRange();
			range.selectNode(document.getElementById('final_span'));
			window.getSelection().addRange(range);
		}
		if (create_email) {
			create_email = false;
			createEmail();
		}
	};

	recognition.onresult = function(event) {
		var interim_transcript = '';
		if (typeof(event.results) == 'undefined') {
			recognition.onend = null;
			recognition.stop();
			upgrade();
			return;
		}
		for (var i = event.resultIndex; i < event.results.length; ++i) {
			if (event.results[i].isFinal) {
				final_transcript += event.results[i][0].transcript;
			} else {
				interim_transcript += event.results[i][0].transcript;
			}
		}
		final_transcript = capitalize(final_transcript);
		final_span.innerHTML = linebreak(final_transcript);
		interim_span.innerHTML = linebreak(interim_transcript);
		if (final_transcript || interim_transcript) {
			showButtons('inline-block');
		}
	};
}

function upgrade() {
	start_button.style.visibility = 'hidden';
	showInfo('info_upgrade');
}

var two_line = /\n\n/g;
var one_line = /\n/g;
function linebreak(s) {
	return s.replace(two_line, '<p></p>').replace(one_line, '<br>');
}

var first_char = /\S/;
function capitalize(s) {
	return s.replace(first_char, function(m) { return m.toUpperCase(); });
}

function createEmail() {
	var n = final_transcript.indexOf('\n');
	if (n < 0 || n >= 80) {
		n = 40 + final_transcript.substring(40).indexOf(' ');
	}
	var subject = encodeURI(final_transcript.substring(0, n));
	var body = encodeURI(final_transcript.substring(n + 1));
	window.location.href = 'mailto:?subject=' + subject + '&body=' + body;
}

function copyButton() {
	if (recognizing) {
		recognizing = false;
		recognition.stop();
	}
	copy_button.style.display = 'none';
	copy_info.style.display = 'inline-block';
	showInfo('');
}

function startButton(event) {
	if (recognizing) {
		recognition.stop();
		return;
	}
	final_transcript = '';
	recognition.lang = select_dialect.value;
	recognition.start();
	ignore_onend = false;
	final_span.innerHTML = '';
	interim_span.innerHTML = '';
	start_img.src = 'voice/mic-slash.gif';
	showInfo('info_allow');
	showButtons('none');
	start_timestamp = event.timeStamp;
}

function showInfo(s) {
	if (s) {
		for (var child = info.firstChild; child; child = child.nextSibling) {
			if (child.style) {
				child.style.display = child.id == s ? 'inline' : 'none';
			}
		}
		info.style.visibility = 'visible';
	} else {
		info.style.visibility = 'hidden';
	}
}

var current_style;
function showButtons(style) {
	if (style == current_style) {
		return;
	}
	current_style = style;
	copy_button.style.display = style;
	email_button.style.display = style;
	copy_info.style.display = 'none';
	email_info.style.display = 'none';
}
		</script>
		</div>
	</div>

</div>
<div class="clear" style="margin: 0 0 25px 0"></div>

</body>
</html>
