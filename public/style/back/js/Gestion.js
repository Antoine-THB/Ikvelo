
function click_checkbox(event) {
    //la page s'arrête rien ne se passe
    event.preventDefault();

    const url = this.href;
    const icon = this.querySelector('i')

    axios.get(url).then(function (response) {
        if (icon.classList.contains('fas')) {
            icon.classList.replace('fas','far');
        }
        else{
            icon.classList.replace('far','fas');
        }
      })
   
}


document.querySelectorAll('a.js-click').forEach(function(link){
    link.addEventListener('click', click_checkbox);
})