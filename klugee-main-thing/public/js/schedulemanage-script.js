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

    let headingHTML1 = "<div><h2 class=\"text-left flash animated page-heading green\" style=\"margin: 30px 0 0 0px;color: #54dee4;\">" ;
    let headingHTML2 = "</h2>";
    let tableheadHTML = "<div class=\"table-responsive\">"+
    "<table class=\"table\">"+
        "<thead>"+
            "<tr><th>Student&nbsp;</th><th>Time</th><th>Location</th><th>Class Type</th><th>Program</th><th>Subject</th><th>Action</th></tr>"+
        "</thead>";
    let tablebodyHTML1 = "<tbody>";
    let tablebodyHTML2="</tbody></table></div>";
    let buttonHTML="<a class=\"d-inline-block\" href=\"#\"  data-toggle=\"modal\" data-target=\"#formModal\" style=\"height: 50px;margin: auto;margin-top:30px\">"+
    "<div class=\"attendance-plus\"><i class=\"fa fa-plus\"></i></div>"+
"</a></div>"

    let rowspancount = function(array, id){
        let count=0;
        for (k = 0 ; k < array.length; k++){
            if (parseInt(array[k].id) == parseInt(id)){
                count++;
            }
        }
        return count;
    }

    dc.ScheduleSearch = function(){
        let teacher_id = document.getElementById("teacher-name").value;
        let input_hidden = document.getElementById("teacher-id").value = teacher_id;
        if (teacher_id===""){
            //warning
            return;
        }
        $.ajax({
            url:'/get/schedule/'+teacher_id,
            type : 'get',
            dataType : 'json',
            success:function(response){
                if (response.success && response.schedule.length>0){
                    console.log(response);
                    let allHTML = headingHTML1 + "Teacher "+ response.schedule[0].nickname +"'s Schedule" + headingHTML2;
                    let day = response.schedule[0].day;
                    let dayrepeat = false;
                    //read data on seperated variables
                    let name="";
                    let begin = "";
                    let location = "";
                    let students = "";
                    let program = "";
                    let subject = "";
                    let rowspan = 1;
                    for (i = 0 ; i < response.schedule.length; i++){

                        allHTML += "<h3 class=\"text-left page-heading\" style=\"margin: 10px 0 20px 0;\">" + day + "</h3>"
                        allHTML += tableheadHTML + tablebodyHTML1;

                        console.log(response.schedule[i].day);
                        while (day == response.schedule[i].day){
                            name=response.schedule[i].name;
                            begin = response.schedule[i].begin;
                            location = response.schedule[i].classroom_type;
                            program = response.schedule[i].program;
                            students = response.schedule[i].classroom_students;
                            subject = response.schedule[i].subject;

                            rowspan = rowspancount(response.schedule,response.schedule[i].id);

                            allHTML+= "<tr><td>"+name+"</td>"
                            allHTML+= "<td rowspan=\""+rowspan+"\">"+begin+"</td>"
                            allHTML+= "<td rowspan=\""+rowspan+"\">"+location+"</td>"
                            allHTML+= "<td rowspan=\""+rowspan+"\">"+students+"</td>"
                            allHTML+= "<td rowspan=\""+rowspan+"\">"+program+"</td>"
                            allHTML+= "<td rowspan=\""+rowspan+"\">"+subject+"</td>"
                            allHTML += "<td rowspan=\""+rowspan+"\">"+"<div class=\"btn-group\" role=\"group\"><a class=\"btn btn-warning\" role=\"button\">Edit</a><a class=\"btn btn-danger\" role=\"button\">Delete</a></div>"+"</td></tr>"

                            //print name
                            if (parseInt(rowspan) > 1){
                                for (j = i+1 ; j < (i+rowspan) ; j++){
                                    allHTML += "<tr><td>"+response.schedule[j].name+"</td></tr>"
                                }
                            }
                            i+=rowspan;
                            if (i >= response.schedule.length){break;}
                        }
                        if (i < response.schedule.length){
                            day = response.schedule[i].day;
                            i--;
                        }

                        allHTML += tablebodyHTML2;
                    }
                    console.log(allHTML);
                    allHTML+=buttonHTML;
                    replaceHtml("#schedules",allHTML);
                }
                else if (response.schedule.length == 0) {
                    let allHTML = headingHTML1 + "Schedule not found" + headingHTML2;
                    allHTML+=buttonHTML;
                    replaceHtml("#schedules",allHTML);
                }
                else{
                    //swal thingy
                }
            }
        })
    }

    dc.ScheduleAdd = function(){
        var form = $('form')[1];
        var formdata = new FormData(form);

        $.ajax({
            url : '/schedule-admin/manage/add',
            type : 'post',
            dataType : 'JSON',
            cache : false,
            contentType : false,
            processData : false,
            data : formdata,
            success : function(response){
                if (response.success){
                    //swal
                    console.log("success");
                    $('#formModal').modal('toggle');
                    dc.ScheduleSearch();
                }
                else{
                    //swal
                }
            }
        });
    }

    global.$dc = dc;
})(window);
