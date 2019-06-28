//we will write object oriented modular(each class will live in its own individual file like here) js

import $ from 'jquery';
//class is like a blueprint
class Search {
    //1. describe and create/initiate our object
    //in js whenerver you make a class, you will need to create a constructor that will run whenever we create an object using Search class
    constructor() {
        // alert("hello, im a search");
        this.addSearchHTML(); //this function needs to sit on top so others could be read
        this.resultsDiv = $("#search-overlay__results");
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.searchField = $("#search-term"); //accessing DOM with this line instead of calling it over and over again will make loading faster
        this.events(); //this makes usre that event listeners in #2 will be added to the page right away
        this.isOverlayOpen = false;
        this.isSpinnerVisible = false; //this to avoid resetting spinner every time a new letter is typed
        this.previousValue;
        this.typingTimer; //creating this object so we can access settimeout function
    }
    //2. events: this is where we connect dots between objects and methods
        // on the events that this.head feels like, we respond by a methid names wearHats from 3.
        events() {
            this.openButton.on('click', this.openOverlay.bind(this)); //by default jquery "on" method will change the value of "this" to point to the html element. therefore we add bind(this) so methods in #3 refer to contructor properties
            this.closeButton.on("click", this.closeOverlay.bind(this));
            $(document).on("keydown", this.keyPressDispatcher.bind(this)); //keyup will only fire once, but keydown fires over and over again
            this.searchField.on("keyup", this.typingLogic.bind(this)); //keydown fires so immedialty before typingLogic function is ran
        }
    //3. methods(function/action,verbs...)
    typingLogic() {
        if (this.searchField.val() != this.previousValue) { //this if makes sure spinner doesnt load if we're moveing pointer and not actually changing characters
            clearTimeout(this.typingTimer); //this will clear the timer and only fires up below console if typer has waited for 2 seconds
            if (this.searchField.val()) { //this if makes sure that search field has a value before doing anything
                if (!this.isSpinnerVisible) { //this avoid rerunning spinner eveytime a new char is fired
                    this.resultsDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible =true;
                }
                this.typingTimer= setTimeout(this.getResults.bind(this), 750); //timeout will set lag before a function is run
            } else {
                this.resultsDiv.html('');
                this.isSpinnerVisible =false;
            }
            
        }
        // alert("search field is workin!");
       
        this.previousValue = this.searchField.val();
    }
    getResults() {
        //get results using our own custom api url; we dont need to worry sync and async anymore, cuz we longer need to send multiple json requests
        $.getJSON(universityData.root_url + '/wp-json/university/v1/search?term=' + this.searchField.val(), (results) => {
            this.resultsDiv.html(`
            <div class ='row'>
                <div class = "one-third">
                    <h2 class="search-overlay__section-title">General Information</h2>
                    ${results.generalInfo.length ? '<ul class="link-list min-list">' : '<p>No general information matches that search...</p>'} 
                    ${results.generalInfo.map(item => `<li><a href="${item.permalink}">${item.title} </a>${item.postType=='post' ? `by ${item.authorName}` : '' }</li>`).join('')}
                    ${results.generalInfo.length ? '</ul>' : ''}
                </div>
                <div class = "one-third">
                    <h2 class="search-overlay__section-title">Programs</h2>
                    ${results.programs.length ? '<ul class="link-list min-list">' : `<p>No programs match that search. <a href="${universityData.root_url}/programs">View all programs</a></p>`} 
                    ${results.programs.map(item => `<li><a href="${item.permalink}">${item.title} </a></li>`).join('')}
                    ${results.programs.length ? '</ul>' : ''}
                    <h2 class="search-overlay__section-title">Professors</h2>
                    ${results.professors.length ? '<ul class="professor-cards">' : '<p>No general professors matches that search...</p>'} 
                    ${results.professors.map(item => `
                        <li class="professor-card__list item">
                            <a class="professor-card" href="${item.permalink}">
                                <img class="professor-card__image" src="${item.image}">
                                <span class="professor-card__name">${item.title}</span>
                            </a>
                        </li>
                    `).join('')}
                    ${results.professors.length ? '</ul>' : ''}
                </div>
                <div class = "one-third">
                    <h2 class="search-overlay__section-title">Campuses</h2>
                    ${results.campuses.length ? '<ul class="link-list min-list">' : `<p>No campuses match that search.<a href="${universityData.root_url}/campuses">View all campuses</a> </p>`} 
                    ${results.campuses.map(item => `<li><a href="${item.permalink}">${item.title} </a></li>`).join('')}
                    ${results.campuses.length ? '</ul>' : ''}
                    <h2 class="search-overlay__section-title">Events</h2>
                    ${results.events.length ? '' : `<p>No events match that search.<a href="${universityData.root_url}/events">View all events</a></p>`} 
                    ${results.events.map(item => `
                    <div class="event-summary">
                        <a class="event-summary__date t-center" href="${item.permalink}">
                            <span class="event-summary__month">${item.month}</span>
                            <span class="event-summary__day">${item.day}</span>  
                        </a>
                        <div class="event-summary__content">
                            <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
                            <p>${item.description}<a href="${item.permalink}" class="nu gray">Learn more</a></p>
                        </div>
                    </div>
                    `).join('')}
                    
            
            <div>
            
            
            `);
            this.isSpinnerVisible=false;

        });
        
        // this.resultsDiv.html('imagine dragons');
        // this.isSpinnerVisible =false;
        //following method is an asynchronus ajax call using wp auto generated api
        // $.when(//when method is used to do API calls asynchronously
        //     $.getJSON(universityData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val()), 
        //     $.getJSON(universityData.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.val())
        //     ).then((posts, pages) => {
        //         var combinedResults = posts[0].concat(pages[0]); //first item in the posts/pages arrays is the json files and rest are details on status of request
        //             this.resultsDiv.html(`
        //             <h2 class="search-overlay__section-title">General Information</h2>
        //             ${combinedResults.length ? '<ul class="link-list min-list">' : '<p>No general information matches that search...</p>'} 
        //             ${combinedResults.map(item => `<li><a href="${item.link}">${item.title.rendered} </a>${item.type=='post' ? `by ${item.authorName}` : '' }</li>`).join('')}
        //             ${combinedResults.length ? '</ul>' : ''}
        //             `); //this by itself points to json, we need to change it so it points to main object here, we can either use .bind or use arrow function
        //             this.isSpinnerVisible= false;
        //     }, () => {
        //         this.resultsDiv.html('<p>Unexpected error! Please try again...</p>');//adding this error handling piece so if url is wrong, we dont get a fatal error
        //     });
        //below method is used synchronously and is slower
        // $.getJSON(universityData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val(), posts => { //first piece will make the url relative
        //     // alert(posts[0].title.rendered);
        //     // var testArray = ['red', 'orange', 'yellow'];
        //     //we can use template literals to write html freely within js
        //     //inside template literal we can only use ternary operator as the conditional
        //     $.getJSON(universityData.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.val(), pages=> {
                
        //     });

        // });
        
    }
    openOverlay() {
        this.searchOverlay.addClass("search-overlay--active");
        $("body").addClass("body-no-scroll"); //this class will set overflow to hidden which will remove the ability to scroll
        // console.log("open ran");
        this.isOverlayOpen= true;
        this.searchField.val('');
        setTimeout(() =>this.searchField.focus(), 301);//300ms is how long it takes for css animation for overlay to load, if a function is on a single line, we dont need curly brackets
        return false; //this will prevent the default behavior of anchor/link elements
    }
    closeOverlay() {

        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
        // console.log("close ran");
        this.isOverlayOpen = false;
    }
    keyPressDispatcher(e) {
        // console.log(e.keyCode); //this will display key code for all keyboard buttons in js
        if (e.keyCode == 83 && !this.isOverlayOpen && !$("input, textarea").is(':focus')) { //this.isOverlayOpen is created cuz keydown runs multiple times until users holds the key down and it could freeze browser, last if condition checks to make sure that if we have a contacf us form lets say, typing "s" wont run the search function
            this.openOverlay();
            // this.searchField.focus();
        } else if(e.keyCode == 27 && this.isOverlayOpen) {
            this.closeOverlay();
        }

    }
    addSearchHTML() {//append is how we add html to js; we add it in js file so if some visitors have js disabled dont see it
        $("body").append(`
        <!-- This is the live search html skeleton; in below div we will add search-overlay--active class by js when search button is clicked -->
            <div class="search-overlay">  
                <div class="search-overlay__top">
                    <div class="container">
                    <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                    <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
                    <?php //having an id will help to have a unique hook to target in js ?>
                    <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>

                    </div>
                </div>
                <div class="container>">
                    <div id= "search-overlay__results"></div>
                </div>

            </div>
        `);
    }
}

export default Search;