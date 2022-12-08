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
         let ul = document.createElement("ol");
         ul.setAttribute("type", "1");
         let li1 = document.querySelector("li");
         let li2 = document.querySelector("li");
         let li3 = document.querySelector("li");

         for (let res in result) {
            if (result[res] === "1") {
               li1.textContent = result[res];
            } else if (result[res] === "2") {
               li2.textContent = result[res];
            } else if (result[res] === "3") {
               li3.textContent = result[res];
            }
         }
         ul.append(li1, li2, li3);
         popUp.textContent = "you successfully choosed !";
         document.querySelector(".popUp .ctnt").append(ul);
         // popUp.textContent = result.status;
      } else if (result.status === "NOT_CONNECTED") {
         let p = document.createElement("p");
         p.textContent = result.p.style.cssText =
            "color: red; font-weight: bold";
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
