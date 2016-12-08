(function() {
    var form = document.forms[0];
    var miniature = document.getElementsByClassName("miniature");
    var inputFile = form.imgperso;
    var submit = form.submit;
    var frame;
    var radiolist = document.forms.uploadImg.frame;
    var streaming = false,
      video        = document.querySelector('#video'),
      canvas       = document.querySelector('#canvas'),
      photo        = document.querySelector('#photo'),
      startbutton  = document.querySelector('#startbutton'),
      width = 800,
      height = 600;



    navigator.getMedia = ( navigator.getUserMedia ||
                         navigator.webkitGetUserMedia ||
                         navigator.mozGetUserMedia ||
                         navigator.msGetUserMedia);

    navigator.getMedia(
    {
      video: true,
      audio: false
    },
    function(stream) {
      if (navigator.mozGetUserMedia) {
        video.mozSrcObject = stream;
    } else {
        var vendorURL = window.URL || window.webkitURL;
        video.src = vendorURL ? vendorURL.createObjectURL(stream) : stream;
      }
      video.play();
    },
    function(err) {
      errorWebcam(video);
    }
    );

    function checkRadio(){
        for (i=0;i<radiolist.length;i++) {
            if (radiolist[i].checked == true){
                return(true);
            }
        }
        return (false);
    }

    function takepicture() {
        canvas.width = width;
        canvas.height = height;
        canvas.getContext('2d').drawImage(video, 0, 0, width, height);
        var data = canvas.toDataURL('image/png');
        photo.setAttribute('src', data);
        sendData(data);
    }

    function foreachBindDel(miniatureNodeList){
        for (i=0;i<miniatureNodeList.length;i++) {
              if (miniatureNodeList[i].hasChildNodes()){
                  child = miniatureNodeList[i].firstChild;
                  while (child !== null )
                  {
                      if (child.tagName === "A" ){
                          linkDel = child;
                          bindLinkDel(linkDel);
                          break;
                      }
                      child = child.nextSibling;
                  }
              }
          }
    }

    function bindLinkDel(linkElement){
        linkElement.addEventListener("click",function(event){
            event.preventDefault();
            var xmlhttp = new XMLHttpRequest();
            url = this.getAttribute("href");
            xmlhttp.open("GET", url + "&type=ajax", true);
            xmlhttp.send(null);
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && (xmlhttp.status == 200 || xmlhttp.status == 0)) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("GET", "index.php?controller=Mounting&action=listMounting", true);
                    xhr.send(null);
                    xhr.onreadystatechange = function() {
                         if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
                             aside = document.getElementById("aside");
                             aside.innerHTML = this.responseText;
                             miniature = document.getElementsByClassName("miniature");
                             foreachBindDel(miniature);
                         }
                     };
                }
            }
        });
    }

    function sendData(data)
    {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.open("POST", "index.php?controller=Mounting&action=addMounting", true);
      xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
      xmlhttp.send("frame=" + frame + "&image=" + data);
      xmlhttp.onreadystatechange = function() {
          if (xmlhttp.readyState == 4 && (xmlhttp.status == 200 || xmlhttp.status == 0)) {
              photo.setAttribute('src', this.responseText);
              var xhr = new XMLHttpRequest();
              xhr.open("GET", "index.php?controller=Mounting&action=listMounting", true);
              xhr.send(null);
              xhr.onreadystatechange = function() {
                   if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
                       aside = document.getElementById("aside");
                       aside.innerHTML = this.responseText;
                       miniature = document.getElementsByClassName("miniature");
                       foreachBindDel(miniature);
                   }
               };
          }
      };
    }

    function errorWebcam(node){
        ElemMsgError = createFlushError("warning", "la webcam n'est pas disponible.")
        node.parentNode.replaceChild(ElemMsgError, node);
        startbutton.parentNode.removeChild(startbutton);
        photo.parentNode.removeChild(photo);
    }

    function createFlushError(type, message){
        div = document.createElement("div");
        if (type)
            div.setAttribute("class", "alert " + type);
        else
            div.setAttribute("class", "alert");
        p = document.createElement("p");
        div.insertBefore(p, null);
        strong = document.createElement("strong");
        if(!type)
            type = "Erreur";
        strong.innerHTML = capitalize(type) + ": ";
        p.appendChild(strong);
        span = document.createElement("span");
        span.innerHTML = "&times;"
        span.setAttribute("class", "closebtn");
        span.setAttribute("onclick", 'this.parentElement.parentElement.removeChild(this.parentElement)');
        div.insertBefore(span, p);
        p.innerHTML += message;
        return(div);
    }

    function capitalize(s)
    {
        return s && s[0].toUpperCase() + s.slice(1);
    }

    function isBlank(nod)
    {
        // Utilisation des fonctionnalités String et RegExp d'ECMA-262 Edition 3
        return !(/[^\t\n\r ]/.test(nod.data));
    }
    //*******************************MAIN***************************//
    //Init radiolist for choice frame
   for (i=0;i<radiolist.length;i++) {
         radiolist[i].addEventListener("click",function(){
             frame = this.value;
             startbutton.setAttribute("name", "shoot");
             startbutton.innerHTML = "shoot";
             startbutton.style.backgroundColor = "green";
             filter = document.querySelector("#filter");
             str = new String(filter.getAttribute("src"));
             n = str.lastIndexOf("/");
             str = str.substr(0, n + 1);
             filter.setAttribute("src", str + frame + ".png");
             filter.setAttribute("alt", frame);
             filter.style.display = "block";
         });
     }

    foreachBindDel(miniature);

    video.addEventListener('canplay', function(ev){
        if (!streaming) {
          height = video.videoHeight / (video.videoWidth/width);
          video.setAttribute('width', width);
          video.setAttribute('height', height);
          canvas.setAttribute('width', width);
          canvas.setAttribute('height', height);
          streaming = true;
        }
    }, false);

    form.addEventListener('submit', function(event){
        event.preventDefault();
        submit.innerHTML = "uploading...";
        var file = inputFile.files[0];
        // Check the file type.
        if(file){
            if (!file.type.match('image.png')) {
                if (isBlank(submit.nextSibling))
                {
                    ElemMsgError = createFlushError(null, "le format du fichier est invalide.");
                    submit.parentNode.insertBefore(ElemMsgError, submit.nextSibling);
                }
                else if(submit.nextSibling.getAttribute('class') == "alert" || submit.nextSibling.getAttribute('class') == "alert success"){
                        submit.nextSibling.className = "alert";
                        p = submit.nextSibling.getElementsByTagName("p");
                        p[0].innerHTML = "<strong>Erreur:</strong> le format du fichier est invalide." ;
                }
                submit.innerHTML = "upload";
                return;
            }
            if (checkRadio()){
                var reader = new FileReader();
                reader.readAsDataURL(file)
                reader.onloadend = function(e){
                    data = e.target.result;
                    sendData(data);
                    if (isBlank(submit.nextSibling))
                    {
                        ElemMsgError = createFlushError("success", "Upload réussi ;)");
                        submit.parentNode.insertBefore(ElemMsgError, submit.nextSibling);
                    }
                    else if(submit.nextSibling.getAttribute('class') == "alert"){
                            submit.nextSibling.className = "alert success";
                            p = submit.nextSibling.getElementsByTagName("p");
                            p[0].innerHTML = "<strong>Succès:</strong> Upload réussi ;)" ;
                    }
                }
            }
            else {
                if (isBlank(submit.nextSibling))
                {
                    ElemMsgError = createFlushError(null, "Tu dois choisir un cadre.");
                    submit.parentNode.insertBefore(ElemMsgError, submit.nextSibling);
                }
                else if(submit.nextSibling.getAttribute('class') == "alert" || submit.nextSibling.getAttribute('class') == "alert success"){
                        submit.nextSibling.className = "alert";
                        p = submit.nextSibling.getElementsByTagName("p");
                        p[0].innerHTML = "<strong>Erreur:</strong> Tu dois choisir un cadre." ;
                }
                submit.innerHTML = "upload";
                return;
            }
        }else {
            if (isBlank(submit.nextSibling))
            {
                ElemMsgError = createFlushError(null, "Aucun fichier sélectionner.");
                submit.parentNode.insertBefore(ElemMsgError, submit.nextSibling);
            }
            else if(submit.nextSibling.getAttribute('class') == "alert" || submit.nextSibling.getAttribute('class') == "alert success"){
                    submit.nextSibling.className = "alert";
                    p = submit.nextSibling.getElementsByTagName("p");
                    p[0].innerHTML = "<strong>Erreur:</strong> Aucun fichier sélectionner." ;
            }
            submit.innerHTML = "upload";
            return;
        }
        submit.innerHTML = "upload";
    });

    startbutton.addEventListener('click', function(event){
        if (startbutton.name !== "choiceframe" && checkRadio()){
            takepicture();
        }
        event.preventDefault();
    }, false);

})();