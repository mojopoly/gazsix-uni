import $ from 'jquery';
class Like {

    constructor() {
        // alert('testing from Like.js');
        this.events(); //with this our events gets added as soon as the page loads

    }

    events() {
        $(".like-box").on('click', this.ourClickDispatcher.bind(this));
    }

    //methods
    ourClickDispatcher(e) { //our eventhandler above passes along info on which element got clicked; we pass e in case we have multiple like boxes or professors on a single page, therefore we need target an element with a class different from like-box
        var currentLikeBox = $(e.target).closest(".like-box"); //e.target means whatever element got clicked on; closest looks for closest ancester(parent/grandparent) no matter if click on i element, like box grey box, heart icon, etc
        if(currentLikeBox.attr('data-exists')== 'yes') { //instead of data('exists), changed to attr(data-exist), reason is jquery data method only looks once the page is loaded, attr method will let user toggle between like and unlike
            this.deleteLike(currentLikeBox);
        } else {
            this.createLike(currentLikeBox); //we need to pass currentlikebox if we want toaccess data-professor in this function
        }
    }
    createLike(currentLikeBox) {
        // alert("createLike message");
        
        $.ajax({
            beforeSend: (xhr) => { //this pice to pass randomly generated nonce code along with our request so wp knows we're logged in and authorized to do so
            xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: universityData.root_url + '/wp-json/university/v1/manageLike',
            type: 'POST', //regarding professorId below, in order to assign it dynamically, we need to assign a data attribute in html(data-professor) and then access it via js
            data: {'professorId': currentLikeBox.data('professor')}, //we use data property to send along info to server side and we could add it to the end of url above like ...manageLike?professorId=789, but this way is cleaner
            success: (response)=>{
                console.log(response);
                currentLikeBox.attr('data-exists', 'yes');//this will fill the heart
                var likeCount = parseInt(currentLikeBox.find(".like-count").html(),10); //this will fetch html of like count, 10 is number that we use as the base, 9 out 10, we use 10
                likeCount++;//increment the like count
                currentLikeBox.find(".like-count").html(likeCount);
                currentLikeBox.attr("data-like", response); //this to update data-like value on the fly as we like a proff without having to reload the page
            },
            error: (response)=>{
                console.log(response);
            },
        });
    }
    deleteLike(currentLikeBox) {
        // alert("deleteLike message");
        $.ajax({
            beforeSend: (xhr) => { //this pice to pass randomly generated nonce code along with our request so wp knows we're logged in and authorized to do so
            xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: universityData.root_url + '/wp-json/university/v1/manageLike',
            data:{'like': currentLikeBox.attr('data-like')},//here we say WHAT we want to delete, defined data-like in single-professor.php in like-box section
            type: 'DELETE',
            success: (response)=>{
                console.log(response);
                currentLikeBox.attr('data-exists', 'no');//this will remove the heart
                var likeCount = parseInt(currentLikeBox.find(".like-count").html(),10); //this will fetch html of like count, 10 is number that we use as the base, 9 out 10, we use 10
                likeCount--;//decrement the like count
                currentLikeBox.find(".like-count").html(likeCount);
                currentLikeBox.attr("data-like", ''); //this to update data-like value on the fly as we like a proff without having to reload the page
            },
            error: (response)=>{
                console.log(response);
            },
        });
    }

}

export default Like;