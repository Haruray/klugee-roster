(function (global){
    let dc={};

    //HTML insertion
    var insertHtml = function (selector, html) {
        var targetElem = document.querySelector(selector);
        $(selector).append(html);
      };
    var replaceHtml = function (selector, html) {
        var targetElem = document.querySelector(selector);
        targetElem.innerHTML=html;
      };
    var show_loading = function (selector) {

    var loadingHTML="<div class=\"loader\"></div>"
        replaceHtml(selector,loadingHTML);
        };
    var erase_loading = function(selector){
        var HTML="";
        replaceHtml(selector,HTML);
    };
    
    dc.StudentSearch = function(){
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById('student-search');
        filter = input.value.toUpperCase();
        student_list = document.getElementById("student-list");
        student_cards = document.getElementsByClassName("student-card")
        students = student_list.getElementsByClassName("student-card-name");
        

        // Loop through all list items, and hide those who don't match the search query
        for (i = 0; i < students.length; i++) {
            p = students[i].innerHTML;
            txtValue = p;
            console.log(student_cards[i]);
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
            student_cards[i].style.setProperty("display", "", "important")
            } else {
            student_cards[i].style.setProperty("display", "none", "important")
            }
        }
    }

    global.$dc = dc;
})(window);