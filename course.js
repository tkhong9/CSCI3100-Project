function addCourse(user_id, course_id) {
  var urlString ="userID="+user_id+"&courseID="+course_id;
  //alert(urlString);

  $.ajax
  ({
  url: "ajax.php",
  type : "POST",
  cache : false,
  data : urlString,
  success: function(response)
  {
  alert(response);
  }
  });

}

