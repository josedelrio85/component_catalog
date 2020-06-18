document.addEventListener("DOMContentLoaded", (e) => {

  let html_data = document.getElementById('component_html_data');
  let save = document.getElementById('component_save');
  // let x = document.forms;

  html_data.addEventListener('focusout', (e) => {
    save.disabled = false;
    if(!IsValidJSONString(e.target.value)){
      save.disabled = true;
      html_data.setCustomValidity("Not a valid JSON format.");
    }else{
      html_data.setCustomValidity("");
    }
  });

  function IsValidJSONString(str) {
    try {
      JSON.parse(str);
    } catch (e) {
      return false;
    }
    return true;
  }
});