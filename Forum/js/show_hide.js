function showHide(elementId) {
    function showElements(show_category, show_forum, show_thread, show_post, 
            show_user) {
        var category_list = document.getElementById("category_list");
        if(category_list != null) {
            if(show_category) {
                category_list.style.display = "inline-block";
            } else {
                category_list.style.display = "none";
            }
        }
        var forum_list = document.getElementById("forum_list");
        if(forum_list != null) {
            if(show_forum) {
                forum_list.style.display = "inline-block";
            } else {
                forum_list.style.display = "none";
            }
        }
        var thread_list = document.getElementById("thread_list");
        if(thread_list != null) {
            if(show_thread) {
                thread_list.style.display = "inline-block";
            } else {
                thread_list.style.display = "none";
            }
        }
        var post_list = document.getElementById("post_list");
        if(post_list != null) {
            if(show_post) {
                post_list.style.display = "inline-block";
            } else {
                post_list.style.display = "none";
            }
        }
        var user_list = document.getElementById("user_list");
        if(user_list != null) {
            if(show_user) {
                user_list.style.display = "inline-block";
            } else {
                user_list.style.display = "none";
            }
        }
    }

    switch (elementId) {
        case 'category_list':
            showElements(true, false, false, false, false);   
            break;
        case 'forum_list':
            showElements(false, true, false, false, false);
            break;
        case 'thread_list':
            showElements(false, false, true, false, false);
            break;
        case 'post_list':
            showElements(false, false, false, true, false);
            break;
        case 'user_list':
            showElements(false, false, false, false, true);
            break;
        default:
            showElements(false, false, false, false, false);
            break;
    }   
}