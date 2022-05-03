function sort(criteria){
    if (window.location.search === '') {
        window.location = window.location.origin + window.location.pathname + `?sort=${criteria}`
    } else {
        window.location = splitUrl(window.location.href, "sort") + `&sort=${criteria}`;
    }
}

function splitUrl(url, filter){
    let urlParam = url.split("?")[1];
    let params = urlParam.split("&");   
    let finalUrl = `${window.location.origin + window.location.pathname}?`;
    for(let i=0; i<params.length; i++){
        if(!params[i].includes(filter)){finalUrl += `${params[i]}`};
    }
    return finalUrl;
}
