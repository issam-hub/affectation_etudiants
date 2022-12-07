var r = document.querySelector(':root');
var x = document.getElementById('so');
var y = document.getElementById('soo');
function setEnrolled(nu){
  y.innerHTML = nu;
}
function setUnenrolled(nu){
  x.innerHTML = nu;
}


function myPercentage(){
  t = parseInt(t);
  z = parseInt(z);
  var s = z+t;
  var enrolled = z*100/s;
  var percer = '%'
  var perc =enrolled.toString() + percer;
  return perc;
}
// Create a function for getting a variable value
function myFunction_get() {
  // Get the styles (properties and values) for the root
  var rs = getComputedStyle(r);
  var rss = getComputedStyle();
  
  alert("The value of --blue is: " + rs.getPropertyValue('--enrolled'));
}


function myFunction_set() {
  
  r.style.setProperty('--enrolled', myPercentage());
}
myFunction_set();