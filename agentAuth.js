let agentName = document.getElementById("agent_name");
let agentCode = document.getElementById("agent_code");

document.querySelector("form").onsubmit = (e) => {
   e.preventDefault();

   (async function fire() {
      let response = await fetch(
         `session_auth.php?agent_name=${agentName.value}&agent_code=${agentCode.value}`
      );

      let result = await response.text();

      let r = await fetch("session_auth.php");
      console.log(r);
      if (r.statusText == "already_logged_in")
         location.href = "agentChoices.html";

      console.log(result);
      if (result === "NOT_CONNECTED") {
         pERROR = document.querySelector("p#err");
         pERROR.textContent = "* wrong credentials, fix username or password";
         pERROR.style.cssText = "display: block";
      }
   })();
};
