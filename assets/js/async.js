import { landingCommander } from '../../node_modules/@bysidecar/landing_commander/dist/main';
import  './functions.js';

document.addEventListener("DOMContentLoaded", (e) => {

  let components = document.querySelectorAll("[id^='comp-']");
  components.forEach((cv, ci, listObj) => {
    cv.addEventListener('click', (event) => {
      let compname = event.target.getAttribute("data-name");

      getDataComponent(compname)
      .then((result) => {
        console.log(result);
        if(result.success) {
          let comp_async = document.getElementById('comp_async');
          comp_async.innerHTML = null;
          comp_async.insertAdjacentHTML('beforeend', result.template);
          window.addCopyToClipboardEvent();
          document.querySelector(".edit-container-block").classList.add('d-none');
        }
      })
      .catch((error) => { console.log(error); });

    });
  });
});


function getDataComponent(compname) {
  const urlEndPoint = '/data-component';
  let params = {
    compname: compname,
  }
  return new Promise((resolve, reject) => {
    landingCommander.makePostRequestFormData(params, urlEndPoint)
    .then((result) => {
      resolve(result);
    })
    .catch((error) => {reject(error);})
  });
}