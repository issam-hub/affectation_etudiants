// in case of
// {
/* <th style="color: white">ID</th>
        <th style="color: white">Total average</th>
        <th style="color: white">GL</th>
        <th style="color: white">GI</th>
        <th style="color: white">RT</th>
        <th style="color: white">wishes affects</th>
        <th style="color: white">Satisfaction</th> 
    
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
    
    */
// }

async function fire(annee) {
  let re = await fetch("data_handling.php?gl&gi&rt&annee=" + annee);
  let json = await re.json();
  console.log(json);
  let table = document.querySelector("table");
  table.innerHTML = `
      <tr>
        <th style="color: white; height: 45px">Order</th>
        <th style="color: white; height: 45px">Full Name</th>
      </tr>
    `;
  let i = 0;
  for (row of json) {
   if()
   table.innerHTML += `
         <tr>
            <td>${++i}</td>
            <td>${row["nom_prenom"]}</td>
         </tr>`;
  }
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
