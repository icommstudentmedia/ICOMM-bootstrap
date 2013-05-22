/*************************************
*	ajax call to load more news
*************************************/
//args is an associative array of arguments for WP query, attributes is what you want back, 
//title, excerpt, etc., callback is the name of the function that will handle it
function ajaxQuery(args,attributes,callback){
	jQuery.post(ajaxurl,
		{
			action: "ajax_query",
			args: args,
			attributes: attributes
		},
		function(response){
			window[callback](jQuery.parseJSON(response));
		});
}
// use different callback functions so the ajaxQuery method is reusable
var temp;
/********************************************************
* This function is called after an ajax call. It duplicates
* a post element that is already on the page and fills it in with ajax data
* for every element returned by ajax
********************************************************/
function frontPageHandler(response){
	temp = response;
	var clone = jQuery('.recent:last').clone();
	for(var i = 0; i < response.length; i++){
		var image = clone.find('.image img');
		image.attr('src',response[i].image_url);

		var title = clone.find('.title');
		title.html(response[i].title);

		var excerpt = clone.find('.excerpt');
		excerpt.html(response[i].excerpt);

		var author = clone.find('.author');
		author.html(response[i].author);

		var date = clone.find('.date');
		date.html(response[i].date);

		var permalinks = clone.find('.permalink');
		for(var j = 0; j < permalinks.length; j++){
			permalinks[j].href = response[i].permalink
		}

		// you have to clone the clone or it keeps replacing the same post
		var newPost = jQuery(clone).clone(); 
		jQuery('.recent:last').after(newPost); 
	}
}
//called by the button
var page = 2;
function loadMoreFrontPage(){
	var args = { 
		posts_per_page: 20,
		paged: page++
	};
	var attributes = {
		title:null,
		excerpt:null,
		image:'medium',
		author:null,
		date:null,
		permalink:null
	};
	ajaxQuery(args,attributes,"frontPageHandler");
}