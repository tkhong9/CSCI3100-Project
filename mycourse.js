function addCourse(user_id, course_id) {
  var time = document.getElementById('Time'+course_id).innerHTML;
  var newtime = time.split(': ')[1];
  var newtime2 = newtime.split(', ');
  var strTime = newtime2[0].split('-')[0];
  var endTime = newtime2[0].split('-')[1];
  var day = newtime2[1];

  var urlString ="userID="+user_id+"&courseID="+course_id+"&strTime="+strTime+"&endTime="+endTime+"&day="+day;

  $.ajax
  ({
  url: "ajax_add.php",
  type : "POST",
  cache : false,
  data : urlString,
  success: function(response)
  {
  alert(response);
  }
  });
 
 }

function deleteCourse(user_id, course_id) {
  var urlString ="userID="+user_id+"&courseID="+course_id;

  $.ajax
  ({
  url: "ajax_delete.php",
  type : "POST",
  cache : false,
  data : urlString,
  success: function(response)
  {
  alert(response);
  }
  });

  // Reload the page after ajax success
  $(document).ajaxStop(function(){
    window.location.reload();
  });
}