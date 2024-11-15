function addTitleToUrl() {
    const titleValue = document.getElementById('titleInput').value;

    const currentUrl = new URL(window.location.href);

    const params = currentUrl.searchParams;

    params.set('title', titleValue);

    const newUrl = currentUrl.origin + currentUrl.pathname + '?' + params.toString();

    window.location.href = newUrl;
}
