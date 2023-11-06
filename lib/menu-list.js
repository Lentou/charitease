document.addEventListener('DOMContentLoaded', function () {
    var tabs = document.querySelectorAll('.menu-list a');

    for (var i = 0; i < tabs.length; i++) {
        tabs[i].addEventListener('click', function (event) {
            event.preventDefault();

            var target = event.target.getAttribute('href').replace('#', '');

            var contents = document.querySelectorAll('.content');
            for (var j = 0; j < contents.length; j++) {
                contents[j].classList.add('is-hidden');
            }

            document.getElementById(target).classList.remove('is-hidden');

            var links = document.querySelectorAll('.menu-list a');
            for (var k = 0; k < links.length; k++) {
                links[k].classList.remove('is-active');
            }

            event.target.classList.add('is-active');
        });
    }
});