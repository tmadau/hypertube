
async function getMovieDataPromise(result, pageType)
{
	for(let i = 0; i < result.length; i++) 
	{
		let response = await fetch("https://api.themoviedb.org/3/movie/"+ result[i].id +"/external_ids?api_key=4084c07502a720532f5068169281abff");

		if (response.status !== 200)
			alert("something went wrong");
		else 
			var movie = await response.json();

		let response2 = await fetch("https://www.omdbapi.com/?i="+ movie.imdb_id +"&apikey=1f18a935");
		if (response2.status !== 200)
			alert("something went wrong");
		else 
			var moviedata = await response2.json();

	if(moviedata.Response)
	{	
		
		console.log(result[i])
		
		result[i].imdbID = movie.imdb_id ? movie.imdb_id : "N/A"
		result[i].Year = result[i].release_date ? Number((result[i].release_date.split("-"))[0]) : "N/A";
		result[i].tmdbURL = "https://www.themoviedb.org/movie/"+ result[i].id +"";
		result[i].imdbURL = "https://www.imdb.com/title/"+ result[i].imdbID +"/";
		result[i].imdbRating = moviedata.imdbRating ? Number(moviedata.imdbRating) : "N/A"								
		result[i].Poster = moviedata.Poster;
		result[i].genres = JSON.stringify(result[i].genres);// moviedata.Genre;
		result[i].Title = moviedata.Title;	

		if (pageType == "info")
		{	
			result[i].Plot = moviedata.Plot;
			result[i].Production = moviedata.Production;
			result[i].Runtime = moviedata.Runtime;
			result[i].Rated = moviedata.Rated; // age restriction
			result[i].Website = moviedata.Website;
		}
			
	}
	if (pageType == "info")
	{
		let response3 = await fetch("https://api.themoviedb.org/3/movie/"+ result[i].id +"/credits?api_key=4084c07502a720532f5068169281abff");
		if (response3.status !== 200)
			alert("something went wrong");
		else 
			var moviecredit = await response3.json();
		
		let response4 = await fetch("https://api.themoviedb.org/3/movie/"+ result[i].id +"?api_key=4084c07502a720532f5068169281abff");
		if (response4.status !== 200)
			alert("something went wrong");
		else 
			var moviedetail = await response4.json();

		result[i] = $.extend({}, result[i], moviecredit, moviedetail);
	}
	console.log(result[i]);
			
	}
	return result;

}

async function createMovieCard(moviedata) 
{
	var content;
	var imdbRating;
	var imdbURL;

	var rating;
	imdbRating = moviedata.imdbRating;
	if (imdbRating === 'N/A' || imdbRating === 'undefined' || imdbRating === undefined || imdbRating === 'null' || imdbRating === null || isNaN(imdbRating)) //imdbRating === NaN || imdbRating === "NaN" || movie.imdbID === NaN || movie.imdbID === "NaN"
		rating = 'N/A';
	else
		rating = imdbRating + "/10";	

	// check if there is an IMDB ID to have a URL
	if (moviedata.imdbID === 'N/A' || moviedata.imdbID === 'undefined' || moviedata.imdbID === undefined || moviedata.imdbID === 'null' || moviedata.imdbID === null) //|| rating === 'N/A'
		imdbURL = "<p> </p>";
	else
		imdbURL = "<a href='"+ moviedata.imdbURL +"'>Go to IMDb Page</a>";

	//check if there is a year provided
	var yearRelease = moviedata.Year;
	if (yearRelease === 'N/A' || yearRelease === 'undefined' || yearRelease === undefined || yearRelease === 'null' || yearRelease === null || isNaN(yearRelease) || yearRelease <= 0) 
		yearRelease = 'N/A';

	// check if there is a movie poster avaliable
	var srcImagePath;
	if (!(moviedata.poster_path === null))
		srcImagePath = "https://image.tmdb.org/t/p/w342" + moviedata.poster_path;
	else if (!(moviedata.Poster === 'N/A' || moviedata.Poster === undefined))
		srcImagePath = moviedata.Poster;
	else 
		srcImagePath = './images/noImagePoster.png'//"";

		var srcImage;
		if (srcImagePath != "")
			srcImage = srcImagePath = `<img src="${srcImagePath}" style="width: 100%; height: 450px; spadding-top: 0.5rem;"/>`;
		else 
			srcImage =`<img src="${srcImagePath}" style="width: 100%; height: 450px; spadding-top: 0.5rem;"/>`

	// AESTHETIC - This is just a font size chaninging effect for if the movie name is too long.
	var titleSize;
	if(moviedata.title.length <= 65) 
		titleSize = "font-size: 1.2rem";
	else
			titleSize = "font-size: 100%";
	
	var originalTitle;
	if (moviedata.title != moviedata.original_title)
		originalTitle = `<h6>(`+ moviedata.original_title +`)</h6>`;
	else
		originalTitle = ""
	
	var viewed = "display:block";
	viewed = haveSeen(moviedata); 

	content = 
	`<div id="${moviedata.imdbID}"class="moviecards col-sm-4 card border-secondary sm-3" style="max-width: 20rem; min-width: 20rem; align-items: center; border-color: #9933CC; margin-top: 20px; margin-bottom: 20px;" onmouseover="movieHoverIn(this)" onmouseout="movieHoverOut(this)" onclick="loadInfo('`+ moviedata.imdbID +`','`+moviedata.Year+`')">
		<div class="card-header">
			<h5 class="card-title" style="${titleSize}"> ${moviedata.title} </h5>
			${originalTitle}
		</div>
		<div class="card-body">
			<div id="${"viewedload" + moviedata.imdbID}" class="progress" style="width: 20.5px;	float: right;">
				<div class="progress-bar progress-bar-striped bg-info progress-bar-animated" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
			</div>
			<i id="${"viewed" + moviedata.imdbID}" class="far fa-eye" style="float: right; font-size: large; display: none;"></i>
			<br>
			${srcImage}
			<br>
			<p text-muted>Year Released: ${yearRelease} </p>
		</div>
		<div class="card-footer">
			<p><i class="fas fa-star"></i> ${rating} </p>
			<br>
			${imdbURL}
		</div>
	</div>`;

	return content;	
}
		
async function haveSeen(moviedata)
{
	var viewed;

	await $.post('checkWatched.php', {movieID:moviedata.imdbID})
	.done(function( data ) 
	{
		
		var idIcon = "#viewed" + moviedata.imdbID;
		var idLoad = "#viewedload" + moviedata.imdbID;

		if (document.getElementById('viewed' + moviedata.imdbID)) 
		{
			$(idLoad).fadeOut().hide();
			if (data > 0)
				$(idIcon).show();
		}
	})
	.fail(function() 
	{
		console.log("something went wrong..");
	});	
}

function noResults()
{
	let content = `
	<div style="width: 100%; display: flex; justify-content: center;">
		<div class="alert alert-dismissible alert-danger">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<strong>Oh snap!</strong> no results were found matching your criteria! Try something different
		</div>
	</div>`;

	$('#loading').fadeOut();
	$('#result').append(content).hide().fadeIn(); 

}

async function appendMovieCard(moviedata)
{
	console.log("append");
	$('#loading').fadeOut();
	$('#result').append(await createMovieCard(moviedata)).hide().fadeIn(); 
}

async function createMoviePage(moviedata)
{
	console.log("createMoviePage");

	var pageResult = "";
	
	for (let index = 0; index < moviedata.length; index++) {
		const element = moviedata[index];
		
		pageResult += await createMovieCard(element);
	}

	$('#loading').fadeOut();
	$('#result').append(pageResult).hide().fadeIn(); 

}