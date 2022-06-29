(function (global){
    let dc2={};

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
    let convertDay = function(number){
        if (number == 0){
            return "Sunday";
        }
        else if (number==1){
            return "Monday";
        }
        else if (number==2){
            return "Tuesday";
        }
        else if (number==3){
            return "Wednesday";
        }
        else if (number==4){
            return "Thursday";
        }
        else if (number==5){
            return "Friday";
        }
        else{
            return "Saturday";
        }
    }

    function padTo2Digits(num) {
        return num.toString().padStart(2, '0');
      }

      function formatDate(date) {
        return [
          padTo2Digits(date.getDate()),
          padTo2Digits(date.getMonth() + 1),
          date.getFullYear(),
        ].join('/');
      }


    var teachModal = "<div class=\"modal fade\" id=\"teach-modal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalCenterTitle\" aria-hidden=\"true\">"+
    "<div class=\"modal-dialog modal-dialog-centered\" role=\"document\">"+
      "<div class=\"modal-content\">"+
        "<div class=\"modal-header\">"+
          "<h5 class=\"modal-title\" id=\"teach-modal-title\">Progress Report Information</h5>"+
          "<button onclick=\"$dc.CloseTeachingInfo()\"type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">"+
            "<span aria-hidden=\"true\">&times;</span> </button></div>"+
        "<div id=\"teaching-info-body\" class=\"modal-body\">"+
                "</div></div></div></div>";
    var teachDetails = "<div class=\"attendance-box\" style=\"background-color: #00c2cb; width:100%;\">"+
    "<h3 class=\"page-sub-heading\">Progress report is&nbsp;<span class=\"yellow\">filled</span></h3>"+
    "<h1 class=\"page-sub-heading\"><i class=\"fa fa-check swing animated infinite input-confirm-check\"></i></h1>"+
    "<p class=\"input-confirm-description\"> <names> <br> <date> <br> <program> <br> <unit> <br> <exercise> <br> <scores> </p>"+
"</div>";

    dc2.TeachingInfo = function(id){
        insertHtml("body",teachModal);
        $('#teach-modal').modal('toggle');
        show_loading("#teaching-info-body");
        $.ajax({
            url : '/get/teaching-info/'+id,
            type : 'get',
            dataType : 'JSON',
            cache : false,
            contentType : false,
            processData : false,
            success : function(response){
                if (response.success){
                    let _date = new Date(response['progress'][0]['date']);
                    let day = convertDay(_date.getDay());
                    let students = '';
                    for (var j = 0 ; j < response['progress'].length-1 ; j++){
                        students += response['progress'][j]['name']+", ";
                    }
                    students+=response['progress'][response['progress'].length-1]['name'];
                    let timedetails = day+", "+formatDate(_date);
                    let program = response['progress'][0]['program']+", Level "+response['progress'][0]['level'];
                    let unit = response['progress'][0]['unit'];
                    let exercise = response['progress'][0]['last_exercise'];
                    let scores = '';
                    for (var j = 0 ; j < response['progress'].length-1 ; j++){
                        scores += response['progress'][j]['name']+"'s Score : "+ response['progress'][j]['score']+"<br>";
                    }
                    teachDetails = teachDetails.replace("<names>",students);
                    teachDetails = teachDetails.replace("<date>",timedetails);
                    teachDetails = teachDetails.replace("<program>",program);
                    teachDetails = teachDetails.replace("<unit>",unit);
                    teachDetails = teachDetails.replace("<exercise>",exercise);
                    teachDetails = teachDetails.replace("<scores>",scores);
                    replaceHtml("#teaching-info-body",teachDetails);
                }
                else{
                    erase_loading("#teaching-info-body");
                }
            },
            error : function(response){
                //alert(response.message);
                Swal.fire({
                    icon : 'error',
                    title: 'Oops...',
                    text: 'Input error. Please re-enter the data or reload the page.'
                }).then(function(){
                    location.reload();
                });
            }
        })
    }

    dc2.CloseTeachingInfo = function(){
        document.getElementById("teach-modal").remove();
    }

    global.$dc2 = dc2;
})(window);
