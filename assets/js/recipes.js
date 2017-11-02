var ajaxurl = ttp.ajaxurl;

var recipes = {
	onReady: function() {
        recipes.filterImages();
        recipes.addInputs();
        recipes.imageUploader();
        recipes.ordering();
        recipes.removeBtn();
        recipes.starRating();
	},
	addInputs: function() {
		jQuery(".btn-ingredient").click(function(e) {
            e.preventDefault();
            var count = jQuery(this).attr("data-count");
            jQuery('#ingredientListing').append('<article class="ingredient"><div class="move"><i class="fa fa-bars"></i></div><input type="text" class="measurement" name="ingredients['+count+'][measure]" placeholder="measurement" /><span>-</span><input type="text" class="ingredientTitle" name="ingredients['+count+'][ingredient_title]" placeholder="ingredient" /><div class="remove"><i class="fa fa-close"></i></div></article>');
        	count++;
        	jQuery(this).attr("data-count",count);
        });
        jQuery(".btn-instruction").click(function(e) {
            e.preventDefault();
            jQuery('#instructionListing').append('<article class="instruction"><div class="move"><i class="fa fa-bars"></i></div><input type="text" name="instructions[]" placeholder="cooking step" /><div class="remove"><i class="fa fa-close"></i></div></article>' );
        });
	},
	imageUploader: function() {
		var meta_image_frame;
     	// Runs when the image button is clicked.
	    jQuery('.upload-image').click(function(e){
	    	// Prevents the default action from occuring.
	        e.preventDefault();
	        var id = jQuery(this).parent().attr("data-post");
	        var key = jQuery('.photoWrap').children().length;
	        // Sets up the media library frame
	        meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
                library: { type: 'image', uploadedTo : id },
	            multiple: false
	        });
	        // Opens the media library frame.
	        meta_image_frame.open();
	        // Runs when an image is selected.
	        meta_image_frame.on('select', function(){
                console.log("selected");
	            // Grabs the attachment selection and creates a JSON representation of the model.
	            var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
	            // Append selected photo and save to post
	            jQuery('.photoWrap').append('<article class="photo ui-state-default" data-order="'+key+'"><img src="'+media_attachment.url+'" alt="" /><div class="remove" data-key="'+key+'"><i class="fa fa-close"></i></div></article>' );
	            recipes.saveImage(id, media_attachment.id);
	            // Close the media library frame.
	        	meta_image_frame.close();
	        });

	    });
	},
	saveImage: function(id, url) {
        jQuery.ajax({
            url: ajaxurl,
            type: "GET",
            data: {
            	postID: id,
                fieldVal: url,
                action: 'setImage'
            },
            dataType: 'html',
            success : function() {
            	recipes.removeItem();
            },
            error : function(jqXHR, textStatus, errorThrown) {
                window.alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
        }); 
    },
    removeItem: function(postID, key, type) {
        jQuery.ajax({
            url: ajaxurl,
            type: "GET",
            data: {
                action: 'removeItem',
                postID: postID,
                type: type,
                key: key
            },
            dataType: 'html',
            error : function(jqXHR, textStatus, errorThrown) {
                window.alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
        });
    },
    removeBtn: function() {
        jQuery(".remove").click(function(e){
            // prevent default
            e.preventDefault();
            // define vars
            var postID = jQuery(this).parent().parent().attr("data-post");
            var type = jQuery(this).parent().parent().attr("data-type");
            var key = jQuery(this).attr("data-key");
            // remove item
            jQuery(this).parent().remove();
            // ajax call to save removal
            recipes.removeItem(postID, key, type);
        });
    },
    saveOrder: function(order, type, postID) {
        jQuery.ajax({
            url: ajaxurl,
            type: "GET",
            data: {
            	order : order,
            	type: type,
            	postID: postID,
                action: 'setOrder'
            },
            dataType: 'JSON'
        });
    },
    ordering: function() {
    	jQuery( ".sortable" ).sortable({
			placeholder: "ui-state-highlight",
			// Do callback function on jquery ui drop
			update: function( event, ui ) {
				var order = [];
				jQuery("article", this).each(function() {
					order.push(jQuery(this).attr("data-order"));
				});
				var postID = jQuery(this).attr("data-post");
				var type = jQuery(this).attr("data-type");
				recipes.saveOrder(order, type, postID);
			}
		});
		jQuery( ".sortable" ).disableSelection();
    },
    starRating: function() {
        var rating = jQuery('#recipe_rating').val();
        jQuery('#starRating i[data-star="'+rating+'"]').addClass("active").prevAll().addClass("active");
    },
    filterImages: function() {
        jQuery(document).on("DOMNodeInserted", function(){
            // Lock uploads to "Uploaded to this post"
            jQuery('select.attachment-filters [value="uploaded"]').attr( 'selected', true ).parent().trigger('change');
        });
    }
};
jQuery(document).ready(function($) {
	recipes.onReady();
});