
var ready = false;

function downloadQuery(movie, status = true) {
	var movieName = movie;//(movie != null && movie != 'undefined') ? document.getElementById('movie_name').value : movie;
	var xhr = new XMLHttpRequest();

	if (status == true) {
		var address = "http://localhost:3000/startDownload/" + movieName;
	}
	else {
		var address = "http://localhost:3000/checkStatus";
	}

	xhr.open('GET', address, true);

	xhr.onreadystatechange = function () {
		if (xhr.readyState == 4 && xhr.status == 200) {
			if (xhr.responseText != 'failed' && xhr.responseText != 'Pending') {
				var target = document.getElementById('target');
				target.innerHTML = '<button id="startStream_button" value="' + xhr.responseText + '" onclick="startStreamQuery(this)">Start Stream</button></a>';
				ready = true;
				console.log(xhr.responseText);
			}
			else if (xhr.responseText == 'failed') {
				var target = document.getElementById('movie_result');
				target.innerHTML = "<h2>" + xhr.responseText + "</h2>";
			}
			else {
				var target = document.getElementById('movie_result');
				target.innerHTML = "<h2>" + xhr.responseText + "</h2>";
				downloadQuery(movieName, false);
			}
		}
	}

	xhr.send();
}

function startStreamQuery(button)
{
	var movieName = button.value;

	var divMain = document.getElementById('main_body');
	var divSec = document.getElementById('movie_result');

	var result = '<video id="videoPlayer" controls>';
	result += '<source src="http://localhost:3001?movie='+ movieName +'" type="video/mp4">';
	result += '</video>';

	divMain.innerHTML = result;
	divSec.innerHTML = '<button onclick="refreshPage()">Refresh</button>';
	//window.location.href = "http://localhost:8080/Hypertube/video.php?torrent_id=" +val+"&movie="+movieName+"&title="+window.title;
}

if (ready == true)
{
	var startStream_button = document.getElementById('startStream_button');
	startStream_button.addEventListener('click', switchPage);

}

function refreshPage() {
	location.reload();
}
