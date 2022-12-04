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
