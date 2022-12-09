async function fire() {
  let re = await fetch("data_handling.php?stats");
  let json = await re.json();
  console.log(json);
  let table = document.querySelector("table");
  let i = 0;
  let nChoiceAffected = [0, 0, 0];
  for (row of json) {
    table.innerHTML += `
       <tr>
       <td>${++i}</td>
         <td>${row["nom"]} ${row["prenom"]}</td>
         <td>${row["matricule"]}</td>
         <td>${row["mgc"]}</td>
         <td class='gl' style='font-weight: bold;color: gray'>${row["ordre_gl"]}</td>
         <td class='gi' style='font-weight: bold;color: gray'>${row["ordre_gi"]}</td>
         <td class='rt' style='font-weight: bold;color: gray'>${row["ordre_rt"]}</td>
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


  /* End affecting number of students who get their nth choice */

}

fire();

