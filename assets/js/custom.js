// Parallax
if (!/wp-admin/.test(window.location.href)) {
    $(function(){ParallaxScroll.init()});var ParallaxScroll={showLogs:!1,round:1e3,init:function(){return this._log("init"),this._inited?(this._log("Already Inited"),void(this._inited=!0)):(this._requestAnimationFrame=function(){return window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||window.oRequestAnimationFrame||window.msRequestAnimationFrame||function(a,t){window.setTimeout(a,1e3/60)}}(),void this._onScroll(!0))},_inited:!1,_properties:["x","y","z","rotateX","rotateY","rotateZ","scaleX","scaleY","scaleZ","scale"],_requestAnimationFrame:null,_log:function(a){this.showLogs&&console.log("Parallax Scroll / "+a)},_onScroll:function(a){var t=$(document).scrollTop(),e=$(window).height();this._log("onScroll "+t),$("[data-parallax]").each($.proxy(function(i,o){var s=$(o),r=[],n=!1,l=s.data("style");void 0==l&&(l=s.attr("style")||"",s.data("style",l));var d=[s.data("parallax")],c;for(c=2;s.data("parallax"+c);c++)d.push(s.data("parallax-"+c));var v=d.length;for(c=0;v>c;c++){var m=d[c],u=m["from-scroll"];void 0==u&&(u=Math.max(0,$(o).offset().top-e)),u=0|u;var h=m.distance,p=m["to-scroll"];void 0==h&&void 0==p&&(h=e),h=Math.max(0|h,1);var w=m.easing,x=m["easing-return"];if(void 0!=w&&$.easing&&$.easing[w]||(w=null),void 0!=x&&$.easing&&$.easing[x]||(x=w),w){var g=m.duration;void 0==g&&(g=h),g=Math.max(0|g,1);var f=m["duration-return"];void 0==f&&(f=g),h=1;var _=s.data("current-time");void 0==_&&(_=0)}void 0==p&&(p=u+h),p=0|p;var y=m.smoothness;void 0==y&&(y=30),y=0|y,(a||0==y)&&(y=1),y=0|y;var A=t;A=Math.max(A,u),A=Math.min(A,p),w&&(void 0==s.data("sens")&&s.data("sens","back"),A>u&&("back"==s.data("sens")?(_=1,s.data("sens","go")):_++),p>A&&("go"==s.data("sens")?(_=1,s.data("sens","back")):_++),a&&(_=g),s.data("current-time",_)),this._properties.map($.proxy(function(a){var t=0,e=m[a];if(void 0!=e){"scale"==a||"scaleX"==a||"scaleY"==a||"scaleZ"==a?t=1:e=0|e;var i=s.data("_"+a);void 0==i&&(i=t);var o=(e-t)*((A-u)/(p-u))+t,l=i+(o-i)/y;if(w&&_>0&&g>=_){var d=t;"back"==s.data("sens")&&(d=e,e=-e,w=x,g=f),l=$.easing[w](null,_,d,e,g)}l=Math.ceil(l*this.round)/this.round,l==i&&o==e&&(l=e),r[a]||(r[a]=0),r[a]+=l,i!=r[a]&&(s.data("_"+a,r[a]),n=!0)}},this))}if(n){if(void 0!=r.z){var X=m.perspective;void 0==X&&(X=800);var Y=s.parent();Y.data("style")||Y.data("style",Y.attr("style")||""),Y.attr("style","perspective:"+X+"px; -webkit-perspective:"+X+"px; "+Y.data("style"))}void 0==r.scaleX&&(r.scaleX=1),void 0==r.scaleY&&(r.scaleY=1),void 0==r.scaleZ&&(r.scaleZ=1),void 0!=r.scale&&(r.scaleX*=r.scale,r.scaleY*=r.scale,r.scaleZ*=r.scale);var Z="translate3d("+(r.x?r.x:0)+"px, "+(r.y?r.y:0)+"px, "+(r.z?r.z:0)+"px)",q="rotateX("+(r.rotateX?r.rotateX:0)+"deg) rotateY("+(r.rotateY?r.rotateY:0)+"deg) rotateZ("+(r.rotateZ?r.rotateZ:0)+"deg)",F="scaleX("+r.scaleX+") scaleY("+r.scaleY+") scaleZ("+r.scaleZ+")",S=Z+" "+q+" "+F+";";this._log(S),s.attr("style","transform:"+S+" -webkit-transform:"+S+" "+l)}},this)),window.requestAnimationFrame?window.requestAnimationFrame($.proxy(this._onScroll,this,!1)):this._requestAnimationFrame($.proxy(this._onScroll,this,!1))}};
}
// Instafeed
(function(){var e;e=function(){function e(e,t){var n,r;this.options={target:"instafeed",get:"popular",resolution:"thumbnail",sortBy:"none",links:!0,mock:!1,useHttp:!1};if(typeof e=="object")for(n in e)r=e[n],this.options[n]=r;this.context=t!=null?t:this,this.unique=this._genKey()}return e.prototype.hasNext=function(){return typeof this.context.nextUrl=="string"&&this.context.nextUrl.length>0},e.prototype.next=function(){return this.hasNext()?this.run(this.context.nextUrl):!1},e.prototype.run=function(t){var n,r,i;if(typeof this.options.clientId!="string"&&typeof this.options.accessToken!="string")throw new Error("Missing clientId or accessToken.");if(typeof this.options.accessToken!="string"&&typeof this.options.clientId!="string")throw new Error("Missing clientId or accessToken.");return this.options.before!=null&&typeof this.options.before=="function"&&this.options.before.call(this),typeof document!="undefined"&&document!==null&&(i=document.createElement("script"),i.id="instafeed-fetcher",i.src=t||this._buildUrl(),n=document.getElementsByTagName("head"),n[0].appendChild(i),r="instafeedCache"+this.unique,window[r]=new e(this.options,this),window[r].unique=this.unique),!0},e.prototype.parse=function(e){var t,n,r,i,s,o,u,a,f,l,c,h,p,d,v,m,g,y,b,w,E,S,x,T,N,C,k,L,A,O,M,_,D;if(typeof e!="object"){if(this.options.error!=null&&typeof this.options.error=="function")return this.options.error.call(this,"Invalid JSON data"),!1;throw new Error("Invalid JSON response")}if(e.meta.code!==200){if(this.options.error!=null&&typeof this.options.error=="function")return this.options.error.call(this,e.meta.error_message),!1;throw new Error("Error from Instagram: "+e.meta.error_message)}if(e.data.length===0){if(this.options.error!=null&&typeof this.options.error=="function")return this.options.error.call(this,"No images were returned from Instagram"),!1;throw new Error("No images were returned from Instagram")}this.options.success!=null&&typeof this.options.success=="function"&&this.options.success.call(this,e),this.context.nextUrl="",e.pagination!=null&&(this.context.nextUrl=e.pagination.next_url);if(this.options.sortBy!=="none"){this.options.sortBy==="random"?M=["","random"]:M=this.options.sortBy.split("-"),O=M[0]==="least"?!0:!1;switch(M[1]){case"random":e.data.sort(function(){return.5-Math.random()});break;case"recent":e.data=this._sortBy(e.data,"created_time",O);break;case"liked":e.data=this._sortBy(e.data,"likes.count",O);break;case"commented":e.data=this._sortBy(e.data,"comments.count",O);break;default:throw new Error("Invalid option for sortBy: '"+this.options.sortBy+"'.")}}if(typeof document!="undefined"&&document!==null&&this.options.mock===!1){m=e.data,A=parseInt(this.options.limit,10),this.options.limit!=null&&m.length>A&&(m=m.slice(0,A)),u=document.createDocumentFragment(),this.options.filter!=null&&typeof this.options.filter=="function"&&(m=this._filter(m,this.options.filter));if(this.options.template!=null&&typeof this.options.template=="string"){f="",d="",w="",D=document.createElement("div");for(c=0,N=m.length;c<N;c++){h=m[c],p=h.images[this.options.resolution];if(typeof p!="object")throw o="No image found for resolution: "+this.options.resolution+".",new Error(o);E=p.width,y=p.height,b="square",E>y&&(b="landscape"),E<y&&(b="portrait"),v=p.url,l=window.location.protocol.indexOf("http")>=0,l&&!this.options.useHttp&&(v=v.replace(/https?:\/\//,"//")),d=this._makeTemplate(this.options.template,{model:h,id:h.id,link:h.link,type:h.type,image:v,width:E,height:y,orientation:b,caption:this._getObjectProperty(h,"caption.text"),likes:h.likes.count,comments:h.comments.count,location:this._getObjectProperty(h,"location.name")}),f+=d}D.innerHTML=f,i=[],r=0,n=D.childNodes.length;while(r<n)i.push(D.childNodes[r]),r+=1;for(x=0,C=i.length;x<C;x++)L=i[x],u.appendChild(L)}else for(T=0,k=m.length;T<k;T++){h=m[T],g=document.createElement("img"),p=h.images[this.options.resolution];if(typeof p!="object")throw o="No image found for resolution: "+this.options.resolution+".",new Error(o);v=p.url,l=window.location.protocol.indexOf("http")>=0,l&&!this.options.useHttp&&(v=v.replace(/https?:\/\//,"//")),g.src=v,this.options.links===!0?(t=document.createElement("a"),t.href=h.link,t.appendChild(g),u.appendChild(t)):u.appendChild(g)}_=this.options.target,typeof _=="string"&&(_=document.getElementById(_));if(_==null)throw o='No element with id="'+this.options.target+'" on page.',new Error(o);_.appendChild(u),a=document.getElementsByTagName("head")[0],a.removeChild(document.getElementById("instafeed-fetcher")),S="instafeedCache"+this.unique,window[S]=void 0;try{delete window[S]}catch(P){s=P}}return this.options.after!=null&&typeof this.options.after=="function"&&this.options.after.call(this),!0},e.prototype._buildUrl=function(){var e,t,n;e="https://api.instagram.com/v1";switch(this.options.get){case"popular":t="media/popular";break;case"tagged":if(!this.options.tagName)throw new Error("No tag name specified. Use the 'tagName' option.");t="tags/"+this.options.tagName+"/media/recent";break;case"location":if(!this.options.locationId)throw new Error("No location specified. Use the 'locationId' option.");t="locations/"+this.options.locationId+"/media/recent";break;case"user":if(!this.options.userId)throw new Error("No user specified. Use the 'userId' option.");t="users/"+this.options.userId+"/media/recent";break;default:throw new Error("Invalid option for get: '"+this.options.get+"'.")}return n=e+"/"+t,this.options.accessToken!=null?n+="?access_token="+this.options.accessToken:n+="?client_id="+this.options.clientId,this.options.limit!=null&&(n+="&count="+this.options.limit),n+="&callback=instafeedCache"+this.unique+".parse",n},e.prototype._genKey=function(){var e;return e=function(){return((1+Math.random())*65536|0).toString(16).substring(1)},""+e()+e()+e()+e()},e.prototype._makeTemplate=function(e,t){var n,r,i,s,o;r=/(?:\{{2})([\w\[\]\.]+)(?:\}{2})/,n=e;while(r.test(n))s=n.match(r)[1],o=(i=this._getObjectProperty(t,s))!=null?i:"",n=n.replace(r,""+o);return n},e.prototype._getObjectProperty=function(e,t){var n,r;t=t.replace(/\[(\w+)\]/g,".$1"),r=t.split(".");while(r.length){n=r.shift();if(!(e!=null&&n in e))return null;e=e[n]}return e},e.prototype._sortBy=function(e,t,n){var r;return r=function(e,r){var i,s;return i=this._getObjectProperty(e,t),s=this._getObjectProperty(r,t),n?i>s?1:-1:i<s?1:-1},e.sort(r.bind(this)),e},e.prototype._filter=function(e,t){var n,r,i,s,o;n=[],r=function(e){if(t(e))return n.push(e)};for(i=0,o=e.length;i<o;i++)s=e[i],r(s);return n},e}(),function(e,t){return typeof define=="function"&&define.amd?define([],t):typeof module=="object"&&module.exports?module.exports=t():e.Instafeed=t()}(this,function(){return e})}).call(this);

var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent);

var ajaxurl = ttp.ajaxurl;
var siteurl = ttp.siteurl;

var move = {
    onMove: function() {
        move.slideUp();
    },
    isOnScreen: function(elem) {
        var item = jQuery(elem);
        var win = jQuery(window);
        var viewport = {
            top : win.scrollTop(),
            left : win.scrollLeft()
        };
        viewport.right = viewport.left + win.width();
        viewport.bottom = viewport.top + win.height();
     
        var bounds = item.offset();
        bounds.right = bounds.left + item.outerWidth();
        bounds.bottom = bounds.top + item.outerHeight();
     
        return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
    },
    slideUp: function() {
        var fadeWrap = jQuery('*[data-animation="slideUp"]');
        if(fadeWrap.length > 0) {
            fadeWrap.each(function(){
                var text = jQuery(this);
                if(move.isOnScreen(text)) {
                    text.addClass("slideIn");
                } else {
                    jQuery(window).scroll(function(){
                        if(move.isOnScreen(text)) {
                            text.addClass("slideIn");
                        }
                    });
                }
            });
        }
    }
};

var init = {
	onReady: function() {
        init.instafeed();
        init.loadListing();
        init.modal();
        init.thumbnail();
        init.starRatingClick();
        init.recipeModal();
        init.categoryClick();
        init.videoClick();
        init.ctaClick();
        init.contactSubmit();
        init.loadPage();
        if(window.location.href.indexOf("recipes") > -1) {
            jQuery('#bodyWrap').addClass("out");
            jQuery('body').addClass("stop");
            init.starRating();
            init.dishPics();
        }
        if(isMobile) {
            init.mobileHover();
        }
	},
    mobileHover: function() {
        jQuery('.hover').on('touchstart touchend', function(e) {
            e.preventDefault();
            jQuery(this).toggleClass('mobile_hover');
        });
    },
    loadPage: function() {
        setTimeout(
            function(){
                jQuery("#loader").fadeOut();
            }, 700
        );
    },
    videoClick: function() {
        jQuery('#introVideo').on("click",function(){
            var video = jQuery('#introVideo video');
            if(jQuery('#introVideo').hasClass("playing")) {
                video[0].pause();
                jQuery('#introVideo').removeClass("playing");
            } else {
                video[0].play();
                jQuery('#introVideo').addClass("playing");
            }
        });
        jQuery('#introVideo video').on('pause', function() {
            jQuery('#introVideo').removeClass("playing");
        });
    },
    instafeed: function() {
        var userFeed = new Instafeed({
            get: 'user',
            userId: '1936919979',
            clientId: 'b5a9123e62b24e26bc01dc76ec2b213d',
            accessToken: '1936919979.b5a9123.f4492fd6106043a5b33405791d8ff30e',
            resolution: 'standard_resolution',
            template: '<div style="background:url({{image}}) no-repeat scroll center / cover;"></div>',
            limit: 12,
            sortBy: 'most-liked'
        });
        userFeed.run();
    },
    dishPics: function() {
        var feed = new Instafeed({
            get: 'tagged',
            target: 'dishpics',
            tagName: 'dishpics',
            clientId: 'b5a9123e62b24e26bc01dc76ec2b213d',
            accessToken: '1936919979.b5a9123.f4492fd6106043a5b33405791d8ff30e',
            resolution: 'standard_resolution',
            template: '<div class="pic"><a href="{{link}}" style="background:url({{image}}) no-repeat scroll center / cover;"></a></div>',
            limit: 6,
            sortBy: 'random'
        });
        feed.run();
    },
    listingAjax: function(categories, ingredients, perPage, count) {
        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                action: 'loadListing',
                categories: categories,
                ingredients: ingredients,
                count: count,
                trigger: ttp.trigger,
                security: ttp.listing_nonce,
                pageNumber: ttp.page
            },
            dataType: "html",
            beforeSend : function(){
                jQuery("#listingWrap").append('<div id="ajaxLoad"><i class="fa fa-spinner fa-spin"></i></div>');
            },
            success : function(data){
                var recipes = jQuery(data);
                // add counts
                ttp.page++;
                ttp.trigger++;
                if(recipes.length >= 1) {
                    jQuery("#ajaxLoad").remove();
                    jQuery("#listingWrap").append(recipes);
                    setTimeout(
                        function(){
                            recipes.addClass("slideIn");
                        }, 500
                    );
                    if(recipes.length < perPage) {
                        ttp.loading = true;
                    } else {
                        ttp.loading = false;
                    }
                } else {
                    // remove loader
                    jQuery("#ajaxLoad").remove();
                    ttp.loading = true;
                }
            },
            complete: function() {
                init.ctaClick();
            },
            error : function(xhr, status, error) {
                jQuery("#ajaxLoad").remove();
                alert("ERROR - xhr.status: " + xhr.status + '\nxhr.responseText: ' + xhr.responseText + '\nxhr.statusText: ' + xhr.statusText + '\nError: ' + error + '\nStatus: ' + status);
            }
        });
    },
    loadListing: function() {
        jQuery(window).scroll(function() {
            var totalHeight = (jQuery(window).scrollTop() + jQuery(window).height());
            var contentHeight = (jQuery("#listingWrap").scrollTop() + jQuery("#listingWrap").height() - 500);
            if(!ttp.loading && totalHeight > contentHeight) {
                ttp.loading = true;
                var categories = jQuery('#listingWrap').attr('data-cat');
                var ingredients = jQuery('#listingWrap').attr('data-tag');
                var perPage = jQuery('#listingWrap').attr('data-perPage');
                var count = jQuery('#listingWrap .recipe').last().attr("data-color").replace('color','');
                init.listingAjax(categories, ingredients, perPage, count);
            }
        });
    },
    closeModal: function() {
        jQuery('.modal').removeClass('in');
        jQuery('#bodyWrap').removeClass("out");
        jQuery('body').removeClass("stop");
        window.history.replaceState('','','/');
        setTimeout(
            function(){
                jQuery('.modal #recipeWrap').remove();
            }, 500
        );
    },
    modal: function() {
        jQuery('.btn-modal').click(function(e){
            e.preventDefault();
            var modal = jQuery(this).attr("data-modal");
            jQuery('body').addClass("stop");
            jQuery('#bodyWrap').addClass("out");
            jQuery('.modal[data-modal="'+modal+'"]').addClass("in");
        });
        jQuery('.modal .fa-close').click(function(e){
            e.preventDefault();
            jQuery(this).parent().removeClass('in');
            jQuery('#bodyWrap').removeClass("out");
            if(!jQuery('.modal').hasClass("in")) {
                jQuery('body').removeClass("stop");
            }
            window.history.replaceState('','','/');
        });
    },
    recipeAjax: function(postID, urlPath) {
        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                postID: postID,
                security: ttp.recipe_nonce,
                action: 'loadRecipe'
            },
            dataType: "html",
            success : function(data){
                jQuery('#recipeWrap').remove();
                window.history.replaceState('','',urlPath.replace(siteurl,""));
                jQuery('.recipe').removeClass("clicked");
                jQuery('.modal.single-recipes').append(data);
                init.starRating();
                init.dishPics();
            },
            complete: function() {
                jQuery('#bodyWrap').addClass("out");
                jQuery('.single-recipes').addClass("in");
                jQuery(".recipeLoad").remove();
            },
            error : function(xhr, status, error) {
                jQuery(".recipeLoad").remove();
                alert("ERROR - xhr.status: " + xhr.status + '\nxhr.responseText: ' + xhr.responseText + '\nxhr.statusText: ' + xhr.statusText + '\nError: ' + error + '\nStatus: ' + status);
            }
        });
    },
    recipeModal: function() {
        jQuery(document).on("click",'.recipe',function(e){
            e.preventDefault();
            var postID = jQuery(this).attr("data-post");
            var urlPath = jQuery(this).attr("href");

            if(jQuery(this).hasClass("clicked")) {
                jQuery('body').addClass("stop");
            } else {
                jQuery(this).addClass("clicked");
                jQuery('body').addClass("stop");
                jQuery("article",this).append('<div class="recipeLoad"><i class="fa fa-spinner fa-spin"></i></div>');
                setTimeout(
                    function(){
                        jQuery('.recipeLoad').addClass("in");
                    }, 5
                );
                init.recipeAjax(postID, urlPath);
            }
        });
        jQuery(document).on("click",'.relatedRecipe',function(e){
            e.preventDefault();
            var postID = jQuery(this).attr("data-post");
            var urlPath = jQuery(this).attr("href");
            jQuery('.single-recipes').animate({
               scrollTop: 0
            }, "fast", function(){
                jQuery('.single-recipes').removeClass('in');
                jQuery('#bodyWrap').removeClass("out");
            });
            setTimeout(
                function(){
                    init.recipeAjax(postID, urlPath);
                }, 500
            );
        });
    },
    thumbnail: function() {
        jQuery(document).on("click",'.thumbnail',function(){
            var feature = jQuery('.featureImage img').attr("src");
            var image = jQuery(this).attr("data-image");
            jQuery('.featureImage img').attr("src", image);
            jQuery(this).attr("data-image", feature);
            jQuery("span",this).css('background','url('+feature+') no-repeat scroll center / cover');
        });
    },
    saveRating: function(postID, rating) {
        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                action: 'setRating',
                postID: postID,
                security: ttp.rating_nonce,
                rating: rating
            },
            dataType: 'html',
            error : function(jqXHR, textStatus, errorThrown) {
                jQuery(window).alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
        });
    },
    starRating: function() {
        var rating = jQuery('#recipe_rating').val();
        jQuery('#starRating i[data-star="'+rating+'"]').addClass("active").prevAll().addClass("active");
    },
    starRatingClick: function() {
        jQuery(document).on("click",'#starRating i',function(e){
            e.preventDefault();
            var postID = jQuery('#starRating').attr("data-post");
            var rating = jQuery(this).attr("data-star");
            jQuery('#starRating .fa-star').removeClass("active");
            jQuery(this).addClass("active").prevAll().addClass("active");
            jQuery('#recipe_rating').val(rating);
            init.saveRating(postID,rating);
        });
    },
    filterAjax: function(categories, catNames, ingredients, tagNames, termName) {
        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                categories: categories,
                ingredients: ingredients,
                security: ttp.filter_nonce,
                action: 'loadFilter'
            },
            dataType: "html",
            success : function(data){
                var recipes = jQuery(data);

                jQuery('.modal').removeClass('in');
                jQuery('#bodyWrap').removeClass("out");
                jQuery('body').removeClass("stop");

                jQuery("#listingWrap a").removeClass("slideIn");
                jQuery("#listingWrap").attr("data-cat", categories);
                jQuery("#listingWrap").attr("data-tag", ingredients);

                jQuery('#listingTitle').html(termName);
                if(catNames !== undefined) {
                    if(catNames.length !== 0 && termName === "Categories") {
                        jQuery('#listingTitle').append('<span class="catNames">( '+catNames+' )</span>');
                    }
                }
                if(tagNames !== undefined) {
                    if(tagNames.length !== 0 && termName === "Ingredients") {
                        jQuery('#listingTitle').append('<span class="tagNames">( '+tagNames+' )</span>');
                    }
                }
                if(tagNames !== undefined && catNames !== undefined) {
                    if(tagNames.length !== 0 && catNames.length !== 0) {
                        jQuery('#listingTitle').append('<span class="catNames">categories&bull;'+catNames+'</span><span class="tagNames">ingredients&bull;'+tagNames+'</span>');
                    }
                }
                jQuery("#listingWrap").html(recipes);

                setTimeout(
                    function(){
                        jQuery('html,body').animate({
                           scrollTop: jQuery("#listing").offset().top - 40
                        }, 1000);
                        recipes.addClass("slideIn"); 
                    }, 500
                );
            },
            complete: function() {
                setTimeout(
                    function(){
                        ttp.page = 2;
                        ttp.loading = false;
                        init.loadListing();
                    }, 500
                );
            },
            error : function(xhr, status, error) {
                jQuery(".recipeLoad").remove();
                alert("ERROR - xhr.status: " + xhr.status + '\nxhr.responseText: ' + xhr.responseText + '\nxhr.statusText: ' + xhr.statusText + '\nError: ' + error + '\nStatus: ' + status);
            }
        });
    },
    categoryClick: function() {
        jQuery('.modal[data-modal="ingredients"] a, .modal[data-modal="category"] a').click(function(e){
            e.preventDefault();
            if(jQuery(this).hasClass("clicked")) {
                jQuery(this).removeClass("clicked");
            } else {
                jQuery(this).addClass("clicked");
            }
        });
        jQuery('.btn-filter').click(function(e){
            e.preventDefault();
            jQuery(this).html('<i class="fa fa-spinner fa-spin"></i>');
            if(jQuery('.modal a').hasClass("clicked")) {
                var categories = [];
                var catNames = [];
                jQuery('.modal[data-modal="category"] a.clicked').each(function(){
                    categories.push(jQuery(this).attr("data-term"));
                    catNames.push(jQuery(this).text());
                });
                var ingredients = [];
                var tagNames = [];
                jQuery('.modal[data-modal="ingredients"] a.clicked').each(function(){
                    ingredients.push(jQuery(this).parent().attr("data-term"));
                    tagNames.push(jQuery(this).text());
                });
                if(categories.length !== 0 && ingredients.length !== 0) {
                    var termName = 'Categories / Ingredients';
                } else {
                    if(categories.length !== 0) {
                        var termName = 'Categories';
                    }
                    if(ingredients.length !== 0) {
                        var termName = 'Ingredients';
                    }
                }
                var termType = "category";
            } else {
                var categories = 0;
                var ingredients = 0;
                var termName = "All Recipes";
                var termType = "recipes";
                var urlPath = siteurl;
            }
            init.filterAjax(categories, catNames, ingredients, tagNames, termName);
            setTimeout(
                function(){
                    jQuery('.btn-filter').html("Filter");
                }, 500
            );
        });
    },
    mailChimpAjax: function(list,userIP,fname,lname,email,button) {
        jQuery.ajax({
            url: ajaxurl,
            type: "GET",
            data: {
                userIP: userIP,
                fname: fname,
                lname: lname,
                email: email,
                list: list,
                security: ttp.subscribe_nonce,
                action: 'mailchimpSubscribe'
            },
            dataType: 'html',
            success: function(response) {
                if(response === "success") {
                    jQuery('input').val("");
                    button.addClass('success');
                    button.html('<fa class="fa fa-check"></i>');
                    setTimeout(
                        function(){
                            button.removeClass('success').html('Join Us');
                        }, 2500
                    );
                }
            },
            error : function(xhr, status, error) {
                alert("ERROR - xhr.status: " + xhr.status + '\nxhr.responseText: ' + xhr.responseText + '\nxhr.statusText: ' + xhr.statusText + '\nError: ' + error + '\nStatus: ' + status);
            }
        });
    },
    ctaClick: function() {
        jQuery('.btn-newsletter').click(function(e){
            e.preventDefault();
            var button = jQuery(this);
            button.html('<i class="fa fa-spinner fa-spin"></i>');
            var list = button.parent().attr('data-list');
            var fname = button.parent().find('input[name="fname"]').val();
            var lname = button.parent().find('input[name="lname"]').val();
            var email = button.parent().find('input[name="email"]').val();
            if(fname && lname && email) {
                jQuery.get("https://ipinfo.io/json", function(response) {
                    init.mailChimpAjax(list,response['ip'],fname,lname,email,button);
                });
            } else {
                if(!fname) {
                    button.parent().find('input[name="fname"]').addClass('error');
                }
                if(!lname) {
                    button.parent().find('input[name="lname"]').addClass('error');
                }
                if(!email) {
                    button.parent().find('input[name="email"]').addClass('error');
                }
                button.addClass('error').html('<i class="fa fa-ban"></i>');
                setTimeout(
                    function(){
                        button.removeClass('error').html('Join Us');
                        jQuery('input').removeClass('error');
                    }, 2500
                );
            }
        });
    },
    contactAjax: function(userIP,fname,lname,email,message,button) {
        jQuery.ajax({
            url: ajaxurl,
            type: "GET",
            data: {
                userIP: userIP,
                fname: fname,
                lname: lname,
                email: email,
                message: message,
                security: ttp.contact_nonce,
                action: 'contactEmail'
            },
            dataType: 'html',
            success: function(response) {
                if(response === "success") {
                    jQuery('input, textarea').val("");
                    button.addClass('success');
                    button.html('<fa class="fa fa-check"></i>');
                    setTimeout(
                        function(){
                            button.removeClass('success').html('Join Us');
                        }, 2500
                    );
                } else {
                    button.addClass('error').html('<i class="fa fa-ban"></i>');
                    setTimeout(
                        function(){
                            button.removeClass('error').html('Send');
                            jQuery('input').removeClass('error');
                            jQuery('textarea').removeClass('error');
                        }, 2500
                    );
                }
            },
            error : function(xhr, status, error) {
                alert("ERROR - xhr.status: " + xhr.status + '\nxhr.responseText: ' + xhr.responseText + '\nxhr.statusText: ' + xhr.statusText + '\nError: ' + error + '\nStatus: ' + status);
            }
        });
    },
    contactSubmit: function() {
        jQuery('.btn-contact').click(function(e){
            e.preventDefault();
            var button = jQuery(this);
            button.html('<i class="fa fa-spinner fa-spin></i>"');
            var fname = button.parent().find('input[name="fname"]').val();
            var lname = button.parent().find('input[name="lname"]').val();
            var email = button.parent().find('input[name="email"]').val();
            var message = button.parent().find('textarea').val();
            if(fname && lname && email && message) {
                jQuery.get("https://ipinfo.io/json", function(response) {
                    init.contactAjax(response['ip'],fname,lname,email,message,button);
                });
            } else {
                if(!fname) {
                    button.parent().find('input[name="fname"]').addClass('error');
                }
                if(!lname) {
                    button.parent().find('input[name="lname"]').addClass('error');
                }
                if(!email) {
                    button.parent().find('input[name="email"]').addClass('error');
                }
                if(!message) {
                    button.parent().find('textarea').addClass('error');
                }
                button.addClass('error').html('<i class="fa fa-ban"></i>');
                setTimeout(
                    function(){
                        button.removeClass('error').html('Send');
                        jQuery('input').removeClass('error');
                        jQuery('textarea').removeClass('error');
                    }, 2500
                );
            }
        });
    }
};

jQuery(document).ready(function($) {
	move.onMove();
	init.onReady();
});