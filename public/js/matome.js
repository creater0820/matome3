document.getElementById('header').innerHTML="hello";
function anchor(id) {
    var userComment = document.getElementById('post_' + id).querySelector('td.comment').innerText;
    document.getElementById('textarea').value = ">>" + id;
}
// function anchor(id) {
//     var userComment = document.getElementById('post_'+id).querySelector('td.comment').innerText;
//     document.getElementById('textarea').value = ">>"+id+"\n"+userComment;
// }
function display(id) {
    // var element = document.getElementById('post_'+id).innerText;
    var element = document.getElementById('post_'+id);
    // var original = element.style.display;
    element.style.visibility = "hidden";
    // document.getElementById('post_'+id).innerHTML=element;
}
function scrollUp() {
    window.scrollTo(0, 50);
}

