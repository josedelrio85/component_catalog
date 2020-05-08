/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';
import 'bootstrap';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

document.addEventListener("DOMContentLoaded", function (event) {

    // Copy to clipboard
    document.querySelectorAll('.copy-clipboard').forEach(div => {
        div.addEventListener('click', function() {
            navigator.clipboard.writeText(this.nextSibling.innerText)
            .then(() => {
                // Text copied to clipboard
                //console.log('Text copied to clipboard');
            })
            .catch(err => {
              // If the user denies clipboard permissions:
              console.error('Could not copy text: ', err);
            });

        });
    });

});

//Search on press search button
//Note: now it searchs in all the component html block. Maybe should change it to search just in component title (or a more descriptive field like a description or keywords)?
//  document.addEventListener('click', function (event) {
//     if (event.target.matches('#submit-search')) {
//         event.preventDefault();
//         var termsearch = document.getElementById("term-search").value;
//         if(termsearch && termsearch.length <= 3) return;
//         //Get all components
//         var components = document.getElementsByClassName("single-component");

//         //Loop through components
//         for (var i = 0; i < components.length; i++) {
//             if(!termsearch || components.item(i).innerHTML.search(termsearch) != -1) {
//                 //Show element if string is in its content
//                 components.item(i).classList.remove('d-none');
//             } else {
//                 //Hide element if string is not in its content
//                 components.item(i).classList.add('d-none');
//             }
//         }
//     }
//   }, false);

//Search on keyup event
//Note: now it searchs in all the component html block. Maybe should change it to search just in component title (or a more descriptive field like a description or keywords)?
document.addEventListener('keyup', function (event) {
    if (event.target.matches('#term-search')) {
        event.preventDefault();
        var termsearch = document.getElementById("term-search").value;
        if(termsearch && termsearch.length <= 3) return;
        //Get all components
        var components = document.getElementsByClassName("single-component");

        //Loop through components
        for (var i = 0; i < components.length; i++) {
            if(!termsearch || components.item(i).innerHTML.search(termsearch) != -1) {
                //Show element if string is in its content
                components.item(i).classList.remove('d-none');
            } else {
                //Hide element if string is not in its content
                components.item(i).classList.add('d-none');
            }
        }
    }
}, false);