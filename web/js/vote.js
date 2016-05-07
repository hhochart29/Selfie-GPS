function getXhr(){
    var xhr = null;
    if(window.XMLHttpRequest)
    xhr = new XMLHttpRequest();
    else if(window.ActiveXObject){
        try {
            xhr = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }
    }
    else {
        alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...RIP");
        xhr = false;
    }
    return xhr
}

function fonctionvote(idphoto, vote) {
    var xhr = getXhr()
    xhr.onreadystatechange = function() {
        if(xhr.readyState == 4 && xhr.status == 200){
            reponse = xhr.responseText;
            var spanp = document.getElementById('p_'+idphoto);
            var spann = document.getElementById('n_'+idphoto);
            var progresslike = document.getElementById('b_'+idphoto);

            if (vote === 1) {
                if (spanp.style.color!=="green") {
                    spanp.innerHTML = parseInt(spanp.innerHTML)+1;
                    spanp.style.color="green";
                    if (spann.style.color==="red") {
                        spann.innerHTML = parseInt(spann.innerHTML)-1;
                        spann.style.color="black";
                    }

                }
            } else {
                if (spann.style.color!=="red") {
                    spann.innerHTML = parseInt(spann.innerHTML)+1;
                    spann.style.color="red";
                    if (spanp.style.color==="green") {
                        spanp.innerHTML = parseInt(spanp.innerHTML)-1;
                        spanp.style.color="black";
                    }
                }
            }
            var votetot = parseInt(spanp.innerHTML) + parseInt(spann.innerHTML);
            var voteposval = (spanp.innerHTML * 100) / votetot;
            progresslike.style.width = parseInt(voteposval)+"%";
        }
    }
    if (vote === 1) {
        xhr.open("GET", "votepositif/"+idphoto, true);
    } else {
        xhr.open("GET", "votenegatif/"+idphoto, true);
    }
    xhr.send(null);
}
