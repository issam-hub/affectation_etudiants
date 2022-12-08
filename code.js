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
      // let ul = document.createElement("ol");
      // ul.setAttribute("type", "1");
      // ul.style.cssText = "color: white";
      let li1 = document.querySelector("ol .one");
      let li2 = document.querySelector("ol .two");
      let li3 = document.querySelector("ol .three");

      for (let res in result) {
        if (result[res] === "1") {
          li1.textContent = res.substring(res.length - 2).toUpperCase();
        } else if (result[res] === "2") {
          li2.textContent = res.substring(res.length - 2).toUpperCase();
        } else if (result[res] === "3") {
          li3.textContent = res.substring(res.length - 2).toUpperCase();
        }
      }
      // ul.append(li1, li2, li3);
      if (result.deja_choisit === 0) {
        popUp.textContent = "you successfully choosed !";
        popUp.classList.remove("already");

        popUp.classList.add("firstTime");
      } else if (result.deja_choisit === 1) {
        popUp.textContent = "you already choosed !";
        popUp.classList.remove("firstTime");

        popUp.classList.add("already");
      }
      // popUp.after(ul);
      // popUp.textContent = result.status;
      setTimeout(() => {
        document.querySelector(".popUp").style.cssText = "display: block";
        document.querySelector(".popUp").style.cssText =
          "animation: pop 0.5s forwards;";
      }, 300);
    } else if (result.status === "NOT_CONNECTED") {
      let p = document.createElement("p");
      p.textContent = "* wrong credentials, fix username or password";
      p.style.cssText = "color: red; font-weight: bold";
      document.querySelector("form").append(p);
    }
  })();
};

popUpExit.addEventListener("click", () => {
  document.querySelector(".popUp").style.cssText = "display: none; opacity: 0";
  popUp.classList.length = 0;
});
