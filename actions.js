



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



async function getDLStatus(relTitle, cover) {
    
    relTitle = relTitle.replace('/&(amp;)+/', '');
    relTitle = relTitle.replace('/[^A-Za-z0-9]/', '');


    if (cover && cover != '') {
        document.querySelector('.loading-dialog .album-cover').src = cover;
    }

    document.querySelector('.loading-dialog .loading-status').style.display = 'none';
    document.querySelector('.loading-dialog .wheel').style.display = 'none';
    document.querySelector('.dl-status').style.display = 'unset';
    document.querySelector('.loading-dialog .album-cover').style.display = 'unset';
    document.querySelector('.dl-status .top .status-large').innerText = 'Downloading album...';


    setInterval(() => {

        fetch('status.php?rel=' + relTitle)
        .then(status => status.text())
        .then (status => {

            var statusData = JSON.parse(status)[0]; 
            console.log(statusData);

            if (statusData[0] && statusData[0] != '' && statusData[1] && statusData[1] != '') {
                var percent = Math.round( (parseFloat(statusData[0]) / parseFloat(statusData[1])) * 100 );

                if (percent > 100) {
                    percent = 0;
                }
                var dlprog = statusData[0] + ' / ' + statusData[1] + ' (' + percent + '%)';
                
                document.querySelector('.dl-status .bottom .prog').innerText = dlprog;
                document.querySelector('.dl-status .bottom .prog-bar .bar').style.width = percent + '%';
            }

            if (statusData[3] && statusData[3] != '') {
                statusData[3] = statusData[3].replace(/([a-z.])/gm, '');
                document.querySelector('.dl-status .top .status').innerText = 'Downloading track ' + statusData[3] + '...';
            }

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
        <div style="background:white; margin: 15% auto; width:fit-content; padding:5px; display:flex;">
        
            <div class="loading-dialog">
                <img class="album-cover" style="height:100px; width:100px; display:none;">
                <img class="wheel" src="img/ico/load.gif">
                <span class="loading-status" style="line-height:2;margin-left:15px;">`+text+`</span>
            </div>

            <div class="dl-status" style="display:none;">
                <div class="top">
                    <span class="status-large">NaN / NaN</span><button class="abort-dl">Abort</button>
                    <br>
                    <span class="status">Please wait...</span>
                </div>

                <div class="bottom">
                    <span class="prog">0/0</span>
                    <div class="prog-bar">
                        <div class="bar"></div>
                    </div>
                </div>

            </div>
        </div>
    `;

    if (!text || text == '') {
        bg.getElementsByClassName('loading-status')[0].style.display = 'none';
    }

    document.body.appendChild(bg);

    var abortdl = document.getElementsByClassName('abort-dl');
    if (abortdl[0]) {
        abortdl[0].addEventListener('click', function(){
            var thisUrl = window.location.href;
            window.location.href = 'download.php?mode=abort-dl&return=' + thisUrl;
        })  
    }

}


function removeLoadingDialog() {
    var dialog = document.getElementsByClassName('load-dialog');
    if (dialog[0]) {
        dialog[0].remove();
    }
}