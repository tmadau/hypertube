<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once('setup.php');
session_start();
error_reporting(E_ALL);
	if (!isset($_SESSION['id'])) {
		header ('Location: ./');
	}

	$db->exec("USE hypertube");
	$query = $db->prepare("SELECT * FROM users WHERE id = :id");
	$query->bindParam(":id", $_SESSION['id']);
	$query->execute();
	$data = $query->fetch(PDO::FETCH_ASSOC);
	$username = $data['username'];
	$picturep = $data['picture']; 

 ?>

<!DOCTYPE html>
<html>
<title></title>
<head>
	<link rel="apple-touch-icon" sizes="57x57" href="/Hypertube/favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/Hypertube/favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/Hypertube/favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/Hypertube/favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/Hypertube/favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/Hypertube/favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/Hypertube/favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/Hypertube/favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/Hypertube/favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/Hypertube/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/Hypertube/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/Hypertube/favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/Hypertube/favicon/favicon-16x16.png">
<link rel="manifest" href="/Hypertube/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/Hypertube/favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">

	<script type="text/javascript" src="sort.js"></script>
	<script type="text/javascript" src="filter.js"></script>
	<script 
		src="https://unpkg.com/popper.js">
	</script>
		<script
		src="https://code.jquery.com/jquery-3.3.1.js"
		integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
		crossorigin="anonymous">
	</script>
	<script 
		src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" 
		integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" 
		crossorigin="anonymous">
	</script>

	<link 
		rel="stylesheet" 
		href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" 
		integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" 
		crossorigin="anonymous">
	<link 
		rel="stylesheet" 
		href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" 
		integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" 
		crossorigin="anonymous">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.5.2/cyborg/bootstrap.min.css" integrity="sha384-nEnU7Ae+3lD52AK+RGNzgieBWMnEfgTbRHIwEvp1XXPdqdO6uLTd/NwXbzboqjc2" crossorigin="anonymous">
		
		<link href="style.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
		/* AESTHETIC */
	</style>
</head>
<body>
	
<div class="topnav" id="myTopnav">
		<a class="navbar-brand" href="#">
    		<img src="<?php echo $picturep?>" alt="profile picture" style="width:40px;">
		</a>
		<a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
        	<?php echo $username ?>
      	</a>
		<div class="dropdown-menu">
        	<a class="dropdown-item" href="./profile.php">My Profile</a>
        	<a class="dropdown-item" href="/Hypertube/logout.php">Logout</a>
    	</div>
		<center>
		<div class="topnav-centered">
			<a href="/Hypertube/home.php"><img src="./images/logo.svg" alt="logo" height="70%" width="70%"></a>
		</div>
		</center>
</div>
	<br>
	<div id="result" class="card border-info mb-3">		
	</div>
	    <div id="main_body">
    </div>
    <div id="movie_result"></div>
    <div id="comment">
    	
    </div>

    <script type="text/javascript" src="/Hypertube/NODE/public/js/download.js"></script>
	<div id="google_translate_element"></div>
</body>
</html>
<script src="showMoviehelpers.js"></script>
<script type="text/javascript">

	var val = "<?php echo $_GET['id'] ?>";
	var date = "<?php echo $_GET['date'] ?>";

	if (val)
	{
		
		$(document).ready(function()
		{

			var arr = [];
			arr.push({'id':val ,'release_date':date});

			getMovieDataPromise(arr,"info").then(function(movie){
				var result = movie[0];

				console.log("result");
				console.log(result);

				//ERROR CHECKING - so as not to get funny values displaying
			// check if there is a rating given
			var rating;
			if (result.imdbRating === 'N/A' || result.imdbRating === 'undefined' || result.imdbRating === undefined || result.imdbRating === 'null' || result.imdbRating === null || isNaN(result.imdbRating)) 
				rating = 'N/A';
			else
				rating = result.imdbRating + "/10";	

			// check if there is an IMDB ID to have a URL
			var imdbURL;
			if (result.imdbID === 'N/A' || result.imdbID === 'undefined' || result.imdbID === undefined || result.imdbID === 'null' || result.imdbID === null) 
				imdbURL = "<p> </p>"; //rating === 'N/A'
			else
				imdbURL = "<a href='"+ result.imdbURL +"'>Go to IMDb Page</a>";

			//check if there is a year provided
			var yearRelease = result.Year;
			if (yearRelease === 'N/A' || yearRelease === 'undefined' || yearRelease === undefined || yearRelease === 'null' || yearRelease === null || isNaN(yearRelease) || yearRelease <= 0) 
				yearRelease = 'N/A';

			// check if there is a movie poster avaliable
			var srcImagePath;
			if (result.poster_path !== null && result.poster_path !== undefined)
				srcImagePath = "https://image.tmdb.org/t/p/w342" + result.poster_path;
			else if (result.Poster !== 'N/A' && result.Poster !== undefined)
				srcImagePath = result.Poster;
			else 
				srcImagePath = "";

		var srcImage;
		if (srcImagePath != "")
			srcImage = `<img src="${srcImagePath}" style="width:100%;"/>`;
		else 
			srcImage = `<div><svg
							xmlns:dc="http://purl.org/dc/elements/1.1/"
							xmlns:cc="http://creativecommons.org/ns#"
							xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
							xmlns:svg="http://www.w3.org/2000/svg"
							xmlns="http://www.w3.org/2000/svg"
							xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
							xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
							width="342"
							height="513"
							viewBox="0 0 90.487498 135.73125"
							version="1.1"
							id="svg8"
							sodipodi:docname="noImagePoster.svg">
							<g
								inkscape:label="Layer 1"
								inkscape:groupmode="layer"
								id="layer1"
								transform="translate(0,-161.26873)">
								<rect
								style="fill:#5d5d5d;fill-opacity:1;stroke-width:1.23137879"
								id="rect41"
								width="90.487503"
								height="135.73125"
								x="0"
								y="161.26872" />
								<text
								xml:space="preserve"
								style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:10.08429718px;line-height:1.25;font-family:'Yu Gothic';-inkscape-font-specification:'Yu Gothic';letter-spacing:0px;word-spacing:0px;fill:#ffffff;fill-opacity:1;stroke:none;stroke-width:0.25210744"
								x="46.937805"
								y="204.47298"
								id="text25"
								transform="scale(0.95284703,1.0494864)"><tspan
									sodipodi:role="line"
									id="tspan23"
									x="46.622673"
									y="204.47298"
									style="font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:12.10115719px;line-height:1.5;font-family:'Microsoft Sans Serif';-inkscape-font-specification:'Microsoft Sans Serif Bold';text-align:center;letter-spacing:-0.63026857px;text-anchor:middle;fill:#ffffff;fill-opacity:1;stroke-width:0.25210744">IMAGE</tspan><tspan
									sodipodi:role="line"
									x="46.622673"
									y="222.62471"
									style="font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:12.10115719px;line-height:1.5;font-family:'Microsoft Sans Serif';-inkscape-font-specification:'Microsoft Sans Serif Bold';text-align:center;letter-spacing:-0.63026857px;text-anchor:middle;fill:#ffffff;fill-opacity:1;stroke-width:0.25210744"
									id="tspan27">NOT</tspan><tspan
									sodipodi:role="line"
									x="46.622669"
									y="240.77644"
									style="font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:12.10115719px;line-height:1.5;font-family:'Microsoft Sans Serif';-inkscape-font-specification:'Microsoft Sans Serif Bold';text-align:center;letter-spacing:-0.63026857px;text-anchor:middle;fill:#ffffff;fill-opacity:1;stroke-width:0.25210744"
									id="tspan29">AVALIABLE</tspan></text>
							</g>
							</svg>
							</div>
							`;


			var originalTitle;
			if (result.title != result.original_title)
				originalTitle = `<h6>(`+ result.original_title +`)</h6>`;
			else
				originalTitle = ""

			var genreList;

			var cast = fillTable(result.cast, "cast");
		//	console.log(cast);
			var crew = fillTable(result.crew, "crew");
		//	console.log(crew);
		console.log( result.genres );
		var genres = []
		result.genres.forEach((gen)=>{
			genres.push(gen.name);
		});
			window.title = result.Title;
					// this is creating a div with the content inside of it
					content =
					`<div class="card-header">
						<h4 id="movieName" class="card-title">${result.Title}</h4>
						${originalTitle}
						<p class="text-muted">( ${result.Year} )</p>
					</div>
					<div class="card-body">	
						<div class="container-fluid">
							<div class="row">
								<div class="col-sm-4 gallery-pad">
									${srcImage}
									<div class="row IMDb" style="padding: 5px;">
										<div class="col-sm gallery-pad">
											<p><i class="fas fa-star"></i> ${rating} </p>
										</div>
										<div class="vl"></div>
										<div class="col-sm gallery-pad">
											<a href="${result.imdbURL}">Go to IMDb Page</a>
										</div>
									</div>
								</div>
								<div class="col-sm-8 gallery-pad">
									<p><b>Genre: </b>${genres}</p>
									<br>
									<p><b>Plot: </b>${result.Plot}</p>
									<br>
									 <center>
		<div class="col">
		<div id="target">
		</div>
			<button class="btn"><i class="fa fa-download"></i> Download</button> 
			<button id='importantStream' class="btn" onclick="downloadQuery('${result.Title + " " + result.Year}'); isWatched();"><i class="fa fa-tv"></i> Stream</button>
		</div>
	</center>
								</div>

							</div>
							<br/>
							<div class="row" style"flex-wrap: nowrap; flex-direction: row; overflow-x: scroll;>
								<ul class="nav nav-tabs" style="width: 100%;">
									<li class="nav-item">
										<a class="nav-link active" data-toggle="tab" href="#cast">Cast</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#crew">Crew</a>
									</li>
								</ul>
								<div id="myTabContent" class="tab-content" style="flex-wrap: nowrap; flex-direction: row; overflow-x: scroll;">
									<div class="tab-pane fade active show" id="cast">
										<div class="container-fluid" >
											<div class="row" style="flex-wrap: nowrap;">
											${cast}
											</div>
										</div>									
									</div>
									<div class="tab-pane fade" id="crew">
									<div class="container-fluid">
											<div class="row" style="flex-wrap: nowrap;">
											${crew}
											</div>
										</div>	
									</div>
								</div>
							</div>
						</div>
					</div>`;

				$('#result').append(content).hide().fadeIn(); 
				$('commenting_frame').src = "./video.php?torrent_id=" +val+"&title="+window.title;
				var src_c = "./video.php?torrent_id=" +val+"&title="+window.title;
				var target = document.getElementById('comment');
				target.innerHTML = '<iframe id="commenting_frame" frameborder="0" scrolling="yes" width="100%" height="198" src="'+src_c+'" name="imgbox" id="imgbox"><p>iframes are not supported by your browser.</p></iframe>';
			});

	
		});		
						
	}		

	function stringifyGenre(result)
	{
		let names = result.map(item => item.name);
		result = names.join(', ');

		return result;
	}

	function fillTable(result, type) // type = cast or crew
	{
		let content = "";

		for (var i = 0; i < result.length; i++) 
		{
			
			let role;

			let srcImage;
			if (!(result[i].profile_path === null))
				srcImage = "https://image.tmdb.org/t/p/w90_and_h90_face/" + result[i].profile_path; // w342 //https://image.tmdb.org/t/p/w90_and_h90_face/kU3B75TyRiCgE270EyZnHjfivoq.jpg
			else
				srcImage = "./images/noImageProfile.svg"
			
			if (type == "cast")
				role = result[i].character;
			if (type == "crew")
				role = result[i].job;  // ""+ result[i].job +" ("+ result[i].department +")";
			 
			content += `
				<div style="padding: 4px;">
					<div class="card border-secondary mb-3" style="width: 10rem;height: 15rem; font-size: 0.75rem;">
						<div class="card-header" style="font-size: 0.75rem;">
							<strong>${result[i].name}</strong>							
							<br/>
							(${role})
						</div>
						<div class="card-body">
							<img src='${srcImage}'/>
						</div>
					</div>
				</div>
			`

			
			//`<td><table><tr><div class='cell_name'><b>${result[i].name}</b></div></tr><tr><div class='cell_image'><img src='${srcImage}'/></div></tr><tr><div class='cell_role'><p>${role}</p></div></tr></table></td>`;
		}

		return content; 
	}

</script>



	<br />

	<script>
	 var val = "<?php echo $_GET['id'] ?>";
	  

	function sendobj_to_video() 
	{
		window.open("http://localhost:8080/Hypertube/video.php?torrent_id=" +val+"&title="+window.title);
	}

	function myFunction() 
	{
		var x = document.getElementById("myTopnav");
		if (x.className === "topnav") 
		{
			x.className += " responsive";
		} 
		else 
		{
			x.className = "topnav";
		}
	}

	function isWatched()
	{
		// simple ajax post call to send information to updateWatched.php
		console.log("in function");
		let url = 'updateWatched.php';

		$.post( url, {movieID:val})
		.done(function( data ) 
		{
			if (data > 0)
				console.log("View added");
			else
				console.log("something went wrong");
		});
	}
	</script>

	<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
}
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
