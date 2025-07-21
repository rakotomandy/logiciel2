document.addEventListener("DOMContentLoaded", function () {
  // Password toggle functionality
  let eye = document.querySelector("#eye");
  eye.addEventListener("click", function () {
    const pwd = document.getElementById("pwd");
    const toggleEye = document.getElementById("toggleEye");

    if (pwd.type === "password") {
      pwd.type = "text";
      toggleEye.classList.remove("fa-eye");
      toggleEye.classList.add("fa-eye-slash");
    } else {
      pwd.type = "password";
      toggleEye.classList.remove("fa-eye-slash");
      toggleEye.classList.add("fa-eye");
    }
  });

  // Create Dark Mode Button
  const button = document.createElement("button");
  button.textContent = "DARK MODE";

  // Style the button
  button.style.padding = "10px";
  button.style.borderRadius = "20px";
  button.style.border = "none";
  button.style.cursor = "pointer";
  button.style.zIndex = "2";
  button.style.position = "fixed";
  button.style.top = "10px";
  button.style.right = "10px";
  button.style.fontFamily = "arial";
  button.classList.add("mode");

  document.body.appendChild(button);

  // Apply saved mode from localStorage
  let savedMode = localStorage.getItem("mode");
  let darkmode = savedMode === "dark";

  function applyMode(isDark) {
    document.body.style.backgroundColor = isDark ? "black" : "white";
    document.body.style.color = isDark ? "white" : "black";
    document.querySelector(".mode").style.backgroundColor = isDark ? "white" : "black";
    document.querySelector(".mode").style.color = isDark ? "black" : "white";
    document.querySelector(".mode").innerText= isDark ? "LIGHT MODE" : "DARK MODE";
    document.querySelector(".eye").style.color="black" ;
  }

  applyMode(darkmode); // Apply on page load

  // Toggle dark mode on button click
  button.addEventListener("click", function () {
    darkmode = !darkmode;
    applyMode(darkmode);
    localStorage.setItem("mode", darkmode ? "dark" : "light");
  });

  /* document.addEventListener("keydown",function(event){
    event.key==="ArrowDown" ? 
  }) */
});
