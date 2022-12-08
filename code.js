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

let popUp = document.querySelector(".popUp .ctnt p");
let popUpExit = document.querySelector(".popUp img");
let matricule = document.getElementById("matricule");
let code = document.getElementById("code");
let gl_choix = document.getElementById("gl_choix");
let gi_choix = document.getElementById("gi_choix");
let rt_choix = document.getElementById("rt_choix");

document.querySelector("form").onsubmit = (e) => {
   e.preventDefault();

   (async function getData() {
      let response = await fetch(
         `choose_speciality.php?matricule=${matricule.value}&code=${code.value}` +
            `&gl_choix=${gl_choix.value}` +
            `&gi_choix=${gi_choix.value}` +
            `&rt_choix=${rt_choix.value}`
      );
      let result = await response.json();
      console.log(result);
      if (result.status === "CONNECTED") {
         let ul = document.createElement("ul");
         let li = document.querySelector("li");
         popUp.textContent = result.status;
         // popUp.textContent = result.status;
      } else if (result.status === "NOT_CONNECTED") {
         let p = document.createElement("p");
         p.style.cssText = "color: red; font-weight: bold";
         document.querySelector("form").append(p);
      }
   })();
   setTimeout(() => {
      document.querySelector(".popUp").style.cssText = "display: block";
      document.querySelector(".popUp").style.cssText =
         "animation: pop 0.5s forwards;";
   }, 300);
};

popUpExit.addEventListener("click", () => {
   document.querySelector(".popUp").style.cssText = "display: none; opacity: 0";
});
