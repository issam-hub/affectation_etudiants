var r = document.querySelector(":root");
var x = document.getElementById("so");
var y = document.getElementById("soo");
function setEnrolled(nu) {
  x.textContent = nu;
}
function setUnenrolled(nu) {
  y.textContent = nu;
}

let t, z;
let nuRequest;
(async function () {
  let nuRequest = await fetch("data_handling.php?num_ins");
  console.log(nuRequest);
  let nuJson = await nuRequest.json();
  console.log(nuJson);
  setEnrolled(nuJson["enrolled"]);
  z = nuJson["enrolled"];
  setUnenrolled(nuJson["unenrolled"]);
  t = nuJson["unenrolled"];
  myFunction_set();
})();
function myPercentage() {
  z = parseInt(z);
  t = parseInt(t);
  var s = z + t;
  var enrolled = (z * 100) / s;
  return Math.round(enrolled);
}
// console.log(myPercentage());
// Create a function for getting a variable value
function myFunction_get() {
  // Get the styles (properties and values) for the root
  var rs = getComputedStyle(r);
  var rss = getComputedStyle();

  alert("The value of --blue is: " + rs.getPropertyValue("--enrolled"));
}

function myFunction_set() {
  r.style.setProperty("--enrolled", myPercentage() + "%");
}
