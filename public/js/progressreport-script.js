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
    }

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

    let getAttendeeScore = function(progressReport, studentID){
        for (var i = 0 ; i < progressReport.length ; i++){
            if (progressReport[i]['id_student'] == studentID){
                return progressReport[i]['score'];
            }
        }
    }

    dc.ProgressReportInput = function(){
        var form = $('form')[1];
        var formdata = new FormData(form);
        //---VALIDATION---
        //Image validation
        var file = document.getElementById("documentation").files[0];
        if (file){
            var t = file.type.split('/').pop().toLowerCase();
            if (t != "jpeg" && t != "jpg" && t != "png" && t != "bmp" && t != "gif") {
                Swal.fire({
                    icon : 'error',
                    title: 'Oops...',
                    text: 'Please select a valid image.'
                });
                document.getElementById("documentation").value = '';
                return false;
            }
        }

        //level

        if (!$("#level").val()){
            Swal.fire({
                icon : 'error',
                title: 'Oops...',
                text: 'Level is empty. Please enter a number.'
            });
            return false;
        }

        //---VALIDATION---
        let progressReportConfirm1 = "<div class=\"tada animated attendance-box\" style=\"background-color: #00c2cb;\">" +
        "<h3 class=\"page-sub-heading\">Progress report is&nbsp;<span class=\"yellow\">filled</span></h3>"+
        "<h1 class=\"page-sub-heading\"><i class=\"fa fa-check swing animated infinite input-confirm-check\"></i></h1>"
        let progressReportComplete = progressReportConfirm1;
        $.ajax({
            url : '/attendance/progress-report/input-process',
            type : 'post',
            dataType : 'JSON',
            cache : false,
            contentType : false,
            processData : false,
            data : formdata,
            success : function(response){
                if (response.success){
                    replaceHtml("#attendance-box","");
                    let formContainer = document.getElementById("attendance-box");
                    formContainer.classList.remove("attendance-box");
                    show_loading("#attendance-box");
                    $.ajax({
                        url : "/get/attendance-present/"+response.attendance_id,
                        type:'get',
                        dataType:'json',
                        success:function(second_response){
                            let indexReference = 0;
                            for (k = 0 ; k < response['progress_report'].length ; k++){
                                if (response['progress_report'][k]['level']!=null){
                                    indexReference = k;
                                    break;
                                }
                            }
                            let progressReportConfirm2 = "<p class=\"input-confirm-description\">";
                            let _date = new Date(second_response['attendance']['date']);
                            let day = convertDay(_date.getDay());
                            //Getting the names for attendee
                            for (var j = 0 ; j < second_response['attendee'].length-1 ; j++){
                                progressReportConfirm2 += second_response['attendee'][j]['name']+", ";
                            }
                            progressReportConfirm2 += second_response['attendee'][second_response['attendee'].length-1]['name'];
                            //Filling the details of the meeting
                            let progressReportConfirm3 = "<br>"+day+", "+second_response['attendance']['date']+"<br>"+second_response['attendance']['time']+"<br>"+second_response['attendance']['program']+","+response['progress_report'][indexReference]['level']+"<br>"+response['progress_report'][indexReference]['unit']+"<br>"+response['progress_report'][indexReference]['last_exercise']
                            //Filling the scores
                            for (var i = 0 ; i < second_response['attendee'].length ; i++){
                                progressReportConfirm3 += "<br>"+second_response['attendee'][i]['nickname']+"'s Score : "+getAttendeeScore(response['progress_report'],second_response['attendee'][i]['id']);
                            }
                            progressReportConfirm3 +="</p>"
                            let progressReportConfirm4 ="<div class=\"input-confirm-buttons\"><a href= \"\""+response.attendance_id+"\"><button class=\"btn btn-primary d-block input-confirm-button\" type=\"button\">Edit Progress Report</button></a>"+
                        "</div>";
                            let selector = "#attendance-box";
                            progressReportComplete+= progressReportConfirm2 + progressReportConfirm3 + progressReportConfirm4;
                            erase_loading(selector);
                            replaceHtml(selector,progressReportComplete);
                        }
                    })
                }
                else{
                    Swal.fire({
                        icon : 'error',
                        title: 'Oops...',
                        text: 'Request failed. Please reload the page.'
                    });
                }
            },
            error : function(response){
                Swal.fire({
                    icon : 'error',
                    title: 'Oops...',
                    text: 'Input error. Please re-enter the data or reload the page.'
                });
            }
        })
    }

    global.$dc = dc;
})(window);
