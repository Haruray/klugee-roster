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

    //Counts and finds how many student input form are there in the page
    let studentsCount = 1;

    let countCurrentStudentForms = function(){
        //Counting...
        let stop = false;
        let searchID = "";
        while (!stop){
            searchID = "student"+studentsCount;
            let studentForm = document.getElementById(searchID);
            if (studentForm==null){
                stop = true;
            }
            else{
                studentsCount++
            }
        }
        return studentsCount;
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

    dc.addStudentInput = function(){
        //Add new student input form. Get students data from ajax
        let studentFormsCount = countCurrentStudentForms()
        let studentInputForm1 = "<div class=\"container\">" +
    "<div class=\"form-row\">"+
        "<div class=\"col-11 col-sm-11 col-md-11 col-lg-11 col-xl-11\">"+
            "<div class=\"attendance-input-div\">"+
                "<div class=\"attendance-icon align-middle\"><i class=\"fa fa-user\"></i></div>"+
                    "<select class=\"select required js-placeholder-student js-example-basic-single form-control attendance-input attendance-input-student\" name=\"student"+studentFormsCount+"\" id=\"student"+studentFormsCount+"\">"+
                        "<option></option>";

        let studentInputForm2 = "</select>"+
                        "</div>"+
                "</div>"+
                "<div class=\"col-1 col-sm-1 col-md-1 col-lg-1 text-center align-self-center\" id=\"student-attend-input-check-"+studentFormsCount+"\">"+
                "<a onclick=\"$dc.MarkStudent(\'#student-attend-input-check-"+studentFormsCount+"\')\">" +
                "<div class=\"attendance-confirm align-bottom\"><i class=\"fa fa-check\"></i></div>" +
                "</a>" +
                "<input type=\"hidden\" name=\"student-attend-"+studentFormsCount+"\" id=\"student-attend-"+studentFormsCount+"\" value=\"no\">" +
                "</div>"+
            "</div>"+
        "</div>";

        studentInputFormComplete = studentInputForm1;
        $.ajax({
            url : '/get/student',
            type : 'get',
            dataType : 'json',
            success : function(response){
                if (response.length == studentFormsCount-1){
                    alert('Exceeding the number of students');
                }
                else{
                    for (var i = 0 ; i < response.length ; i++){
                        studentInputFormComplete += "<option value=\""+response[i]["name"]+"\">"+response[i]["name"]+"</option>";
                    }
                    studentInputFormComplete+=studentInputForm2;
                    insertHtml("#attendance-form-box",studentInputFormComplete)

                    let studentFormSelector = "#student"+studentFormsCount;
                    $(studentFormSelector).select2({
                        placeholder: "Select a student",
                        allowClear: true
                    });
                }
            },
            error : function(){
                alert("Data request failed. Please reload the page.");

            }
        });

    }

    dc.MarkStudent = function(selector){
        //mark student as present
        //replacing the checkmark and add invis input
        let studentFormsCount = selector.slice(-1);
        let studentCheckDoneHTML = "<a onclick=\"$dc.UnmarkStudent(\'"+selector+"\')\">" +
        "<div class=\"attendance-confirm-yes align-bottom\"><i class=\"fa fa-check\"></i></div>" +
        "</a>"+
        "<input type=\"hidden\" name=\"student-attend-"+studentFormsCount+"\" id=\"student-attend-"+studentFormsCount+"\" value=\"yes\">";

        replaceHtml(selector,studentCheckDoneHTML);
    }

    dc.UnmarkStudent = function(selector){
        //mark student as absent
        //replacing the checkmark and add invis input
        let studentFormsCount = selector.slice(-1);
        let studentCheckUndoneHTML = "<a onclick=\"$dc.MarkStudent(\'"+selector+"\')\">" +
        "<div class=\"attendance-confirm align-bottom\"><i class=\"fa fa-check\"></i></div>" +
        "</a>"+
        "<input type=\"hidden\" name=\"student-attend-"+studentFormsCount+"\" id=\"student-attend-"+studentFormsCount+"\" value=\"no\">";
        replaceHtml(selector,studentCheckUndoneHTML);
    }


    dc.AttendanceInput = function(){
        //TO do :
        // - check student duplicate so the attendanceConfirm dont have student duplicate

        var form = $('form')[1];
        console.log(form);
        var formdata = new FormData(form);
        console.log(formdata);

        let attendanceConfirm1 = "<div class=\"tada animated attendance-box\">"+
        "<h3 class=\"page-sub-heading\">Student's attendance is&nbsp;<span class=\"yellow\">recorded</span></h3>"+
        "<h1 class=\"page-sub-heading\"><i class=\"fa fa-check swing animated infinite input-confirm-check\"></i></h1>";

        let attendanceConfirmComplete = attendanceConfirm1;
        $.ajax({
            url : '/attendance/input-process',
            type : 'post',
            dataType : 'JSON',
            cache : false,
            contentType : false,
            processData : false,
            /*data : {
                "date" : $("#date").val(),
                "hour" : $("#attendance-form-hour").val(),
                "program" : $("#attendance-form-program").val(),
                "location" : $("#attendance-form-location").val(),
                "class-type" : $("#attendance-form-classtype").val(),
                "student1": studentsData[0],
                "student-attend-1" : studentsPresent[0],
                "student2": studentsData[1],
                "student-attend-2" : studentsPresent[1],
                "student3": studentsData[2],
                "student-attend-3" : studentsPresent[2],
                "_token" : $("meta[name='csrf-token']").attr("content")
            },*/
            data: formdata,
            success : function(response){
                replaceHtml("#attendance-box","");
                let formContainer = document.getElementById("attendance-box");
                formContainer.classList.remove("attendance-box");
                show_loading("#attendance-box");
                if (response.success){
                    $.ajax({
                        url : "/get/attendance/"+response.attendance_id,
                        type:'get',
                        dataType:'json',
                        success:function(second_response){
                            let attendanceConfirm2 = "<p class=\"input-confirm-description\">";
                            let _date = new Date(second_response['attendance']['date']);
                            let day = convertDay(_date.getDay());
                            for (var j = 0 ; j < second_response['attendee'].length-1 ; j++){
                                attendanceConfirm2 += second_response['attendee'][j]['name']+", ";
                            }
                            attendanceConfirm2 += second_response['attendee'][second_response['attendee'].length-1]['name'];
                            let attendanceConfirm3 = "<br>"+day+","+second_response['attendance']['date']+"<br>"+second_response['attendance']['time']+"<br>"+second_response['attendance']['program']+"<br>"+second_response['attendance']['location']+"</p>"+
                            "<div class=\"input-confirm-buttons\"><a href= \"/attendance/progress-report/"+response.attendance_id+"\"><button class=\"btn btn-primary d-block input-confirm-button\" type=\"button\">Progress Report</button></a><button class=\"btn btn-primary d-block input-confirm-button\" type=\"button\">Edit Attendance</button></div>"+
                        "</div>";
                            let selector = "#attendance-box";
                            attendanceConfirmComplete+= attendanceConfirm2 + attendanceConfirm3;
                            erase_loading(selector);
                            replaceHtml(selector,attendanceConfirmComplete);
                        }
                    })
                }
                else{
                    //alert(response.message);
                    Swal.fire({
                        icon : 'error',
                        title: 'Oops...',
                        text: 'Request failed. Please reload the page.'
                    });

                }
            },
            error : function(response){
                //alert(response.message);
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
