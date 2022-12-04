async function fire() {
   let re = await fetch("data_handling.php?stats");
   let json = await re.json();
   console.log(json);
   let table = document.querySelector("table");
   for (row of json) {
      table.innerHTML += `
       <tr>
                <td>${row["nom"]} ${row["prenom"]}</td>
                <td>${row["matricule"]}</td>
                <td>${row["mgc"]}</td>
                <td>${row["voeu choisit"]}</td>
                <td>${row["voeu affecte"]}</td>
                <td>${row["satisfaction"]}</td>
                
                </tr>
       `;
   }
}

fire();
