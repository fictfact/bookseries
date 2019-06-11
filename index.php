<?php

date_default_timezone_set('America/New_York');

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>BookSeries.co - The Book Series Recommendation Engine</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', '<?php echo $_ENV['GA_ID']; ?>']);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>

  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">BookSeries</a>
        </div>
      </div>
    </div>

    <div class="container">

      	<div class="hero-unit">
  			<p>Enter a book series you like and we'll recommend one you should be reading!</p>


  				<form class="form-search">
  						<input type="text" id="searchinput" class="input-medium search-query" data-provide="typeahead" data-source="[]" data-minlength="3" style="width:80%;" placeholder="Enter Series Name">
  						<button class='btn btn-primary' type='button' onclick='doResult($("#seriesdataid").val(), $("#seriesdataname").val());'>Search</button>
				</form>

		</div>

		<table id="results" class="table table-hover" style="display:none;">
			<caption style="text-align:left;">Recommended Series for readers who like <span id="seriesname" style="font-weight:bold;"></span></caption>

			<tbody>
				<tr><td><div class="progress progress-striped active" style="width:100px;">
  					<div class="bar" style="width: 100%;"></div>
				</div></td></tr>
			</tbody>
		</table>

		<input type="hidden" id="seriesdataid" value="">
		<input type="hidden" id="seriesdataname" value="">

		<hr>

		<footer>
        	<p>&copy; BookSeries.co <?php echo date("Y");?> - <a href="http://www.fictfact.com/">Powered by FictFact.com</a></p>
      </footer>

	  </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>

    <script src="js/bootstrap-typeahead.js"></script>

    <script type="text/javascript">

    	$(document).ready(function(){

    		$('#searchinput').typeahead({
    			source: function(typeahead, query) {
        		$.ajax({
            	url: "search_proxy.php",
            	dataType: "json",
            	type: "GET",
            	data: {
                term: query,
                searchType : "series"
               },
            	success: function(data) {
                	var return_list = [];

                 	for(i=0;i<data.hits.hit.length;i++)
                	{
                		hit = data.hits.hit[i];

                		return_list[i] = {id: hit.data.series_id[0], value: hit.data.series_name[0]};

                	}

                	typeahead.process(return_list);
            	}
        		});
    		},
    		onselect: function(obj) {

            $("#seriesdataid").val(obj.id);
        		$("#seriesdataname").val(obj.value);

    			}
			});

			$('.moreBtn').click(function(){
				//nada
			});

    	});

    	function doResult(id, name)
    	{
    		$('#searchinput').val(name);

    		$('#results').show();
    		$('#results').empty();
        	$('#results').append("<caption style='text-align:left;'>Recommended Series for readers who like <span id='seriesname' style='font-weight:bold;'></span></caption>");
        	fillTable(id);
        	$('#seriesname').empty();
        	$('#seriesname').append(name);

        	$("#seriesdataid").val(id);
        	$("#seriesdataname").val(name);

    	}

    	function fillTable(id)
    	{
    		$.ajax({
            	url: "recommendation_proxy.php",
            	dataType: "json",
            	type: "GET",
            	data: {
                seriesid: id
               },
            	success: function(data) {

            		//track when people use it
            		 	var return_list = [];

                	for(i=0;i<data.response.length;i++)
                	{
                		series = data.response[i];

                		display_name = data.response[i].listAuthorDetail[0].display_name;

                		var clickStr = 'doResult('+ id +', "'+ series.name +'");';

                		var newRow = $("<tr><td><b>"+ series.name +"</b> by <b>"+ display_name +"</b></td><td><a href='http://www.fictfact.com/series.aspx?series_id="+ series.series_id +"' target='_whole' class='moreBtn'><button class='btn btn-primary' type='button'>More Info/Track</button></a></td><td><button class='btn btn-info' type='button' onclick='"+ clickStr +"'>Search with this Series</button></td></tr>");

   									$("#results").append(newRow);

                	}


            	}
        	});


    	}

    </script>

  </body>
</html>
