var r = document.querySelector(":root");
var x = document.getElementById("so");
var y = document.getElementById("soo");
var totalStudent = document.getElementById("total");
function setEnrolled(nu) {
  x.textContent = nu;
}
function setUnenrolled(nu) {
  y.textContent = nu;
}

//totalStudent.textContent = ;

let t, z;
let nuRequest;
(async function () {
  let r = await fetch("data_handling.php?annees_list");
  let json = await r.json();
  let select = document.querySelector("#annees");
  for (row of json) {
    select.innerHTML += `
          <option value="${row["annee"]}">${row["annee"].replace(
      "_",
      "/"
    )}</option>
          `;
  }
  let annees = document.querySelector("#annees");
  fire(annees.value);

  annees.addEventListener("click", function () {
    fire(annees.value);
  });
})();

async function fire(annees) {
  let nuRequest = await fetch("data_handling.php?num_ins&annee=" + annees);
  console.log(nuRequest);
  let nuJson = await nuRequest.json();
  console.log(nuJson);
  setEnrolled(nuJson["enrolled"]);
  z = nuJson["enrolled"];
  setUnenrolled(nuJson["unenrolled"]);
  t = nuJson["unenrolled"];
  myFunction_set();
}

function myPercentage() {
  z = parseInt(z);
  t = parseInt(t);
  var s = z + t;
  totalStudent.textContent = s;
  var enrolled = (z * 100) / s;
  return Math.round(enrolled);
}
// console.log(myPercentage());
// Create a function for getting a variable value
function myFunction_get() {
  // Get the styles (properties and values) for the root
  var rs = getComputedStyle(r);

  alert("The value of --blue is: " + rs.getPropertyValue("--enrolled"));
}

function myFunction_set() {
  r.style.setProperty("--enrolled", myPercentage() + "%");
}
