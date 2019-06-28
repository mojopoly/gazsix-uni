import $ from 'jquery'; //npm/weback will fetch jquery for us, $ is madeup name
class MyNotes {
    //constructor
    constructor() {
        // alert("helooo, from MyNotes.js");
        this.events();
    }

    //events
    events() {
        $("#my-notes").on('click', ".delete-note", this.deleteNote); //moved delete-note to middle and replaced with my-notes, since new note posts will not have edit/delete functional right after they're created. With delete-note in the middle, we're saying whenere u click anywhere in the ul my-notes, which will always exist when pages loads, if actual interior matches .delete-note selector, then fire off our call method(aka this.deleteNote)   
        $("#my-notes").on('click', ".edit-note", this.editNote.bind(this)); //we add bind since we're using this inside edit note event
        $("#my-notes").on('click',".update-note", this.updateNote.bind(this)); 
        $(".submit-note").on('click', this.createNote.bind(this)); 
    }

    //Methods
        deleteNote(e) {
            // alert("event hookup to deleteNote method is workin");
            var thisNote = $(e.target).parents("li"); //once delete-note button is clicked, response stored in "e", target implements a simple event delegation
            $.ajax({
                beforeSend: (xhr) => { //this pice to pass randomly generated nonce code along with our request so wp knows we're logged in and authorized to do so
                    xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
                },
                url: universityData.root_url + "/wp-json/wp/v2/note/" + thisNote.data('id'), //last piece from this. will get the data-id that was passed in page-my-notes li
                type: 'DELETE',
                success: (response) => {
                    console.log("congrats");
                    console.log(response);
                    thisNote.slideUp();
                    if(response.userNoteCount <5) {
                        $(".note-limit-message").removeClass("active");
                    }
                },
                error: (response) => {
                    console.log("sorry");
                    console.log(response);                    
                }
            });

        }
        //update note
        updateNote(e) {
            // alert("event hookup to deleteNote method is workin");
            var thisNote = $(e.target).parents("li"); //once delete-note button is clicked, response stored in "e", target implements a simple event delegation
            var ourUpdatedPost = { //this object variable is required when POSTing
                'title': thisNote.find(".note-title-field").val(),
                'content': thisNote.find(".note-body-field").val()
            }
            $.ajax({
                beforeSend: (xhr) => { //this pice to pass randomly generated nonce code along with our request so wp knows we're logged in and authorized to do so
                    xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
                },
                url: universityData.root_url + "/wp-json/wp/v2/note/" + thisNote.data('id'), //last piece from this. will get the data-id that was passed in page-my-notes li
                type: 'POST',
                data: ourUpdatedPost,
                success: (response) => {
                    console.log("congrats");
                    console.log(response);
                    this.makeNoteReadOnly(thisNote);
                },
                error: (response) => {
                    console.log("sorry");
                    console.log(response);                    
                }
            });
        }

        //create note
        createNote(e) {
            var ourNewPost = { //this object variable is required when POSTing
                'title': $(".new-note-title").val(),
                'content': $(".new-note-body").val(),
                'status': 'publish' //we will set post status to private on server side which is more secure in functions.php; with this line, we can remove edit/delete_publish_notes in roles bar in Users dashboard for subscribers       
            }
            $.ajax({
                beforeSend: (xhr) => { //this pice to pass randomly generated nonce code along with our request so wp knows we're logged in and authorized to do so
                    xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
                },
                url: universityData.root_url + "/wp-json/wp/v2/note/",
                type: 'POST',
                data: ourNewPost,
                success: (response) => {
                    console.log("congrats");
                    console.log(response); //this console response will have all the values that we're looking for in following template literal

                    // this.makeNoteReadOnly(thisNote);
                    // thisNote.find(".note-title-field").val(response.title.raw);      // update title 
                    // thisNote.find(".note-body-field").val(response.content.raw);     // update body
  
                    $(".new-note-title", ".new-note-body").val('');//set the value of new note form to null
                    $(`
                    <li data-id="${response.id}"> 
                        <input readonly class="note-title-field" value="${response.title.raw}"> 
                        <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i>Edit</span>
                        <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i>Delete</span>
                        <textarea readonly class="note-body-field">${response.content.raw}</textarea>
                        <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i>Save</span>
                    </li> 
                    `).prependTo("#my-notes").hide().slideDown(); //adding hide first to create an animation effect
                },
                error: (response) => {
                    if(response.responseText) {
                        $(".note-limit-message").addClass("active");
                    }
                    console.log("sorry");
                    console.log(response);                    
                }
            });
        }
                //edit note
                editNote(e) {
                    var thisNote = $(e.target).parents("li");
                    console.log(thisNote);
                    if(thisNote.data("state") == "editable") { //state and editable are made-up names and therefore will return false aka else will run
                        //make read only
                        this.makeNoteReadOnly(thisNote);//we pass along thisnote since it is local only to current function
                    } else {
                        //make editable
                        this.makeNoteEditable(thisNote);
                    }
                }
                makeNoteEditable(thisNote){
                    thisNote.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field");
                    thisNote.find(".update-note").addClass("update-note--visible");
                    thisNote.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"></i>Cancel');
                    thisNote.data("state", "editable");
                }
                makeNoteReadOnly(thisNote) {
                    thisNote.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field");
                    thisNote.find(".update-note").removeClass("update-note--visible");
                    thisNote.find(".edit-note").html('<i class="fa fa-pencils" aria-hidden="true"></i>Edit');
                    thisNote.data("state", "cancel"); //the word cancel is madeup here, it works as long as it is not the word "editable" that was uses in above method
                }
    }

export default MyNotes;