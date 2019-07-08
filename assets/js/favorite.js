const $ = require('jquery');
console.log($);

$(document).ready(function () {

    $('.article').click(function (e) {

        let id = parseInt(e.currentTarget.id);
        let url = '/article/' + id + '/favorite';
        fetch(url)
            .then(response => response.json())
            .then(json => {
                console.log(json);
                let favoriteElt = document.getElementById('favorite_' + id);
                if (json.isFavorite) {
                    favoriteElt.classList.remove('far');
                    favoriteElt.classList.add('fas');
                } else {
                    favoriteElt.classList.remove('fas');
                    favoriteElt.classList.add('far');
                }

            });
    });
});

// console.log(event);

/* let article = document.getElementsByClass('article');
 let id = article.dataset.id;

 console.log(id);
 let url = '/article/' + id + '/favorite';

 fetch(url)
     .then(response => response.json())
     .then(json => {
         let favoriteElt = document.getElementById('favorite');
         if (json.isFavorite) {
             favoriteElt.classList.remove('far');
             favoriteElt.classList.add('fas');
         } else {
             favoriteElt.classList.remove('fas');
             favoriteElt.classList.add('far');
         }

     });*/
