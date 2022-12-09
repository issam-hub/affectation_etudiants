async function fire(annee) {
  let re = await fetch("data_handling.php?stats&annee=" + annee);
  let json = await re.json();
  console.log(json);
  let table = document.querySelector("table");
  table.innerHTML = `
    <tr>
      <th style="color: white; height: 45px">Order</th>
      <th style="color: white; height: 45px">Full Name</th>
      <th style="color: white">ID</th>
      <th style="color: white">Total average</th>
      <th style="color: white">GL</th>
      <th style="color: white">GI</th>
      <th style="color: white">RT</th>
      <th style="color: white">wishes affects</th>
      <th style="color: white">Satisfaction</th>
    </tr>
  `;
  let i = 0;
  let nChoiceAffected = [0, 0, 0];
  for (row of json) {
    table.innerHTML += `
       <tr>
       <td>${++i}</td>
         <td>${row["nom"]} ${row["prenom"]}</td>
         <td>${row["matricule"]}</td>
         <td>${row["mgc"]}</td>
         <td class='gl' style='font-weight: bold;color: gray'>${
           row["ordre_gl"]
         }</td>
         <td class='gi' style='font-weight: bold;color: gray'>${
           row["ordre_gi"]
         }</td>
         <td class='rt' style='font-weight: bold;color: gray'>${
           row["ordre_rt"]
         }</td>
         <td class='final'>${row["voeu affecte"]}</td>
         <td class="sat">${row["satisfaction"]}</td>    
      </tr>`;
    let choices = [];
    choices[row["ordre_gl"]] = "GL";
    choices[row["ordre_gi"]] = "GI";
    choices[row["ordre_rt"]] = "RT";
    let final = document.getElementsByClassName("final")[i - 1].textContent;
    console.log(final, choices);
    for (let j = 1; j < choices.length; j++) {
      if (choices[j] == final) {
        nChoiceAffected[j - 1]++;
        console.log(
          document.getElementsByClassName(choices[j].toLowerCase())[i - 1]
        );
        document.getElementsByClassName(choices[j].toLowerCase())[
          i - 1
        ].style.backgroundColor = "green";
        document.getElementsByClassName(choices[j].toLowerCase())[
          i - 1
        ].style.color = "white";
        break;
      } else
        document.getElementsByClassName(choices[j].toLowerCase())[
          i - 1
        ].style.backgroundColor = "red";
      document.getElementsByClassName(choices[j].toLowerCase())[
        i - 1
      ].style.color = "white";
    }
  }
  console.log("numbers :", nChoiceAffected);
  /* Start affecting number of students who get their nth choice */
  let firstChoice = nChoiceAffected[0];
  let secondChoice = nChoiceAffected[1];
  let thirdChoice = nChoiceAffected[2];
  let totale = firstChoice+secondChoice+thirdChoice;
  var totalChoices = document.getElementById("total");
  totalChoices.textContent = totale;
  var r = document.querySelector(":root");
  var rr =document.querySelector(":root");
 
  function myPercentage(choice,total) {
    var percentageChoice = (choice * 100) / total;
    return Math.round(percentageChoice);
  
  }
  // console.log(myPercentage());
  // Create a function for getting a variable value

  function setSecondChoice() {
    r.style.setProperty("--second-choice", myPercentage(secondChoice,totale) + "%");
  }
  function setThirdChoice() {
    rr.style.setProperty("--third-choice", myPercentage(thirdChoice,totale) + "%");
  }

  setSecondChoice();
  setThirdChoice();
  /* End affecting number of students who get their nth choice */
}

(async function () {
  let r = await fetch("data_handling.php?annees_list");
  let json = await r.json();
  let select = document.querySelector("#annees");
  console.log("list of years :", json);
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
