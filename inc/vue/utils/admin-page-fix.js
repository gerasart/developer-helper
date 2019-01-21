function adminPageFix () {
    const urlParams = new URLSearchParams(window.location.search);
    let page = urlParams.get('page');

    if ( page ) {
        console.log(page);

        let state = `#/${page}`;
        let title = document.querySelector('title').textContent;

        if (window.history.pushState) {
            // IE10, Firefox, Chrome, etc.
            window.history.pushState(page, title, state);
        } else {
            // IE9, IE8, etc
            window.location.hash = state;
        }
    }
}

export default adminPageFix;