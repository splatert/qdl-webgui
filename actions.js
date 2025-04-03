



function preview(button, audioObject) {
    
    var btns = document.getElementsByClassName('preview');
    for (btn in btns) {
        btns[btn].src = 'img/misc/preview.png';
    }

    if (button) {
        button.src = 'img/misc/prev_play.png';
    }

    var audios = document.getElementsByClassName('preview-audio');
    for (let a=0; a<audios.length; a++) {
        if (audios[a] != audioObject) {
            audios[a].pause();
            audios[a].currentTime = 0;
        }
    }


    if (audioObject) {
        if (audioObject.paused) {
            audioObject.play();
        }
        else if (!audioObject.paused) {
            audioObject.pause();
            if (button) {
                button.src = 'img/misc/preview.png';
            }
        }
    }

}



async function getDLStatus(relTitle) {
    
    relTitle = relTitle.replace('/&(amp;)+/', '');
    relTitle = relTitle.replace('/[^A-Za-z0-9]/', '');

    setInterval(() => {

        fetch('status.php?rel=' + relTitle)
        .then(status => status.text())
        .then (status => {
            document.getElementsByClassName('dl-status')[0].innerHTML = status
        })

    }, 1000);

}





function loadingDialog(text) {

    var existing = document.getElementsByClassName('load-dialog');
    if (existing[0]) {
        existing[0].remove();
    }

    var bg = document.createElement('div');
    bg.className = 'load-dialog';

    bg.style.display = 'block';
    bg.style.position = 'fixed';
    bg.style.zIndex = '2';
    bg.style.top = '0';
    bg.style.left = '0';
    bg.style.width = '100%';
    bg.style.height = '100%';
    bg.style.backgroundColor = '#000000bf';

    bg.innerHTML = `
        <div style="background:white;margin: 15% auto;width:fit-content;padding:5px;">
            <div style="display:flex;">
                <img src="img/ico/load.gif">
                <span class="loading-status" style="line-height:2;margin-left:15px;">`+text+`</span>
            </div>
            <div class="dl-status">
                <span></span>
            </div>
        </div>
    `;

    if (!text || text == '') {
        bg.getElementsByClassName('loading-status')[0].style.display = 'none';
    }

    document.body.appendChild(bg);
}


function removeLoadingDialog() {
    var dialog = document.getElementsByClassName('load-dialog');
    if (dialog[0]) {
        dialog[0].remove();
    }
}