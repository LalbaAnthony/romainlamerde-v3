
function debounce(fn, delay) {
    var timer;
    return function () {
        clearTimeout(timer);
        timer = setTimeout(fn, delay);
    };
}

// Submit the search form after 1.5 seconds of inactivity

// addEventListener('DOMContentLoaded', function () {
//     var searchForm = document.getElementById('header-search-form');
//     var searchInput = document.getElementById('header-search-input');

//     searchInput.addEventListener('input', debounce(function () {
//         searchForm.submit();
//     }, 1500));
// });
