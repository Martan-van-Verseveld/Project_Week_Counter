const closebtn = document.getElementById("closebtn");
const help = document.getElementById('help');

document.getElementById('logo').addEventListener('click', () => {
    console.log(document.location.pathname);
    if(document.location.pathname !== '/index.php?page=home') {
        document.location.href = '/index.php?page=home';
        return;
    } // Change this if using php.

    document.location.href = '/index.php?page=home';
});

if(closebtn || help) {
    closebtn.addEventListener('click', () => {
        document.getElementsByTagName('main')[0].style.display = 'none';
    });
    
    help.addEventListener('click', () => {
        document.getElementsByTagName('main')[0].style.display = 'grid';
    })
}