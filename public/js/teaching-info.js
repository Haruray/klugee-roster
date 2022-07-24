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
    var add_loading = function (selector) {
        var loadingHTML="<div id=\"loader\" class=\"loader\"></div>"
        insertHtml(selector,loadingHTML);
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
          "<button onclick=\"$dc2.CloseTeachingInfo()\"type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">"+
            "<span aria-hidden=\"true\">&times;</span> </button></div>"+
        "<div id=\"teaching-info-body\" class=\"modal-body\">"+
                "</div></div></div></div>";
    var teachDetails = "<div class=\"attendance-box\" style=\"background-color: #00c2cb; width:100%;\">"+
    "<h3 id=\"progress-report-heading\" class=\"page-sub-heading\">Progress report is&nbsp;<span class=\"yellow\">filled</span></h3>"+
    "<h1 id=\"progress-report-checkmark\" class=\"page-sub-heading\"><i class=\"fa fa-check swing animated infinite input-confirm-check\"></i></h1>"+
    "<p id=\"input-confirm-description\" class=\"input-confirm-description\"> <names> <br> <date> <br> <program> <br> <unit> <br> <exercise> <br> <scores> </p>"+
"</div>";

var attendanceModal = "<div class=\"modal fade\" id=\"attendance-modal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalCenterTitle\" aria-hidden=\"true\">"+
"<div class=\"modal-dialog modal-dialog-centered\" role=\"document\">"+
  "<div class=\"modal-content\">"+
    "<div class=\"modal-header\">"+
      "<h5 class=\"modal-title\" id=\"teach-modal-title\">Attendance Information</h5>"+
      "<button onclick=\"$dc2.CloseTeachingInfo()\"type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">"+
        "<span aria-hidden=\"true\">&times;</span> </button></div>"+
    "<div id=\"attendance-info-body\" class=\"modal-body\">"+
            "</div></div></div></div>";
var attendanceDetails = "<div class=\"attendance-box\" style=\"width:100%;\">"+
"<h3 class=\"page-sub-heading\">Attendance is&nbsp;<span class=\"yellow\">filled</span></h3>"+
"<h1 class=\"page-sub-heading\"><i class=\"fa fa-check swing animated infinite input-confirm-check\"></i></h1>"+
"<p id=\"input-confirm-description\" class=\"input-confirm-description\"> <names> <br> <date> <br> <program> <br> <location> <br> <classtype> </p>"+
"</div>";

    dc2.TeachingInfo = function(id){
        let newModal = false;
        if (document.getElementById("teach-modal")==null){
            insertHtml("body",teachModal);
            newModal = true;
        }
        // $('#teach-modal').modal({backdrop: 'static', keyboard: false})
        $('#teach-modal').modal('toggle');
        if (newModal){
            show_loading("#teaching-info-body");
        }
        else{
            document.getElementById("names").innerHTML='';
            document.getElementById("date-modal").innerHTML='';
            document.getElementById("program").innerHTML='';
            document.getElementById("unit").innerHTML='';
            document.getElementById("exercise").innerHTML='';
            document.getElementById("scores").innerHTML='';
            add_loading("#input-confirm-description")
        }
        $.ajax({
            url : '/get/teaching-info/'+id,
            type : 'get',
            dataType : 'JSON',
            cache : false,
            contentType : false,
            processData : false,
            success : function(response){
                if (response.success){
                    //check if all alpha
                    let alphaFlag = true;
                    for (k = 0 ; k < response['progress'].length ; k++){
                        alphaFlag = alphaFlag && response['progress'][k]['student_alpha'];
                    }
                    if (!alphaFlag){
                        //index reference idk
                        let indexReference = 0;
                        for (k = 0 ; k < response['progress'].length ; k++){
                            if (response['progress'][k]['level']!=null){
                                indexReference = k;
                                break;
                            }
                        }
                        let _date = new Date(response['progress'][indexReference]['date']);
                        let day = convertDay(_date.getDay());
                        let students = '';

                        for (var j = 0 ; j < response['progress'].length-1 ; j++){
                            students += response['progress'][j]['name'];
                            if (response['progress'][j]['homework']){
                                students+=" (Izin, Homework)";
                            }
                            else if (response['progress'][j]['alpha']){
                                students+=" (Alpha)";
                            }
                            students+=", ";
                        }
                        students+=response['progress'][response['progress'].length-1]['name'];
                        if (response['progress'][response['progress'].length-1]['homework']){
                            students+=" (Izin, Homework)";
                        }
                        else if (response['progress'][j]['alpha']){
                            students+=" (Alpha)";
                        }
                        let timedetails = day+", "+formatDate(_date);
                        let program = response['progress'][indexReference]['program']+", Level "+response['progress'][indexReference]['level'];
                        let unit = response['progress'][indexReference]['unit'];
                        let exercise = response['progress'][indexReference]['last_exercise'];
                        let scores = '';
                        for (var i = 0 ; i < response['progress'].length ; i++){
                            scores += response['progress'][i]['name']+"'s Score : "+ response['progress'][i]['score']+" <br> ";
                        }
                        if (newModal){
                            students = "<p class=\"input-confirm-description\" style=\"margin-bottom:-15px\" id='names'>"+ students + "</p>";
                            timedetails = "<p class=\"input-confirm-description\" style=\"margin-bottom:-15px\" id='date-modal'>"+ timedetails + "</p>";
                            program = "<p class=\"input-confirm-description\" style=\"margin-bottom:-15px\" id='program'>"+ program + "</p>";
                            unit = "<p class=\"input-confirm-description\" style=\"margin-bottom:-15px\" id='unit'>"+ unit + "</p>";
                            exercise = "<p class=\"input-confirm-description\" style=\"margin-bottom:-15px\" id='exercise'>"+ exercise + "</p>";
                            scores = "<p class=\"input-confirm-description\" style=\"margin-bottom:-15px\" id='scores'>"+ scores + "</p>";
                            teachDetails = teachDetails.replace("<names>",students);
                            teachDetails = teachDetails.replace("<date>",timedetails);
                            teachDetails = teachDetails.replace("<program>",program);
                            teachDetails = teachDetails.replace("<unit>",unit);
                            teachDetails = teachDetails.replace("<exercise>",exercise);
                            teachDetails = teachDetails.replace("<scores>",scores);
                            replaceHtml("#teaching-info-body",teachDetails);
                            let headingFilled = "Progress report is&nbsp;<span class=\"yellow\">filled</span>";
                            let vmark = "<i class=\"fa fa-check swing animated infinite input-confirm-check\"></i>";
                            document.getElementById("progress-report-heading").innerHTML = headingFilled;
                            document.getElementById("progress-report-checkmark").innerHTML = vmark;
                        }
                        else{
                            document.getElementById("loader").remove();
                            document.getElementById("names").innerHTML=students;
                            document.getElementById("date-modal").innerHTML=timedetails;
                            document.getElementById("program").innerHTML=program;
                            document.getElementById("unit").innerHTML=unit;
                            document.getElementById("exercise").innerHTML=exercise;
                            document.getElementById("scores").innerHTML=scores;
                            let headingFilled = "Progress report is&nbsp;<span class=\"yellow\">filled</span>";
                            let vmark = "<i class=\"fa fa-check swing animated infinite input-confirm-check\"></i>";
                            document.getElementById("progress-report-heading").innerHTML = headingFilled;
                            document.getElementById("progress-report-checkmark").innerHTML = vmark;
                        }

                    }
                    else{
                        if (newModal){
                            students = "<p class=\"input-confirm-description\" style=\"margin-bottom:-15px\" id='names'></p>";
                            timedetails = "<p class=\"input-confirm-description\" style=\"margin-bottom:-15px\" id='date-modal'></p>";
                            program = "<p class=\"input-confirm-description\" style=\"margin-bottom:-15px\" id='program'></p>";
                            unit = "<p class=\"input-confirm-description\" style=\"margin-bottom:-15px\" id='unit'></p>";
                            exercise = "<p class=\"input-confirm-description\" style=\"margin-bottom:-15px\" id='exercise'></p>";
                            scores = "<p class=\"input-confirm-description\" style=\"margin-bottom:-15px\" id='scores'></p>";
                            teachDetails = teachDetails.replace("<names>",students);
                            teachDetails = teachDetails.replace("<date>",timedetails);
                            teachDetails = teachDetails.replace("<program>",program);
                            teachDetails = teachDetails.replace("<unit>",unit);
                            teachDetails = teachDetails.replace("<exercise>",exercise);
                            teachDetails = teachDetails.replace("<scores>",scores);
                            replaceHtml("#teaching-info-body",teachDetails);
                            let headingNotFilled = "All students are alpha.";
                            let xmark = "<i class=\"fa fa-times swing animated infinite attendance-history-icon-notdone\"></i>";
                            document.getElementById("progress-report-heading").innerHTML = headingNotFilled;
                            document.getElementById("progress-report-checkmark").innerHTML = xmark;
                        }
                        else{
                            document.getElementById("loader").remove();
                            document.getElementById("names").innerHTML='';
                            document.getElementById("date-modal").innerHTML='';
                            document.getElementById("program").innerHTML='';
                            document.getElementById("unit").innerHTML='';
                            document.getElementById("exercise").innerHTML='';
                            document.getElementById("scores").innerHTML='';
                            let headingNotFilled = "All students are alpha.";
                            let xmark = "<i class=\"fa fa-times swing animated infinite attendance-history-icon-notdone\"></i>";
                            document.getElementById("progress-report-heading").innerHTML = headingNotFilled;
                            document.getElementById("progress-report-checkmark").innerHTML = xmark;
                        }
                    }


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

    dc2.AttendanceInfo = function(id){
        let newModal = false;
        if (document.getElementById("attendance-modal")==null){
            insertHtml("body",attendanceModal);
            newModal = true;
        }
        // $('#teach-modal').modal({backdrop: 'static', keyboard: false})
        $('#attendance-modal').modal('toggle');
        if (newModal){
            show_loading("#attendance-info-body");
        }
        else{
            document.getElementById("names-attendance").innerHTML='';
            document.getElementById("date-modal-attendance").innerHTML='';
            document.getElementById("program-attendance").innerHTML='';
            document.getElementById("location-attendance").innerHTML='';
            document.getElementById("class-type").innerHTML='';
            add_loading("#input-confirm-description")
        }
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
                        students += response['progress'][j]['name'];
                        if (response['progress'][j]['homework']){
                            students+=" (Izin, Homework)";
                        }
                        else if (response['progress'][j]['alpha']){
                            students+=" (Alpha)";
                        }
                        students+=", ";
                    }
                    students+=response['progress'][response['progress'].length-1]['name'];
                    if (response['progress'][response['progress'].length-1]['homework']){
                        students+=" (Izin, Homework)";
                    }
                    else if (response['progress'][j]['student_alpha']){
                        students+=" (Alpha)";
                    }
                    let timedetails = day+", "+formatDate(_date);
                    let program = response['progress'][0]['program'];
                    let location = response['progress'][0]['location'];
                    let classtype = response['progress'][0]['class_type'];
                    if (newModal){
                        students = "<p class=\"input-confirm-description\" style=\"margin-bottom:-15px\" id='names-attendance'>"+ students + "</p>";
                        timedetails = "<p class=\"input-confirm-description\" style=\"margin-bottom:-15px\" id='date-modal-attendance'>"+ timedetails + "</p>";
                        program = "<p class=\"input-confirm-description\" style=\"margin-bottom:-15px\" id='program-attendance'>"+ program + "</p>";
                        location = "<p class=\"input-confirm-description\" style=\"margin-bottom:-15px\" id='location-attendance'>"+ location + "</p>";
                        classtype = "<p class=\"input-confirm-description\" style=\"margin-bottom:-15px\" id='class-type'>"+ classtype + "</p>";
                        attendanceDetails = attendanceDetails.replace("<names>",students);
                        attendanceDetails = attendanceDetails.replace("<date>",timedetails);
                        attendanceDetails = attendanceDetails.replace("<program>",program);
                        attendanceDetails = attendanceDetails.replace("<location>",location);
                        attendanceDetails = attendanceDetails.replace("<classtype>",classtype);
                        replaceHtml("#attendance-info-body",attendanceDetails);
                    }
                    else{
                        document.getElementById("loader").remove();
                         document.getElementById("names-attendance").innerHTML=students;
                         document.getElementById("date-modal-attendance").innerHTML=timedetails;
                         document.getElementById("program-attendance").innerHTML=program;
                         document.getElementById("location-attendance").innerHTML=location;
                         document.getElementById("class-type").innerHTML=classtype;
                    }

                }
                else{
                    erase_loading("#attendance-info-body");
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
    }



    global.$dc2 = dc2;
})(window);
