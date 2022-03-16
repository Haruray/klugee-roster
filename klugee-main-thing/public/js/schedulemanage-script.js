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
    
    let headingHTML = "<div><h2 class=\"text-left flash animated page-heading green\" style=\"margin: 30px 0 0 0px;color: #54dee4;\">Teacher Something's Schedule</h2>" ;
    let tableheadHTML = "<div class=\"table-responsive\">"+
    "<table class=\"table\">"+
        "<thead>"+
            "<tr><th>Student&nbsp;</th><th>Time</th><th>Location</th><th>Class Type</th><th>Program</th><th>Subject</th><th>Action</th></tr>"+
        "</thead>";
    let tablebodyHTML1 = "<tbody>";
    let tablebodyHTML2="</tbody></table></div>";
    let buttonHTML="<a class=\"d-inline-block\" href=\"#\"  data-toggle=\"modal\" data-target=\"#formModal\" style=\"height: 50px;margin: auto;\">"+
    "<div class=\"attendance-plus\"><i class=\"fa fa-plus\"></i></div>"+
"</a></div>"

    let rowspancount = function(array, id){
        let count=0;
        for (i = 0 ; i < array.length; i++){
            if (array[i].id == id){
                count++;
            }
        }
        return count;
    }

    dc.ScheduleSearch = function(){
        let teacher_id = document.getElementById("teacher-name").value;
        if (teacher_id===""){
            //warning
            return;
        }
        $.ajax({
            url:'/get/schedule/'+teacher_id,
            type : 'get',
            dataType : 'json',
            success:function(response){
                if (response.success){
                    console.log(response);
                    let allHTML = headingHTML;
                    let day = response.schedule[0].day;
                    let name="";
                    let begin = "";
                    let location = "";
                    let program = "";
                    let subject = "";
                    let rowspan = 1;
                    for (i = 0 ; i < response.schedule.length; i++){
                        if (day == response.schedule[i].day){
                            name=response.schedule[i].name;
                            begin = response.schedule[i].begin;
                            location = response.schedule[i].classroom_type;
                            program = response.schedule[i].program;
                            subject = response.schedule[i].subject;
                            rowspan = rowspancount(response.schedule,response.schedule[i].id);
                            allHTML += "<h3 class=\"text-left page-heading\" style=\"margin: 10px 0 20px 0;\">" + day + "</h3>"
                            allHTML += tableheadHTML + tablebodyHTML1;
                            allHTML+= "<tr><td>"+name+"</td>"
                            allHTML+= "<td rowspan=\""+rowspan+"\">"+begin+"</td>"
                            allHTML+= "<td rowspan=\""+rowspan+"\">"+location+"</td>"
                            allHTML+= "<td rowspan=\""+rowspan+"\">"+program+"</td>"
                            allHTML+= "<td rowspan=\""+rowspan+"\">"+subject+"</td></tr>"
                            allHTML += "<td rowspan=\""+rowspan+"\">bruh</td></tr>"
                            if (rowspan > 1){
                                for (j = i ; j < rowspan ; j++){
                                    allHTML += "<tr><td>"+response.schedule[j].name+"</td></tr>"
                                }
                            }
                            allHTML += tablebodyHTML2 + buttonHTML;
                        }
                        else{
                            day = response.schedule[i].day;
                            i = i-1;
                        }
                        
                    }
                    console.log(allHTML);
                    insertHtml("#main-content",allHTML);
                }
            }
        })
    }

    global.$dc = dc;
})(window);