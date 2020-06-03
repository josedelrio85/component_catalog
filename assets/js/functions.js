/*
 * FUNCTIONS
*/

/* 
 * Add to clipboard event
*/
window.addCopyToClipboardEvent = function addCopyToClipboardEvent() {
    document.querySelectorAll('.copy-clipboard').forEach(div => {
        div.addEventListener('click', function() {

            navigator.clipboard.writeText(this.nextSibling.innerText)
            .then(() => {
                // Text copied to clipboard
                // console.log('Text copied to clipboard');
            })
            .catch(err => {
            // If the user denies clipboard permissions:
            console.error('Could not copy text: ', err);
            });

        });
    });
}