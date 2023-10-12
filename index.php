<!DOCTYPE html>
<html lang="en-US">
<head>
<title>Tile Twist</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="Keywords" content="game,tree,trees,maze,fun,puzzle,test,bored">
<meta name="Description" content="Unscramble tiles to complete the puzzle!">
<link rel="stylesheet" type="text/css" href="./tree.css">
<link href="https://fonts.googleapis.com/css?family=Audiowide" rel="stylesheet">

<script type="text/javascript">
	
	function checkTree(x, y, max=(x * 2)) {

		var count = 1;


		var myobject = document.getElementById(String(x) + "x" + String(y));
		var myimages = myobject.getElementsByTagName('img');

		if (y > 0) {
			var topobject = document.getElementById(String(x) + "x" + String(y - 1));
			var topimages = topobject.getElementsByTagName('img');
		}
		if (x < max) {
			var rightobject = document.getElementById(String(x + 1) + "x" + String(y));
			var rightimages = rightobject.getElementsByTagName('img');
		}
		if (y < max) {
			var bottomobject = document.getElementById(String(x) + "x" + String(y + 1));
			var bottomimages = bottomobject.getElementsByTagName('img');
		}
		if (x > 0) {
			var leftobject = document.getElementById(String(x - 1) + "x" + String(y));
			var leftimages = leftobject.getElementsByTagName('img');
		}
		if (y > 0) {
			if (!(myimages[0].classList.contains('hideme'))) {
				if (!(topimages[2].classList.contains('hideme'))) {
					if (!(topobject.classList.contains('filled'))) {
						topobject.classList.add('filled');
						count += checkTree(x, y - 1, max);
					}
				}
			}
		}
		if (x < max) {
			if (!(myimages[1].classList.contains('hideme'))) {
				if (!(rightimages[3].classList.contains('hideme'))) {
					if (!(rightobject.classList.contains('filled'))) {
						rightobject.classList.add('filled');
						count += checkTree(x + 1, y, max);
					}
				}
			}
		}
		if (y < max) {
			if (!(myimages[2].classList.contains('hideme'))) {
				if (!(bottomimages[0].classList.contains('hideme'))) {
					if (!(bottomobject.classList.contains('filled'))) {
						bottomobject.classList.add('filled');
						count += checkTree(x, y + 1, max);
					}
				}
			}
		}
		if (x > 0) {
			if (!(myimages[3].classList.contains('hideme'))) {
				if (!(leftimages[1].classList.contains('hideme'))) {
					if (!(leftobject.classList.contains('filled'))) {
						leftobject.classList.add('filled');
						count += checkTree(x - 1, y, max);
					}
				}
			}
		}
		return count;
	}

	function savescores(scores) {
		var urlstring = "scores=" + scores;
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById('newboard').click();
			}
		};
		xhttp.open("POST", "writescores.php", true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.send(urlstring);
	}

	function submitscore() {
		var myscores = document.getElementById('scorecontainer').getElementsByTagName('tr');
		var submitname = JSON.stringify(document.getElementById('submitname').value).replace(/(\r\n|\n|\r|,|")/gm, ' ');
		var newscore = [submitname, Number(document.getElementById('bestscore').innerHTML)];
		var scorearray = [];
		
		for (i=0; i<10; i++) {
			scorearray.push([myscores[i].getElementsByClassName("scorename")[0].innerHTML, Number(myscores[i].getElementsByClassName("score")[0].innerHTML)]);
		}
		scorearray.push(newscore);
		scorearray.sort(function(a,b){return b[1] - a[1];});
		var printstring = "";
		for (i=0; i<10; i++) {
			printstring += scorearray[i][0] + "," + String(scorearray[i][1]) + "%0A";
		}
		savescores(printstring);
		// document.getElementById('newboard').click();
	}

	function winner() {
		clearInterval(timerID);
		document.getElementById('victory').style.display = 'block';
		document.getElementById('gameboard').classList.add('winner');
		var scores = document.getElementsByClassName('score');
		var lowscore = Number(scores[scores.length - 1].innerHTML);
		var bestscore = Number(document.getElementById('bestscore').innerHTML)
		var movecount = Number(document.getElementById('movecount').innerHTML)
		if (movecount < bestscore) {
			if (bestscore > lowscore) {
				document.getElementById('submitscorecontainer').classList.add('show');
			}
		}
	}

	function updateTree(center) {
		max = center + center;
		for (j=0; j <= max; j++) {
			for (i=0; i <= max; i++) {
				document.getElementById(String(i) + "x" + String(j)).classList.remove("filled");
			}
		}
		var finalcount = checkTree(center, center, max);
		var piececount = document.getElementById('usedpieces')
		if (piececount.innerHTML == "") {
			piececount.innerHTML = String(finalcount);
		} else if (piececount.innerHTML == String(finalcount)) {
			winner();
		}
	}

	function rotatePiece(myobject) {
		var myimages = myobject.getElementsByTagName('img');
		var newleft, newtop, newbottom, newright = false;
		
		if (myimages[0].classList.contains('hideme')) {
			newright = true;
		}
		if (myimages[1].classList.contains('hideme')) {
			newbottom = true;
		}
		if (myimages[2].classList.contains('hideme')) {
			newleft = true;
		}
		if (myimages[3].classList.contains('hideme')) {
			newtop = true;
		}

		if (newtop) {
			myimages[0].classList.add("hideme");
		} else {
			myimages[0].classList.remove("hideme");
		}
		if (newright) {
			myimages[1].classList.add("hideme");
		} else {
			myimages[1].classList.remove("hideme");
		}
		if (newbottom) {
			myimages[2].classList.add("hideme");
		} else {
			myimages[2].classList.remove("hideme");
		}
		if (newleft) {
			myimages[3].classList.add("hideme");
		} else {
			myimages[3].classList.remove("hideme");
		}
	}

	function getRandomInt(min, max) {
		min = Math.ceil(min);
		max = Math.floor(max);
		return Math.floor(Math.random() * (max - min)) + min;
	}

	function incrementMinutes() {
		var mytime = document.getElementById('minutes');
		var timenumber = (Number(mytime.innerHTML) + 1);
		if (timenumber < 10) {
			mytime.innerHTML = "0" + String(timenumber);
		} else {
			mytime.innerHTML = String(timenumber);
		}
	}

	function incrementClock() {
		var mytime = document.getElementById('time');
		var timenumber = (Number(mytime.innerHTML) + 1);
		if (timenumber < 10) {
			mytime.innerHTML = "0" + String(timenumber);
		} else if (timenumber == 60) {
			mytime.innerHTML = "00";
			incrementMinutes();
		} else {
			mytime.innerHTML = String(timenumber);
		}
	}

	function scrambleBoard(myobject, mycenter) {
		var mytds = myobject.getElementsByTagName('td');
		var blanktds = myobject.getElementsByClassName('blank');
		var bestscore = 0;

		for (i=0; i<mytds.length; i++) {
			if (!(mytds[i].classList.contains('blank'))) {
				if (mytds[i].classList.contains('line')) {
					var times = getRandomInt(1, 3);
					while (times > 0) {
						rotatePiece(mytds[i]);
						times -= 1;
						bestscore += 1;
					}
					bestscore = bestscore + 2;
				} else {
					var times = getRandomInt(1, 5);
					while (times > 0) {
						rotatePiece(mytds[i]);
						times -= 1;
						bestscore += 1;
					}
				}
			}
		}
		document.getElementById("bestscore").innerHTML = String(((mytds.length - blanktds.length)*4)-bestscore);
		return setInterval("incrementClock()", 1000);
	}

	function hideme() {
		document.getElementById('scrambleoverlay').style.display = 'none';
	}

	function increment() {
		myobject = document.getElementById('movecount');
		myobject.innerHTML = String(Number(myobject.innerHTML) + 1);
	}

	function incrementsize(amount) {
		var myobject = document.getElementById('size');
		var currentsize = Number(myobject.value);
		if (Number.isNaN(currentsize)) {
			currentsize = 11;
		}
		currentsize = currentsize + amount;
		if (currentsize < 3) {
			currentsize = 3;
		}
		if (currentsize > 31) {
			currentsize = 31;
		}
		myobject.value = String(currentsize);
	}

</script>

</head>
<body>

<div id="main">
<div id="boardcontainer">
<div id="scrambleoverlay" onclick="timerID = scrambleBoard(document.getElementById('gameboard'), centerid); updateTree(centernum); hideme();"></div>
<div id="victory">
	<div id="submitscorecontainer">
	<div>
	<p>Perfect score! Add your name to the high scores list:</p>
	<input id="submitname" type="text" maxlength="16" placeholder="Enter Your Name">
	<button class="controlbutton" type="button" onclick="submitscore();">Submit</button>
	</div>
	</div>
</div>
<?php

if( isset($_POST['size']) )
{
	$command = escapeshellcmd('python3 tree.py ' . $_POST['size']);
	$mysize = $_POST['size'];
} else {
	$command = escapeshellcmd('python3 tree.py 9');
	$mysize = "9";
}
$output = shell_exec($command);
echo $output;

?>
</div>

<div id="controls">
<h1 id="title">TILET<span id="twist">w</span>IST</h1>
	<form class="countcontainer" method="post" action="index.php" onsubmit="incrementsize(0);">
		<div id="sizediv">
			<span id="sizelabel">Size:</span>
			<input id="size" type="text" name="size" value="<?php echo $mysize; ?>">
			<img class="arrows" src="./arrow-up.png" onclick="incrementsize(2);"><img class="arrows" src="./arrow-down.png" onclick="incrementsize(-2);">
		</div>
		<input class="controlbutton" type="submit" id="newboard" value="New">
		<button class="controlbutton" type="button" id="scramble" onclick="timerID = scrambleBoard(document.getElementById('gameboard'), centerid); updateTree(centernum); hideme();">Scramble</button>
	</form>

	<table class="countcontainer">
		<tr><td>Time:</td><td class="align-right"><span id="minutes">00</span>:<span id="time">00</td></tr>
		<tr><td>Moves:</td><td class="align-right"><span id="movecount">0</span>/<span id="bestscore">0</span></td></tr>
	</table>

	<!--<div id="description" class="countcontainer">
		Click "New" to create a new board, then "Scramble" to mix up the tiles. Click the tiles to rotate them and connect a path from the center tile to the rest of the board.
	</div>-->

	<table id="scorecontainer" class="scorecontainer">
		<caption>Perfect Scores</caption>
		<?php
			$count = 1;
			$myfile = file("./scores/perfect", FILE_IGNORE_NEW_LINES);
			foreach ($myfile as $myline) {
				$myvalues = explode(",", $myline);
				echo "<tr id='score" . $count . "'><td class='scorename'>" . $myvalues[0] . "</td><td class='align-right'><span class='score'>" . $myvalues[1] . "</span></td></tr>";
				$count++;
			}
		?> 
		</table>
</div>

</div>

<script type="text/javascript">
	var boardcenter = document.getElementById('boardcenter').innerHTML;
	var centerid = boardcenter + "x" + boardcenter;
	var centernum = Number(boardcenter)
	timerID = 0;
	document.getElementById(centerid).classList.add('centerpiece');
	document.getElementById(centerid).classList.add('filled');
	document.getElementById(centerid).classList.add('blank');
	document.getElementById(centerid).getElementsByClassName('overlay')[0].onclick = "";
	updateTree(centernum);
</script>

</body>
</html>

