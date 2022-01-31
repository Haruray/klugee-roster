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
    
    dc.DocumentationModal = function(attendance_id){
        var modal = document.getElementById("myModal");

        // Get the image and insert it inside the modal - use its "alt" text as a caption
        var img = document.getElementById("show");
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");

        

        //Get attendance_id documentation with fucking ajax
        $.ajax({
            url : '/get/progress-report/'+attendance_id+'/documentation',
            type : 'get',
            dataType : 'JSON',
            success : function(response){
                modal.style.display = "block";
                console.log(response);
                modalImg.src = '/uploads/progress-reports/'+response.documentation;
                captionText.innerHTML = response.documentation;
                
            }
        })

        //
        //
    }

    dc.DocumentationModalClose = function(){
        var modal = document.getElementById("myModal");
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        modal.style.display = "none";
    }

    dc.SortTable = function() {
        var table, rows, switching, i, x, y, shouldSwitch;
        table = document.getElementById("progress-report-table");
        switching = true;
        switched = false;
        /* Make a loop that will continue until
        no switching has been done: */
        while (switching) {

          // Start by saying: no switching is done:
          switching = false;
          rows = table.rows;
          /* Loop through all table rows (except the
          first, which contains table headers): */
          for (i = 1; i < (rows.length - 1); i++) {
            // Start by saying there should be no switching:
            shouldSwitch = false;
            /* Get the two elements you want to compare,
            one from current row and one from the next: */
            x = rows[i].getElementsByTagName("TD")[0];
            y = rows[i + 1].getElementsByTagName("TD")[0];
            // Check if the two rows should switch place:
            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
              // If so, mark as a switch and break the loop:
              shouldSwitch = true;
              break;
            }
          }
          if (shouldSwitch) {
            switched=true;
            /* If a switch has been marked, make the switch
            and mark that a switch has been done: */
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
          }
        }
        if (switched){
            //change sort by newest to sort by oldest
            //sortOldestButtonHTML = "<button onclick=\"$dc.SortTableOldest()\" class=\"btn btn-primary float-left attendance-input-button\" type=\"button\" style=\"font-size: 13px;\"><i class=\"fa fa-sort-down\"></i>&nbsp;Sort by Oldest</button>";
            sortButton = document.getElementById("sort-newest");
            sortButton.innerHTML = "<i class=\"fa fa-sort-down\"></i>&nbsp;Sort by Oldest";
            sortButton.setAttribute('onclick','$dc.SortTableOldest()')
            
        }
      }

      dc.SortTableOldest = function() {
        var table, rows, switching, i, x, y, shouldSwitch;
        table = document.getElementById("progress-report-table");
        switching = true;
        switched = false;
        /* Make a loop that will continue until
        no switching has been done: */
        while (switching) {
          // Start by saying: no switching is done:
          switching = false;
          rows = table.rows;
          /* Loop through all table rows (except the
          first, which contains table headers): */
          for (i = 1; i < (rows.length - 1); i++) {
            // Start by saying there should be no switching:
            shouldSwitch = false;
            /* Get the two elements you want to compare,
            one from current row and one from the next: */
            x = rows[i].getElementsByTagName("TD")[0];
            y = rows[i + 1].getElementsByTagName("TD")[0];
            // Check if the two rows should switch place:
            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
              // If so, mark as a switch and break the loop:
              shouldSwitch = true;
              break;
            }
          }
          if (shouldSwitch) {
            switched=true;
            /* If a switch has been marked, make the switch
            and mark that a switch has been done: */
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
          }
        }
        if (switched){
            //change sort by newest to sort by oldest
            //sortOldestButtonHTML = "<button onclick=\"$dc.SortTableOldest()\" class=\"btn btn-primary float-left attendance-input-button\" type=\"button\" style=\"font-size: 13px;\"><i class=\"fa fa-sort-down\"></i>&nbsp;Sort by Oldest</button>";
            sortButton = document.getElementById("sort-newest");
            sortButton.innerHTML = "<i class=\"fa fa-sort-up\"></i>&nbsp;Sort by Newest";
            sortButton.setAttribute('onclick','$dc.SortTable()')
            
        }
      }

    global.$dc = dc;
})(window);