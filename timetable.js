function course_click(str) {
    // Get the course number. (e.g. c1)
    var lastchar = str.charAt(str.length - 1);
    var courseno = "course" + lastchar;
    // Get the checkbox
    var checkBox = document.getElementById(courseno);

    // Get the output text
    var text = document.getElementById(str).textContent;
    var courseCode = text.split(" ")[0];
    var duration = text.split(" ")[1];
    var weekday = text.split(" ")[2]; // Monday
    var weekday2 = weekday.substring(0, 3).toLowerCase(); // mon
    var start_time = duration.split("-")[0];
    //var end_time = duration.split("-")[1];
    var start_hour = start_time.split(":")[0];

    var week_id = weekday2 + start_hour;
    //alert(week_id)

    // If the checkbox is checked, display the output text
    if (checkBox.checked == true){
      document.getElementById(week_id).className = "accent-pink-gradient";
      document.getElementById(week_id).innerHTML = courseCode;
    } else {
      document.getElementById(week_id).className = "";
      document.getElementById(week_id).innerHTML = "";
    }

  }
