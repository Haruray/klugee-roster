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

    dc.ProgressReportInput = function(){
        var form = $('form')[0]; 
        var formdata = new FormData(form);
        //To do :
        //Implement input empty error message
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
            /*data : {
                "attendance_id" : $("#attendance_id").val(),
                "level" : $("#level").val(),
                "unit" : $("#unit").val(),
                "last_exercise" : $("#last_exercise").val(),
                "score" : $("#score").val(),
                "documentation" : $("#file").val(),
                "note" : $("#note").val(),
                "_token" : $("meta[name='csrf-token']").attr("content")
            },*/
            data : formdata,
            success : function(response){
                if (response.success){
                    replaceHtml("#attendance-box","");
                    let formContainer = document.getElementById("attendance-box");
                    formContainer.classList.remove("attendance-box");
                    show_loading("#attendance-box");
                    $.ajax({
                        url : "/get/attendance/"+response.attendance_id,
                        type:'get',
                        dataType:'json',
                        success:function(second_response){
                            let progressReportConfirm2 = "<p class=\"input-confirm-description\">";
                            let _date = new Date(second_response['attendance']['date']);
                            let day = convertDay(_date.getDay());
                            for (var j = 0 ; j < second_response['attendee'].length-1 ; j++){
                                progressReportConfirm2 += second_response['attendee'][j]['name']+", ";
                            }
                            progressReportConfirm2 += second_response['attendee'][second_response['attendee'].length-1]['name'];
                            let progressReportConfirm3 = "<br>"+day+", "+second_response['attendance']['date']+"<br>"+second_response['attendance']['time']+"<br>"+second_response['attendance']['program']+","+response['progress_report'][0]['level']+"<br>"+response['progress_report'][0]['unit']+"<br>"+response['progress_report'][0]['last_exercise']+"<br>Score : "+response['progress_report'][0]['score']+"</p>"+
                            "<div class=\"input-confirm-buttons\"><a href= \"\""+response.attendance_id+"\"><button class=\"btn btn-primary d-block input-confirm-button\" type=\"button\">Edit Progress Report</button></a>"+
                        "</div>";
                            let selector = "#attendance-box";
                            progressReportComplete+= progressReportConfirm2 + progressReportConfirm3;
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