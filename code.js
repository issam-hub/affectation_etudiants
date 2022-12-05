let choixs = document.querySelectorAll("select");

choixs.forEach((choix) => {
   choix.addEventListener("change", (e) => {
      choixs.forEach((chx) => {
         if (
            chx.previousElementSibling.textContent !==
            e.target.previousElementSibling.textContent
         ) {
            document
               .querySelectorAll("." + chx.className + " > option")
               .forEach((opt) => {
                  if (e.target.value === opt.getAttribute("value")) {
                     opt.remove();
                  }
               });
         }
      });
   });
});

document.querySelector("form").onsubmit = (e) => {
   e.preventDefault();
   // let popUp = document.querySelector(".popUp .ctnt p");
   // let popUpExit = document.querySelector(".popUp img");
   // let matricule = document.getElementById("matricule");
   // let code = document.getElementById("code");

   (async function getData() {
      let response = await fetch(
         `data_handling.php?matricule=${matricule.value}&code=${code.value}`
      );
      let result = await response.json();
      console.log(result);
      // popUp.textContent = result;
   })();
   // setTimeout(() => {
   //    document.querySelector(".popUp").style.cssText = "display: block";
   //    document.querySelector(".popUp").style.cssText =
   //       "animation: pop 0.5s forwards;";
   // }, 300);
};
