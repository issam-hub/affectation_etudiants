async function fire() {
  let re = await fetch("data_handling.php?stats");
  let json = await re.json();
  console.log(json);
  let table = document.querySelector("table");
  let i = 0;
  for (row of json) {
    table.innerHTML += `
       <tr>
       <td>${++i}</td>
         <td>${row["nom"]} ${row["prenom"]}</td>
         <td>${row["matricule"]}</td>
         <td>${row["mgc"]}</td>
         <td class='gl'>${row["ordre_gl"]}</td>
         <td class='gi'>${row["ordre_gi"]}</td>
         <td class='rt'>${row["ordre_rt"]}</td>
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
        console.log(
          document.getElementsByClassName(choices[j].toLowerCase())[i - 1]
        );
        document.getElementsByClassName(choices[j].toLowerCase())[
          i - 1
        ].style.backgroundColor = "green";
        break;
      } else
        document.getElementsByClassName(choices[j].toLowerCase())[
          i - 1
        ].style.backgroundColor = "red";
    }
  }
}

fire();
//  <td>${row["voeu choisit"]}</td>
