document.addEventListener("DOMContentLoaded", (e) => {

  let html_data = document.getElementById('component_html_data');
  let save = document.getElementById('component_save');
  let x = document.forms;

  html_data.addEventListener('change', (e) => {
    save.disabled = false;
    if(!IsValidJSONString(e.target.value)){
      save.disabled = true;
      html_data.setCustomValidity("lalalalala");
      // x[0].submit();
      // console.log(x);
    }
  });

  html_data.addEventListener('invalid', (e) => {
    console.log(e);
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