const closebtn = document.getElementById("closebtn");
const help = document.getElementById('help');

document.getElementById('logo').addEventListener('click', () => {
    console.log(document.location.pathname);
    if(document.location.pathname !== '/index.html') {
        document.location.href = '../index.html';
        return;
    } // Change this if using php.

    document.location.href = 'index.html';
});

if(closebtn || help) {
    closebtn.addEventListener('click', () => {
        document.getElementsByTagName('main')[0].style.display = 'none';
    });
    
    help.addEventListener('click', () => {
        document.getElementsByTagName('main')[0].style.display = 'grid';
    })
}