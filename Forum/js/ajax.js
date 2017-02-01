function ajax(method, getUrl, paramName, paramValue, elementId) {
    var param;
    switch (paramName) {
        case 'cid':
            param = {"cid" : paramValue};
            break;
        case 'fid':
            param = {"fid" : paramValue};
            break;
        case 'tid':
            param = {"tid" : paramValue};
            break;
        case 'pid':
            param = {"pid" : paramValue};
            break;
        case 'uid':
            param = {"uid" : paramValue};
            break;
    }
    $.ajax({
        url: getUrl,
        type: method,
        data: param,
        success: function (result) {
          document.getElementById(elementId).innerHTML = result;
        }
    }); 
}