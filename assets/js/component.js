document.addEventListener("DOMContentLoaded", (e) => {
  let pathfield = document.getElementById("component_path");
  let html_template = document.getElementById("component_html_template");
  let styles_template = document.getElementById("component_styles_template");
  pathfield.addEventListener('change', (e) => {
    let prefix = "components/";
    let sufix = ".html.twig";
    let sufix2 = ".scss";
    let pathvalue = e.target.value + "/" + e.target.value;
    html_template.value = prefix + pathvalue + sufix;
    styles_template.value = prefix + pathvalue + sufix2;
  });
});