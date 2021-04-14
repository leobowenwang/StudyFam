$(document).ready(function(){
    $.ajax({
        url:"api/api.php",
        method:"GET",
        success:function(data){
            let users = data;
            let manage = document.getElementById('manage-users');
            for (user in users) {
                if (manage) {
                    manage.insertAdjacentHTML("beforeend", `
                    <tr id="row`+users[user]['id']+`">
                        <td style="text-align:center;"><a class="link" target="_blank" onclick="editUser(`+users[user]['id']+`)"><i class="fas fa-edit"></i></a></td>
                        <td>`+users[user]['id']+`</td>
                        <td id="fname`+users[user]['id']+`">`+users[user]['fname']+`</td>
                        <td id="lname`+users[user]['id']+`">`+users[user]['lname']+`</td>
                        <td id="email`+users[user]['id']+`">`+users[user]['email']+`</td>
                        <td id="passwd`+users[user]['id']+`"><span>**********</span></td>
                        <td id="del`+users[user]['id']+`"><a class="link" target="_blank" onclick="deleteUser(`+users[user]['id']+`)"><i class="far fa-trash-alt"></i></a></td>
                    </tr>
                    `);
                }
            }
            let unibtn = document.getElementById('unibtn');
            unibtn.addEventListener("click", function() {
                manage.innerHTML =  
                `<tr>
                    <th></th>
                    <th class="thead">ID</th>
                    <th class="thead">Univesity</th>
                    <th class="thead">Semester</th>
                    <th class="thead">Course</th>
                    <th class="thead"></th>
                </tr>`;
                for (user in users) {
                    if (manage) {
                        manage.insertAdjacentHTML("beforeend", `
                        <tr id="row`+users[user]['id']+`">
                            <td style="text-align:center;"><a class="link" target="_blank" onclick="editUser(`+users[user]['id']+`)"><i class="fas fa-edit"></i></a></td>
                            <td>`+users[user]['id']+`</td>
                            <td id="uni`+users[user]['id']+`">`+users[user]['university']+`</td>
                            <td id="semester`+users[user]['id']+`">`+users[user]['semester']+`</td>
                            <td id="course`+users[user]['id']+`">`+users[user]['studycourse']+`</td>
                            <td id="del`+users[user]['id']+`"><a class="link" target="_blank" onclick="deleteUser(`+users[user]['id']+`)"><i class="far fa-trash-alt"></i></a></td>
                        </tr>
                        `);
                    }
                }
            });
            let basicbtn = document.getElementById('basicbtn');
            basicbtn.addEventListener("click", function() {
                manage.innerHTML =  
                `<tr>
                    <th></th>
                    <th class="thead">ID</th>
                    <th class="thead">Firstname</th>
                    <th class="thead">Lastname</th>
                    <th class="thead">Email</th>
                    <th class="thead">Password</th>
                    <th class="thead"></th>
                </tr>`;
                for (user in users) {
                    if (manage) {
                        manage.insertAdjacentHTML("beforeend", `
                        <tr id="row`+users[user]['id']+`">
                            <td style="text-align:center;"><a class="link" target="_blank" onclick="editUser(`+users[user]['id']+`)"><i class="fas fa-edit"></i></a></td>
                            <td>`+users[user]['id']+`</td>
                            <td id="fname`+users[user]['id']+`">`+users[user]['fname']+`</td>
                            <td id="lname`+users[user]['id']+`">`+users[user]['lname']+`</td>
                            <td id="email`+users[user]['id']+`">`+users[user]['email']+`</td>
                            <td id="passwd`+users[user]['id']+`"><span>**********</span></td>
                            <td id="del`+users[user]['id']+`"><a class="link" target="_blank" onclick="deleteUser(`+users[user]['id']+`)"><i class="far fa-trash-alt"></i></a></td>
                        </tr>
                        `);
                    }
                }
            });
        }
    })
});

function deleteUser (id) {
    $.ajax({
        url:"./api/api.php?id="+id,
        method:"DELETE",
        success:function(){
            document.location.reload(true);
            alert("SUCCESSFULLY DELETED!");
        }
    });
}

function editUser (id) {
    // basic changes
    let fname = document.getElementById("fname"+id);
    let lname = document.getElementById("lname"+id);
    let email = document.getElementById("email"+id);
    let passwd = document.getElementById("passwd"+id);
    if (fname) {
        fname.innerHTML = "<input class='input' id='fnamevalue' value=''></input>";
        lname.innerHTML = "<input class='input' id='lnamevalue' value=''></input>";
        email.innerHTML = "<input class='input' id='emailvalue' value=''></input>";
        passwd.innerHTML = "<input class='input' id='passwdvalue' value=''></input>";
    }

    // university changes 
    let university = document.getElementById("uni"+id);
    let semester = document.getElementById("semester"+id);
    let course = document.getElementById("course"+id);
    if (university) {
        university.innerHTML = "<input class='input' id='univalue' value=''></input>";
        semester.innerHTML = "<input class='input' id='semestervalue' value=''></input>";
        course.innerHTML = "<input class='input' id='coursevalue' value=''></input>";
    }

    let del = document.getElementById("del"+id);
    del.innerHTML = `<a class="link" target="_blank" id="accept"><i class="fas fa-user-check"></i></a>`;
    let accept = document.getElementById("accept");
    accept.addEventListener("click", function() {
        let url = '';
        // basic value changes
        if (fname) {
            let fnamevalue = document.getElementById('fnamevalue').value;
            let lnamevalue = document.getElementById('lnamevalue').value;
            let emailvalue = document.getElementById('emailvalue').value;
            let passwdvalue = document.getElementById('passwdvalue').value;
            url = "./api/api.php?id="+id+"&fname="+fnamevalue+"&lname="+lnamevalue+"&email="+emailvalue+"&passwd="+passwdvalue;
        }
        // university value changes
        if (university) {
            let univalue = document.getElementById('univalue').value;
            let semestervalue = document.getElementById('semestervalue').value;
            let coursevalue = document.getElementById('coursevalue').value;
            url = "./api/api.php?id="+id+"&uni="+univalue+"&semester="+semestervalue+"&course="+coursevalue; 
        }

        let ajax = new XMLHttpRequest();
        ajax.open("PUT",url,true);
        ajax.send();
        ajax.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.location.reload(true);
                alert("SUCCESSFULLY UPDATED!");
            }
        };
    });
}

