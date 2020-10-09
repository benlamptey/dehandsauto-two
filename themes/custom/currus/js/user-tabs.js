// A function to tab between the content on the user page

function openProfileDisplay(evt, contentToggle){
  var i, togglecontent, togglelinks;
  tabcontent = document.getElementsByClassName("togglecontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("togglelinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(contentToggle).style.display = "block";
  evt.currentTarget.className += " active";
}  
 
  // Get an id to the toggle element, to open it by default
  document.getElementById("defaultActiveToggle").click();
