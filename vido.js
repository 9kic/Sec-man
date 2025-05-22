/*const loginButton = document.getElementById('loginBtn');
const videoPlayer = document.getElementById('videoPlayer');

loginButton.addEventListener('click', () => {
    videoPlayer.style.display = 'block';
    videoPlayer.play();
});

videoPlayer.addEventListener('ended', () => {
    window.location.href = 'main.html';
});*/

const videoPlayer = document.getElementById('videoPlayer');

videoPlayer.addEventListener('ended', () => {
    window.location.href = 'main.html';
});