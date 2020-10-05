document.getElementById('upload').addEventListener("change",deleteDom);

function deleteDom(){
    // console.log(document.getElementById('photo').children[0].value);
        document.getElementById("photo").innerHTML= '<input type="hidden" id="check" name="check" value="true">';
}

function deletePic() {
        document.getElementById('photo').innerHTML = '<input type="hidden" id="check" name="check" value="false">'

}
